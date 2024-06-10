<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('monitor:check-uptime')->onOneServer()->everyMinute();
Schedule::command('monitor:check-certificate')->onOneServer()->everyMinute();
