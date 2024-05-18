<?php

namespace App\Jobs;

use App\Helper\ExchangeHelper;

class GenerateExchangesJob extends AbstractExchangeJob
{
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $generateExchanges = $this->generateExhanges();
        $chains = array_merge($generateExchanges, [new FinishGenerateExchangeJob()]);
        StartGenerateExchangeJob::withChain($chains)->dispatch();
    }

    /**
     * @return array
     */
    private function generateExhanges() :array {
        $generateExhanges = [];
        for ($daysBefore = ExchangeHelper::COUNT_DAYS_JOBS - 1; $daysBefore >= 0; $daysBefore--) {
            $generateExhanges[] = new GenerateExchangeOneDayJob(
                date('Y-m-d', strtotime("-{$daysBefore} days"))
            );
        }
        return $generateExhanges;
    }
}
