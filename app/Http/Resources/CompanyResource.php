<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'website' => $this->website,
            'logo' => $this->whenLoaded('media', function () {
                $logo = $this->getFirstMedia('logo');

                return $logo
                    ? [
                        'url' => $logo->getUrl(),
                        '50x50' => $logo->getAvailableUrl(['50x50']),
                        'autox140' => $logo->getAvailableUrl(['autox140']),
                    ]
                    : null;
            }),
        ];
    }
}
