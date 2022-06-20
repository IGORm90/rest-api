<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($serial_numbers)
    {
        return [
            'message' => 'some serial number are incorrect',
            'uncreated_equipments' => $serial_numbers->equipments,
        ];
    }
}
