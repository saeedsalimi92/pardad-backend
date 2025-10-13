<?php

namespace App\Http\Resources\Admin\V1\Redirect;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="AdminV1RedirectIndexResource",
 *     title="Redirect Resource",
 *     description="Redirect resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *
 *
 *     @OA\Property(
 *            property="name",
 *            type="string",
 *            example="brand name"
 *       ),
 *     @OA\Property(
 *            property="priority",
 *            type="string",
 *            example="brand priority"
 *       ),
 *     @OA\Property(
 *            property="file",
 *            type="string",
 *            example="brand file"
 *       ),
 *     @OA\Property(
 *            property="action",
 *            type="string",
 *            example="brand action"
 *       ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Brand status code",
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         description="Translated status of brand",
 *         example="فعال"
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     )
 * )
 */
class RedirectIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "destination_url" => $this->destination_url,
            "source_url" => $this->source_url,
            "status_code" => $this->status_code,
            "status" => $this->status,
            "status_translate" => $this->status_translate,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}


