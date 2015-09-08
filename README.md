# phpGCrons
A simple cronjobs framework for php code

# install

copy /cronjobs to /PATH_TO_YOUR_WWWROOT/
```
$ crontab -e
```
Add a line
```
* * * * * /usr/bin/php /PATH_TO_YOUR_WWWROOT/cronjobs/init.php >> /PATH_TO_YOUR_LOG/cronjob.log 2>&1
```

# usage

Write a new class extends CronJob(or WeeklyCronJob, MonthlyCronJob...) in /cronjobs
Add your class name in CronJob::$workers
Override work() function, write your cron scripts
(optional) Override $runTime (see "format of $runTime" below)
(optional) If you also want to run your work() function when sending HTTP requests at /cronjobs/init.php, override $runOnWebForDebug = true

# format of $runTime
```
const DATE_FORMAT = 'Y-m-d-H-i-N';
$now = date(CronJob::DATE_FORMAT, time());
```
And `*` is supported.
The method `work()` will be called when `$now` matches the pattern `$runTime` defined in the class.

### Examples
Run at 04:00 every day (daily):
```public $runTime = '*-*-*-04-00-*';```

Run at 01:00 on every Monday (weekly):
```public $runTime = '*-*-*-01-00-1';```

Run at 01:00 on every minute:
```public $runTime = '*-*-*-*-*-*';```

Run at 02:00 on 1st day of every month (monthly):
```public $runTime = '*-*-01-02-00-*'```

Never run as cronjob:
```public $runTime = '9999-99-99-99-99-99';```