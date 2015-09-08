<?php
// 610204679@qq.com

class WebDebugTestCronJob extends CronJob {
	public $runTime = '9999-99-99-99-99-99'; // never run in cli
	public $runOnWebForDebug = true;

	public function work() {
		self::log('Web CronJob success.');
	}
}