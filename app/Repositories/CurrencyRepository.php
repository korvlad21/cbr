<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    protected Currency $model;

    public function __construct(Currency $model)
    {
        $this->model = $model;
    }
    public function getAll()
    {
        return $this->model->all();
    }


    public function create(array $data)
    {
        $this->model->create($data);
    }

}
