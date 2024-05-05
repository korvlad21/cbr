<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    protected Currency $model;

    public function __construct()
    {
        $this->model = new Currency();
    }
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all();
    }


    public function create(array $data)
    {
        $this->model->create($data);
    }

}
