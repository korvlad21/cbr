<?php

namespace App\Repositories;

use App\Models\Exchange;

class ExchangeRepository implements ExchangeRepositoryInterface
{
    protected $model;

    public function __construct(Exchange $model)
    {
        $this->model = $model;
    }


    public function getByСharCodeAndDate(string $date, string $charCode)
    {
        return $this->model->where([['date' => $date],['charCode' => $charCode]]);
    }

    public function create(array $data)
    {
        $this->model->created($data);
    }

    public function updateOrCreate(array $data)
    {
        $this->model->updateOrCreate([
                'date' => $data['date'], 'charCode' => $data['charCode']
            ],
            $data
        );
    }

    public function getOnDate(string $date)
    {
        return $this->model->with(['currency'])->where('date', $date)->get();
    }
}
