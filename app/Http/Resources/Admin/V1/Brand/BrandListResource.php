<?php

namespace App\Http\Resources\Admin\V1\Brand;

use App\Models\BrandContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property BrandContent $contents
 * @OA\Schema(
 *     schema="AdminV1BrandListResource",
 *     title="Brand Resource",
 *     description="Brand resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *          property="title",
 *          type="string",
 *          example="Sample Title"
 *      ),
 * )
 */
class BrandListResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->contents?->first()->title ?? "",
        ];
    }
}
