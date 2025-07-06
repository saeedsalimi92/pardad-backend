<?php

namespace App\Http\Resources\Client\V1\Brand;

use App\Models\BrandContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property BrandContent $contents
 * @property mixed $code
 * @OA\Schema(
 *     schema="ApiV1BrandListResource",
 *     title="Brand Resource",
 *     description="Brand resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="contents",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/BrandContentIndexResource")
 *     ),
 * )
 */
class BrandListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->code,
            "title" => $this->contents?->first()->title ?? "",
            "slug" => $this->contents?->first()->slug ?? "",
        ];
    }
}
