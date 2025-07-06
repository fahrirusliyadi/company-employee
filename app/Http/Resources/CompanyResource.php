<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $logo = $this->getFirstMedia('logo');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'website' => $this->website,
            'logo' => $logo
                ? [
                    'url' => $logo->getUrl(),
                    '50x50' => $logo->getUrl('50x50'),
                ]
                : null,
        ];
    }
}
