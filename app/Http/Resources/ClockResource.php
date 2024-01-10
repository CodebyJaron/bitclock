<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClockResource extends JsonResource
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
            'student_id' => $this->student_id,
            'excercise' => $this->excercise,
            'date' => $this->date,
            'inclock_time' => $this->inclock_time,
            'outclock_time' => $this->outclock_time,
            'status' => $this->status,
            'note' => $this->note,
        ];
    }
}
