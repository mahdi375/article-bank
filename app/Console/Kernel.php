<?php

namespace App\Console;

use App\Enums\ArticleDataSource;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $this->sheduleFetchArticlesCmd($schedule);
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    private function sheduleFetchArticlesCmd(Schedule $schedule): void
    {
        $schedule->command('app:fetch-articles', [ArticleDataSource::GUARDIAN->value])->dailyAt('00:00');
        $schedule->command('app:fetch-articles', [ArticleDataSource::NEWS_API->value])->dailyAt('01:00');
        $schedule->command('app:fetch-articles', [ArticleDataSource::NEW_YORK_TIMES->value])->dailyAt('02:00');
    }
}
