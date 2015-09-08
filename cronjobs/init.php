<?php
// 610204679@qq.com
/* -----------------------
       phpGCrons V1.0
       Author: garzon
         2015-09-08
   ----------------------- */

$phpGCrons_root = dirname(__FILE__);
spl_autoload_register(function ($class_name) use ($phpGCrons_root) {
	$file = $phpGCrons_root . '/' . $class_name . '.php';
	if (file_exists($file)) require_once($file);
});

CronJob::$is_cli = (PHP_SAPI == "cli");
(new CronJob())->__worker();