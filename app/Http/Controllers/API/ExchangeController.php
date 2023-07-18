<?php

namespace App\Http\Controllers\API;

use App\Helper\ExchangeHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Repositories\ExchangeRepository;
use App\Repositories\ExchangeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        } else {

            $date = $request->input('date');

        }

        $exchangeHelper = new ExchangeHelper($this->exchangeRepository);

        return ($exchangeHelper->getExchangeOnDate($date))
            ? response()->json(['success'=> true])
            : response()->json(['success'=> false, 'message' => 'Возникла ошибка']);
    }
}
