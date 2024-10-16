<?php

use App\Console\Commands\PurgeDeletedTasks;
use App\Console\Commands\SendDateNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(PurgeDeletedTasks::class)->daily();
Schedule::command(SendDateNotifications::class)->daily();
