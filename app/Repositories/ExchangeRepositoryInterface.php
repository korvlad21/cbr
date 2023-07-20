<?php

namespace App\Repositories;

interface ExchangeRepositoryInterface
{
    public function updateOrCreate(array $data);
    public function getOnDate(string $date);
}
