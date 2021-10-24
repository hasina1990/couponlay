<?php
/**
*	Cache_Model_Cache
**/
class Cron_Model_Maintainance extends Core_Model_Table_Abstract
{
	 public function sendMailToCron5()
     {
           $users = array(
            "ansari.hasina@gmail.com"
              );
            $sender_email     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_email');
            $sender_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_name');
            $website_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/website/name');
            
            if($sender_email)
            {   
                foreach($users as $_user)
                {
                   $mail = new Zend_Mail();       
                    $mail->setFrom($sender_email, $sender_name);            
                    $mail->addTo($_user);       
                    $mail->setSubject("Cron 5 minute from {$website_name}.");
                    $mail->setBodyText("Cron 5 minute. This is the Test Email From {$website_name}. Kindly Ignore this Email.");
                    if($mail->send())
                    {
                       
                    } 
                } 
            }
            return false;
     }
     
     public function sendMailToCron10()
     {
              $users = array(
            "ansari.hasina@gmail.com"
              );
              
            $sender_email     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_email');
            $sender_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_name');
            $website_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/website/name');
            
            if($sender_email)
            {   
                foreach($users as $_user)
                {
                   $mail = new Zend_Mail();       
                    $mail->setFrom($sender_email, $sender_name);            
                    $mail->addTo($_user);       
                    $mail->setSubject("Cron 10 minutes from {$website_name}.");
                    $mail->setBodyText("Cron 10 minutes.. This is the Test Email From {$website_name}. Kindly Ignore this Email.");
                    if($mail->send())
                    {
                       
                    } 
                } 
            }
            return false;
     }
     
     public function sendMailToCronHour()
     {
             $users = array(
            "ansari.hasina@gmail.com".
            "jenny.henny.2013@gmail.com"
                          );
              
            $sender_email     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_email');
            $sender_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_name');
            $website_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/website/name');
            
            echo "Mail Content : ";
            echo $sender_email."<br/>";
            echo $sender_name."<br/>";
            echo $website_name."<br/>";
            
            try
            {
            if($sender_email)
            {   
                foreach($users as $_user)
                {
                   $mail = new Zend_Mail();       
                    $mail->setFrom($sender_email, $sender_name);            
                    $mail->addTo($_user);       
                    $mail->setSubject("Cron 1 Hour from {$website_name}.");
                    $mail->setBodyText("Cron 1 hour.. This is the Test Email From {$website_name}. Kindly Ignore this Email.");
                    if($mail->send())
                    {
                         echo "send";
                    } 
                    else
                    {
                        echo "not send";
                    }
                } 
            }
            }
            catch(Exception $e)
            {
                echo "Exception : ".$e->getMessage();
            }
            return false;
     }
}