<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HoldingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'symbol' => $this->symbol,
            'name' => $this->name,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'user' => new UserResource($this->whenLoaded('user')),
            'average_price' => $this->average_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
