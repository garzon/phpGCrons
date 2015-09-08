<?php
// 610204679@qq.com

class WeeklyCronJob extends CronJob {
	public $runTime = '*-*-*-01-00-1';

	public function work() {
		//$this->log("Weekly CronJob success.");
	}
}

