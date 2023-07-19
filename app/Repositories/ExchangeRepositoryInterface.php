<?php

namespace App\Repositories;

interface ExchangeRepositoryInterface
{
    public function getByСharCodeAndDate(string $charCode, string $date);
    public function create(array $data);
    public function updateOrCreate(array $data);
    public function getOnDate(string $date);
}
