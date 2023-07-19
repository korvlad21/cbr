<?php

namespace App\Http\Resources;

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
            'name' => $this['name'],
            'numCode' => $this['numCode'],
            'rate' => round($this['rate'] / $this['nominal'], 4),
            'difference' => round($this['difference'],4)
        ];
    }
}
