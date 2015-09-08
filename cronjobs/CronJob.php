<?php
// 610204679@qq.com
/* -----------------------
       phpGCrons V1.0
       Author: garzon
         2015-09-08
   ----------------------- */

class CronJob {
	const DATE_FORMAT = 'Y-m-d-H-i-N';
	public static $is_cli = null;

	public $runTime = '*-*-*-04-00-*';
	public $runOnWebForDebug = false;

	public static $workers = [
		'WeeklyCronJob',
		'MonthlyCronJob',
		'WebDebugTestCronJob',
	]; // Cronjobs should be registered here

	// should be overrided
	public function work() {

	}

	public static function log($msg) {
		$now = time();
		$stnow = date('Y-m-d H:i:s l ', $now);
		$stnow .= microtime(true);
		$msg = "[$stnow] $msg\n";
		echo $msg;
	}

	protected function shouldRunNow($now) {
		$runArray = explode('-', $this->runTime);
		$nowArray = explode('-', $now);
		$runFlag = true;
		foreach ($nowArray as $idx => $nowBit) {
			$runBit = $runArray[$idx];
			if ($runBit === '*' || $runBit === $nowBit) continue;
			$runFlag = false;
			break;
		}
		return $runFlag;
	}

	public function __worker() {
		if (self::$is_cli === null) return;
		if (!self::$is_cli) self::log('Running From Web. IP: ' . $_SERVER['REMOTE_ADDR']);

		$now = date(CronJob::DATE_FORMAT, time());

		$childPids = [];

		foreach(self::$workers as $worker) {
			$pid = pcntl_fork();
			if ($pid === -1) {
				self::log("cannot fork.");
			} else {
				if ($pid) {
					// successfully create a child process, the parent(>0) goes here
					$childPids []= $pid;
					continue;
				}
			}
			try {
				// the only main process(-1) or child process(0)
				$instance = new $worker();
				if ($instance && ((self::$is_cli && $instance->shouldRunNow($now)) || (!self::$is_cli && $instance->runOnWebForDebug))) {
					self::log("$worker start.");
					$instance->work();
					self::log("$worker end.");
				}
			} catch (Exception $e) {
				self::log("$worker Exception caught. Msg:");
				self::log($e->getMessage());
				self::log($e->getFile() . ' ' . $e->getLine());
			}
			if ($pid === 0) return; // child
		}

		foreach ($childPids as $pid) {
			pcntl_waitpid($pid, $status);
		}
	}
}