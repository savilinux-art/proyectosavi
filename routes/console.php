<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ---------------------------------------------------------------------------
// Scheduler — FASE K (Recordatorios)
// ---------------------------------------------------------------------------

// Procesa los recordatorios pendientes cada minuto.
Schedule::command('recordatorios:procesar')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
