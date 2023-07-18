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

    public function update($id, array $data)
    {
        $exchange = $this->getByC($id);
        $exchange->update($data);
        return $exchange;
    }

    public function delete($id)
    {
        $exchange = $this->getById($id);
        return $exchange->delete();
    }

    public function updateOrCreate(array $data)
    {
        $this->model->updateOrCreate([
                'date' => $data['date'], 'charCode' => $data['charCode']
            ],
            $data
        );
    }
}
