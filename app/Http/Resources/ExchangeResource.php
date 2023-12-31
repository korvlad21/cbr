<?php

namespace App\Http\Resources;

use App\Helper\RoundHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'charCode' => $this['charCode'],
            'name' => $this['currency']['name'],
            'numCode' => $this['currency']['numCode'],
            'rate' => $this['rate'],
            'difference' => $this['difference'],
        ];
    }
}
