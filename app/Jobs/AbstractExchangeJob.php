<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class AbstractExchangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('generate-exchanges');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->debug('done');
    }

    /**
     * @param string $msg
     * @return void
     */
    protected function debug(string $msg): void {
        $class = static::class;
        $msg = $msg. "[{$class}]";
        Log:info($msg);
    }
}
