<?php
/**
*	Ccc_Core_Model_Journal_Trigger_Row
**/
class Cron_Model_Cron_Schedule_Row extends Core_Model_Table_Row_Abstract
{
	/**
    *	$_tableClass protected Variable. It contains Cron Schedule model class name.
    *	@var mixed
    **/
    protected $_tableClass = 'Cron_Model_Cron_Schedule';
    public $cron_expr = null;
    
    public function isDuplicateRecord()
    {
        $select = $this->select();    
        if($this->scheduled_at)
        {
            $select->where('scheduled_at = ?', $this->scheduled_at);
            
            if($this->cron_id)
            {
                $select->where('cron_id = ?', $this->cron_id);
            }
            
            //$select->where('status = ?', Cron_Model_Cron_Schedule::STATUS_PENDING);    
            
            if($this->schedule_id)
            {
                $select->where('schedule_id != ?', $this->schedule_id);
            }
            
            if($this->getTable()->fetchRow($select))
            {
                return true;
            }
        }
        
        return false;
    }
    
    public function trySchedule($time)
    {
        $e = $this->getCronExpressionArray($this->cron_expr);
        
        if (!$e || !$time) {
            return false;
        }
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }
        
        $d = getdate($time);
        
        $match = $this->matchCronExpression($e[0], $d['minutes'])
            && $this->matchCronExpression($e[1], $d['hours'])
            && $this->matchCronExpression($e[2], $d['mday'])
            && $this->matchCronExpression($e[3], $d['mon'])
            && $this->matchCronExpression($e[4], $d['wday']);

        if ($match) {
            $this->created_at = strftime('%Y-%m-%d %H:%M:%S', time());
            $this->scheduled_at = strftime('%Y-%m-%d %H:%M:00', $time);
        }
        return $match;
    }
    
    public function getCronExpressionArray($expr)
    {
        return preg_split('#\s+#', $expr, null, PREG_SPLIT_NO_EMPTY);
    }
    
    public function matchCronExpression($expr, $num)
    {
        // handle ALL match
        if ($expr==='*') {
            return true;
        }

        // handle multiple options
        if (strpos($expr,',')!==false) {
            foreach (explode(',',$expr) as $e) {
                if ($this->matchCronExpression($e, $num)) {
                    return true;
                }
            }
            return false;
        }

        // handle modulus
        if (strpos($expr,'/')!==false) {
            $e = explode('/', $expr);
            if (sizeof($e)!==2) {
                throw new Cron_Model_Exception("Invalid cron expression, expecting 'match/modulus': ".$expr);
            }
            if (!is_numeric($e[1])) {
                throw new Cron_Model_Exception("Invalid cron expression, expecting numeric modulus: ".$expr);
            }
            $expr = $e[0];
            $mod = $e[1];
        } else {
            $mod = 1;
        }

        // handle all match by modulus
        if ($expr==='*') {
            $from = 0;
            $to = 60;
        }
        // handle range
        elseif (strpos($expr,'-')!==false) {
            $e = explode('-', $expr);
            if (sizeof($e)!==2) {
                throw new Cron_Model_Exception("Invalid cron expression, expecting 'from-to' structure: ".$expr);
            }

            $from = $this->getNumeric($e[0]);
            $to = $this->getNumeric($e[1]);
        }
        // handle regular token
        else {
            $from = $this->getNumeric($expr);
            $to = $from;
        }

        if ($from===false || $to===false) {
            throw new Cron_Model_Exception("Invalid cron expression: ".$expr);
            
        }

        return ($num>=$from) && ($num<=$to) && ($num%$mod===0);
    }
    
    public function getNumeric($value)
    {
        static $data = array(
            'jan'=>1,
            'feb'=>2,
            'mar'=>3,
            'apr'=>4,
            'may'=>5,
            'jun'=>6,
            'jul'=>7,
            'aug'=>8,
            'sep'=>9,
            'oct'=>10,
            'nov'=>11,
            'dec'=>12,

            'sun'=>0,
            'mon'=>1,
            'tue'=>2,
            'wed'=>3,
            'thu'=>4,
            'fri'=>5,
            'sat'=>6,
        );

        if (is_numeric($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = strtolower(substr($value,0,3));
            if (isset($data[$value])) {
                return $data[$value];
            }
        }

        return false;
    }
}