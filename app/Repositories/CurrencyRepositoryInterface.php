<?php

namespace App\Repositories;

interface CurrencyRepositoryInterface
{
    public function getByCharCode($charCode);
    public function getAll();
    public function create(array $data);

    public function update($charCode, array $data);

    public function delete($charCode);
}
