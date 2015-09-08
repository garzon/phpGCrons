<?php
// 610204679@qq.com

class MonthlyCronJob extends CronJob {
	public $runTime = '*-*-01-02-00-*';

	public function work() {
		//$this->log("Monthly CronJob success.");
	}
}

