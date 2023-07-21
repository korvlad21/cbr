<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public const CHARCODE_RUB = 'RUB';
    public const NAME_RUB = 'Российский рубль';
    public const NUMCODE_RUB = 643;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'charCode',
        'numCode',
        'name',
    ];
}
