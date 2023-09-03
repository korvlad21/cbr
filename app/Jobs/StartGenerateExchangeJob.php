<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StartGenerateExchangeJob extends AbstractExchangeJob
{
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->debug('Запуск брокура задач на 180 дней');
    }
}
