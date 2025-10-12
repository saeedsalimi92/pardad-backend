<?php

namespace App\Http\Resources\Admin\V1\ActivityLog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AdminV1ActvityLogIndexResource",
 *     title="Category Resource",
 *     description="Category resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *    @OA\Property(
 *          property="parent_id",
 *          type="integer",
 *          example=1
 *      ),
 *     @OA\Property(
 *           property="parent_name",
 *           type="string",
 *           example="Main category title"
 *       ),
 *     @OA\Property(
 *            property="title",
 *            type="string",
 *            example="category title"
 *       ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Category status code",
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         description="Translated status of category",
 *         example="فعال"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Category type code",
 *         example="expert"
 *     ),
 *     @OA\Property(
 *         property="type_translate",
 *         type="string",
 *         description="Translated type of category",
 *         example="حرفه ای"
 *     ),
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

class ActivityLogIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this;
    }
}
