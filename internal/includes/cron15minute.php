<?php
/* ==============

This file executes every 15 minutes, initiated by a cron job
Ex: 15,30,45,59 * * * * php cron15minute.php

This script is the main scheduled task runner

================ */
require_once(dirname(__FILE__)."/../app.php");
ini_set('error_log', AppCfg::DIR_BASE.AppCfg::DIR_LOGS.'php_cron_'. date('m_d_y', time()) .'.txt');

//******************************** SYNC FAILURE QUEUE ****************************
$t = microtime(1);
$PM = \rocketD\plugin\PluginManager::getInstance();
$result = $PM->callAPI('UCFCourses', 'sendFailedScoreSetRequests', array(), true);
\rocketD\util\Log::profile('cron', "'sendFailedScoreSetRequests','".time()."','".round((microtime(true) - $t),5)."','{$result['updated']}','{$result['total']}'\n");


//******************************** NID UPDATES ****************************
$t = microtime(1);
$AM = \rocketD\auth\AuthManager::getInstance();
$authMods = $AM->getAllAuthModules();
foreach($authMods AS $curAuthMod)
{
	if(method_exists($curAuthMod, 'updateNIDChanges'))
	{
		$result = $curAuthMod->updateNIDChanges();
		\rocketD\util\Log::profile('cron', "'updateNIDChanges','".time()."','".time()."','".round((microtime(true) - $t),5)."','{$result['updated']}','{$result['total']}'\n");
	}
}

//******************************** CALCULATE VISIT LOGS ****************************
$t = microtime(1);
$VM = \obo\VisitManager::getInstance();
$count = $VM->calculateVisitTimes();
\rocketD\util\Log::profile('cron', "'calculateVisitLogs','".time()."','".round((microtime(true) - $t),5)."','{$count}','{$count}'\n");


?>