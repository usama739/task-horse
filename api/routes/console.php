<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    Artisan::comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
