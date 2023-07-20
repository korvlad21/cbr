<?php

namespace App\Http\Controllers\API;

use App\Helper\CurrencyHelper;
use App\Helper\ExchangeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Repositories\CurrencyRepositoryInterface;
use App\Repositories\ExchangeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    protected CurrencyRepositoryInterface $currencyRepository;

    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @return JsonResponse
     */
    public function get():JsonResponse
    {
        $currencyOptions = $this->currencyRepository->getAll();

        return response()->json([
            'currencyOptions'=> CurrencyResource::collection($currencyOptions),
        ]);
    }
}
