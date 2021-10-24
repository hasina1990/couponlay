<?php

/* 
# Cron Settings config variables
    - schedule_generate_every
    - schedule_ahead_for
    - schedule_lifetime
    - history_cleanup_every
    - history_success_lifetime
    - history_failure_lifetime
    - cron_last_schedule_generate_at
    - cron_last_history_cleanup_at
*/
class Cron_Model_Observer
{
    public $settings;
    protected $_pendingSchedules;
    protected $_jobRoots;
    public $cronGroupId;
    
    const REGEX_RUN_MODEL = '#^([a-z0-9_]+/[a-z0-9_]+)::([a-z0-9_]+)$#i';
    const GROUP_NAME   = 'Cron';
   
    public function __construct()
    {
        $this->settings = $this->getSettings();
    }
    
    public function dispatch()
    {
        try
        {
            if(!isset($this->settings['schedule_lifetime']))
            {
                throw new Exception("schedule_lifetime key is not Exist in Settings.");
            }
            
            if(!$this->getPendingSchedules()) 
            {
                throw new Exception("Not Found any Pending Schedules.");
            }
            
            if(!$this->getJobRoots())
            {
                throw new Exception("Jobs Root are not available.");
            }
            
            $this->_processPendingScheduledCron();            
        }
        catch(Exception $e)
        {
            //
        }
        
        $this->generate();
        $this->cleanup();
    }
    
    protected function _processPendingScheduledCron()
    {
        $pendingSchedules = $this->getPendingSchedules();
        $jobsRoot =  $this->getJobRoots();
        $scheduleLifetime = $this->settings['schedule_lifetime'] * 60;
        $now = time();
             
        foreach($pendingSchedules as $schedule)
        {
           try
           {
               $errorStatus =  Cron_Model_Cron_Schedule::STATUS_ERROR;
               $errorMessage = 'Unknown error.';

                if(!isset($jobsRoot[$schedule->cron_id]))
                {
                    throw new Cron_Model_Exception('Job-code not found.');
                }
               
                $runConfig = $jobsRoot[$schedule->cron_id];
                $time = strtotime($schedule->scheduled_at);

                if($time > $now) 
                {
                    continue;
                }
                
                if($time < ($now - $scheduleLifetime)) 
                {
                    $errorStatus = Cron_Model_Cron_Schedule::STATUS_MISSED;
                    throw new Cron_Model_Exception('Too late for the schedule.');
                }
                
                $callback = array();
                $arguments = array();
                if(isset($runConfig['model']))
                {
                    if(!preg_match(self::REGEX_RUN_MODEL, (string)$runConfig['model'], $run)) 
                    {
                        throw new Cron_Model_Exception('Invalid model/method definition, expecting "model/class::method".');
                    }
                    
                    if( (!isset($run[1])) || (!isset($run[2])))
                    {
                        throw new Cron_Model_Exception('Invalid model/method definition, expecting "model/class::method".');
                    }
                    
                    if(!($model = Ccc::getModel($run[1])) || !method_exists($model, $run[2])) 
                    {
                        throw new Cron_Model_Exception(sprintf('Invalid callback: %s::%s does not exist', $run[1], $run[2]));
                    }
                    
                    $callback = array("model"=>$model, "method"=>$run[2]);
                   // $arguments = array($schedule);
                }
                
                if(empty($callback)) 
                {
                    throw new Cron_Model_Exception('No callbacks found');
                }

                if (!Ccc::getModel('cron/cron_schedule')->tryLockJob($schedule)) 
                {
                     continue; // another cron started this job intermittently, so skip it
                }
                
                /** though running status is set in tryLockJob we must set it here because the object
                was loaded with a pending status and will set it back to pending if we don't set it here */ 
                $schedule->status = Cron_Model_Cron_Schedule::STATUS_RUNNING;
                $schedule->executed_at = strftime('%Y-%m-%d %H:%M:%S', time());
                $schedule->save();
                
                $callback["model"]->$callback["method"]();
                //call_user_func_array($callback, $arguments);
                $schedule->status = Cron_Model_Cron_Schedule::STATUS_SUCCESS;
                $schedule->finished_at = strftime('%Y-%m-%d %H:%M:%S', time());
           }
           catch(Cron_Model_Exception $e)
           {
               $schedule->status = $errorStatus;
               $schedule->messages = (string)$e->getMessage();
           }
           catch(Exception $e)
           {
               $schedule->status = $errorStatus;
               $schedule->messages = (string)$e->getMessage();
           }
           
           $schedule->save();
        }
    }
   
    /**
     * Generate cron schedule
     */
    public function generate()
    {
        try
        {
            if(!isset($this->settings['cron_last_schedule_generate_at']))
            {
                throw new Exception("cron_last_schedule_generate_at key is not set in Settings.");
            }
            
            if(!isset($this->settings['schedule_generate_every']))
            {
                throw new Exception("schedule_generate_every key is not set in Settings.");
            }
            
            if(!isset($this->settings['schedule_ahead_for']))
            {
                throw new Exception("schedule_ahead_for key is not set in Settings.");
            }
            
            $generationTime = time() - ($this->settings['schedule_generate_every']*60);
            if($this->settings['cron_last_schedule_generate_at'] > $generationTime)   //check if schedule generation is needed
            {
                return $this;
            }
            
            if(!$this->getJobRoots())
            {
                throw new Exception("There no Job to generate Schedule.");
            }
            
            $this->_generateJobs();
            $this->updateSetting('cron_last_schedule_generate_at', time());
            
            return $this;
        }
        catch(Exception $e)
        {
            return $this;
        }
    }

    protected function _generateJobs()
    {
        $scheduleAheadFor = $this->settings['schedule_ahead_for']*60;
        $scheduleModel = Ccc::getModel('cron/cron_schedule');

        $jobs = $this->getJobRoots();
        
        foreach ($jobs as $jobCode => $jobConfig) 
        {
            if(!isset($jobConfig['cron_expr'])) 
            {
                continue;
            }
          
            $cronExpr = (string)$jobConfig['cron_expr'];
            $now = time();
            $timeAhead = $now + $scheduleAheadFor;

            for($time = $now; $time < $timeAhead; $time += 60) 
            {
                $ts = strftime('%Y-%m-%d %H:%M:00', $time);
               
                $schedule = $scheduleModel->createRow(); 
                $schedule->cron_id = $jobConfig["cron_id"];
                $schedule->cron_expr = $cronExpr;
                $schedule->status = Cron_Model_Cron_Schedule::STATUS_PENDING;
                            
                if (!$schedule->trySchedule($time)) 
                {
                    continue;     // time does not match cron expression 
                }
                
                if($schedule->isDuplicateRecord())
                {
                   continue;     // already scheduled.      
                }
                
                $schedule->save();
            }
        }
        return $this;
    }
   
    public function cleanup()
    {
        try
        {
            if(!isset($this->settings['cron_last_history_cleanup_at']))
            {
                throw new Exception("cron_last_history_cleanup_at key is not exist in setting.");
            }
            
            if(!isset($this->settings['history_success_lifetime']))
            {
                throw new Exception("history_success_lifetime key is not exist in setting.");
            }
            
            if(!isset($this->settings['history_failure_lifetime']))
            {
                throw new Exception("history_failure_lifetime key is not exist in setting.");
            }
            
            $cleanupTime = time() - ($this->settings['history_cleanup_every']*60);
            if ($this->settings['cron_last_history_cleanup_at'] > $cleanupTime) // check if history cleanup is needed
            {
                return $this;
            }
            
            $historyLifetimes = array(
                    Cron_Model_Cron_Schedule::STATUS_SUCCESS => $this->settings['history_success_lifetime']*60,
                    Cron_Model_Cron_Schedule::STATUS_MISSED => $this->settings['history_failure_lifetime']*60,
                    Cron_Model_Cron_Schedule::STATUS_ERROR => $this->settings['history_failure_lifetime']*60,
                );
            
            $history = $this->_getScheduledEntriesForCleanUp();
            if(!$history->count())
            {
                throw new Exception("Scheduled Entries are not available for Cleanup.");
            }
            
            $now = time();
            foreach ($history as $_schedule) 
            {
                if (strtotime($_schedule->executed_at) < $now-$historyLifetimes[$_schedule->status]) 
                {
                    $_schedule->delete();
                }
            }
            
            $this->updateSetting('cron_last_history_cleanup_at', time());  // save time history cleanup was ran with no expiration 
            
            return $this;
        }
        catch(Exception $e)
        {
            return $this;
        }
    }
    
    public function updateSetting($key, $value)
    {
        $access_key = strtolower(self::GROUP_NAME).'/'.$key;
        $config = Ccc::getModel('config/system_config')->fetchRow("access_key = '".$access_key."'");
        if(!$config)
        {
            return $this;
        }
        
        if($config->system_config_group_id==$this->cronGroupId)
        {
            $config->value = $value;
            $config->save();
        }
        
        return $this;
    }
    
    public function getSettings()
    {
        try
        {
            $Group = Ccc::getModel("config/system_config_group")->fetchRow("name like '".self::GROUP_NAME."'");
        
            if(!$Group)
            {
                throw new Exception("Group doesn't Exist.");
            }
            
            $this->cronGroupId = $Group->system_config_group_id;
            
            $configModel = Ccc::getModel('config/system_config');
            $select = $configModel->select()
                        ->where('system_config_group_id = ?', $this->cronGroupId);
            $collection = $configModel->fetchAll($select);
            
            if(!$collection)
            {
                throw new Exception("crons are not available.");
            }
            
            $result = array();
            foreach($collection as $config)
            {
                $result[substr($config->access_key,strlen(self::GROUP_NAME.'/'))] = $config->value;
            }
            
            return $result;
        }
        catch(Exception $e)
        {
            return array();
        }
    }
   
    public function getJobRoots()
    {
        if(!$this->_jobRoots)
        {
            $this->_jobRoots = Ccc::getModel('cron/cron')->getCollectionByCronID(); 
        }
          
        return $this->_jobRoots;
    }
    
    public function getPendingSchedules()
    {
        if(!$this->_pendingSchedules) 
        {
            $model = Ccc::getModel('cron/cron_schedule');
            $select = $model->select()
                      ->where('status = ?', Cron_Model_Cron_Schedule::STATUS_PENDING);
            $this->_pendingSchedules = $model->fetchAll($select);
        }
        
        return $this->_pendingSchedules;
    }
   
    protected function _getScheduledEntriesForCleanUp()
    {
        $scheduleModel = Ccc::getModel('cron/cron_schedule');
        $select = $scheduleModel->select()
                                ->where('status IN (?)', array(
                                    Cron_Model_Cron_Schedule::STATUS_SUCCESS,
                                    Cron_Model_Cron_Schedule::STATUS_MISSED,
                                    Cron_Model_Cron_Schedule::STATUS_ERROR));
        return $scheduleModel->fetchAll($select); 
    }
}
