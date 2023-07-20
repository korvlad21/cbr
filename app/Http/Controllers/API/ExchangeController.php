<?php

namespace App\Http\Controllers\API;

use App\Helper\ExchangeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ExchangeResource;
use App\Models\Event;
use App\Repositories\ExchangeRepository;
use App\Repositories\ExchangeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ExchangeController extends Controller
{

    protected ExchangeRepositoryInterface $exchangeRepository;

    public function __construct(ExchangeRepositoryInterface $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=> false, 'errors' => $validator->errors()]);
        }

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
    public function getRates(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'currency' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=> false, 'errors' => $validator->errors()]);
        }

        $date = $request->input('date');
        $currency = $request->input('currency');

        $exchangeHelper = new ExchangeHelper($this->exchangeRepository);

        return response()->json([
            'exchangeRates'=> ExchangeResource::collection($exchangeHelper->getExchangeRates($date, $currency)),
        ]);
    }

}
