<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string $en_title
 * @property string $status
 * @property string $status_translate
 * @property string $published_at
 * @property string $created_at
 * @property string $updated_at
 * @property array $props
 * @OA\Schema(
 *     schema="PaginationResource",
 *     title="Pagination Resource",
 *     description="Pagination resource representation",
 *     type="object",
 *     @OA\Property(
 *         property="current_page",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="per_page",
 *         type="integer",
 *         example="10"
 *     ),
 *     @OA\Property(
 *         property="total",
 *         type="integer",
 *         example="1000"
 *     ),
 *     @OA\Property(
 *         property="last_page",
 *         type="integer",
 *         example="100"
 *     ),
 * )
 */
class PaginationResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),
            'last_page' => $this->lastPage(),
        ];
    }
}
