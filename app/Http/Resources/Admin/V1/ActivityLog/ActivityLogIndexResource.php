<?php

namespace App\Http\Resources\Admin\V1\ActivityLog;

use App\Models\User;
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
        $model = explode("\\", $this->subject_type);

        $changes = $this->changes;

        if ($changes->has(['attributes', 'old'])) {
            $attributes = collect($changes['attributes']);
            $old = collect($changes['old']);

            $attributes = $attributes->reject(function ($value, $key) use ($old) {
                return $old[$key] === $value;
            });

            $old = $old->only($attributes->keys());

            $changes->forget(['attributes', 'old']);
            $changes['from'] = $old->toArray();
            $changes['to'] = $attributes->toArray();
        } else if ($changes->has('old')) {
            $changes = $changes['old'];
        } else if ($changes->has('attributes')) {
            $changes = $changes['attributes'];
        }

        return [
            'description' => $this->description,
            'model_name' => end($model),
            'model_id' => $this->subject_id,
            'changes' => json_encode($changes, JSON_PRETTY_PRINT),
            'user' => $this->causer->name,
            'created_at' => $this->created_at,
        ];
    }
}
