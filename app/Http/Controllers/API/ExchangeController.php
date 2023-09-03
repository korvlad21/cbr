<?php

namespace App\Http\Controllers\API;

use App\Helper\ExchangeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeDownloadRequest;
use App\Http\Requests\ExchangeGetRatesRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ExchangeResource;
use App\Jobs\GenerateExchangesJob;
use App\Models\Event;
use App\Repositories\ExchangeRepository;
use App\Repositories\ExchangeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ExchangeController extends Controller
{
    use DispatchesJobs;
    protected ExchangeRepositoryInterface $exchangeRepository;

    public function __construct(ExchangeRepositoryInterface $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }

    /**
     * @param ExchangeDownloadRequest $request
     * @return JsonResponse
     */
    public function download(ExchangeDownloadRequest $request):JsonResponse
    {
        $date = $request->input('date');

        Cache::delete('exchange_rates_'.$date);

        $exchangeHelper = new ExchangeHelper($this->exchangeRepository);

        $exchanges = $exchangeHelper->getExchangeOnDate($date);
        $exchangesBeforeTradeDay = $exchangeHelper->getExchangeOnDate(
            $date,
            true
        );

        return ($exchangeHelper->insert($exchanges, $exchangesBeforeTradeDay))
            ? response()->json(['success'=> true])
            : response()->json(['success'=> false, 'message' => 'Возникла ошибка']);
    }

    /**
     * @param ExchangeDownloadRequest $request
     * @return JsonResponse
     */
    public function downloadAllDays():JsonResponse
    {
        dispatch(new GenerateExchangesJob());
        return response()->json([
            'success' => true,
            'message' => 'Вы поставили в очередь задачу для загрузки всех курсов за последние 180 дней!',
        ]);
    }


    /**
     * @param ExchangeGetRatesRequest $request
     * @return JsonResponse
     */
    public function getRates(ExchangeGetRatesRequest $request):JsonResponse
    {
        $date = $request->input('date');
        $currency = $request->input('currency');

        $exchangeHelper = new ExchangeHelper($this->exchangeRepository);

        return response()->json([
            'exchangeRates'=> ExchangeResource::collection($exchangeHelper->getExchangeRates($date, $currency)),
        ]);
    }

}
