<?php

namespace App\Repositories;

interface CurrencyRepositoryInterface
{
    public function getAll();
    public function create(array $data);

}
