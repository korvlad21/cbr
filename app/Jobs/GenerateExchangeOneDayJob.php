<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class GenerateExchangeOneDayJob extends AbstractExchangeJob
{
    protected string $date;
    /**
     * Create a new job instance.
     */
    public function __construct(string $date)
    {
        parent::__construct();
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->debug('Дата - '. $this->date);
    }
}
