<?php

namespace App\Http\Controllers\API;

use App\Helper\CurrencyHelper;
use App\Helper\ExchangeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Repositories\CurrencyRepository;
use App\Repositories\CurrencyRepositoryInterface;
use App\Repositories\ExchangeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get():JsonResponse
    {
        $currencyHelper = new CurrencyHelper(new CurrencyRepository());

        $currencyOptions = $currencyHelper->getAll();

        return response()->json([
            'currencyOptions'=> CurrencyResource::collection($currencyOptions),
        ]);
    }
}
