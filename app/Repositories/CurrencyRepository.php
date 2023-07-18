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

    public function getByCharCode($charCode)
    {
        return $this->model->find($charCode);
    }
    public function getAll()
    {
        return $this->model->all();
    }


    public function create(array $data)
    {
        $this->model->create($data);
    }

    public function update($charCode, array $data)
    {
        $currency = $this->getByCharCode($charCode);
        $currency->update($data);
        return $currency;
    }

    public function delete($charCode)
    {
        $currency = $this->getByCharCode($charCode);
        return $currency->delete();
    }
}
