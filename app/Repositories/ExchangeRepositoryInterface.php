<?php

namespace App\Repositories;

interface ExchangeRepositoryInterface
{
    public function getByСharCodeAndDate(string $charCode, string $date);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
    public function updateOrCreate(array $data);
}
