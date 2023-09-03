<?php

namespace App\Jobs;

use App\Helper\ExchangeHelper;
use App\Repositories\ExchangeRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateExchangeOneDayJob extends AbstractExchangeJob
{
    protected string $date;
    protected ExchangeRepositoryInterface $exchangeRepository;
    protected ExchangeHelper $exchangeHelper;

    /**
     * Create a new job instance.
     */
    public function __construct(string $date)
    {
        parent::__construct();
        $this->date = $date;
        $this->exchangeRepository = app(ExchangeRepositoryInterface::class);
        $this->exchangeHelper = new ExchangeHelper();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = $this->date;
        $exchanges = $this->exchangeHelper->getExchangeOnDate($date);

        Cache::delete('exchange_'.$this->date);
        Cache::put('exchange_'.$this->date, $exchanges, 60*60*24);

        $exchangesBeforeTradeDay = (Cache::get('exchange_'.$date))
            ? Cache::get('exchange_'.$date)
            : $this->exchangeHelper->getExchangeOnDate(
            $date,
            true
        );
        $this->exchangeHelper->insert($exchanges, $exchangesBeforeTradeDay);
        $this->debug('Дата - '. $this->date);
    }
}
