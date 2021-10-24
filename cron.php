<?php 

require_once "bootstrap.php";

$model = Ccc::getModel('cron/observer');
$model->dispatch();

?>