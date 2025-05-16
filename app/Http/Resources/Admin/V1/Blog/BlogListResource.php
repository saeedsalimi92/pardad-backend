<?php

namespace App\Http\Resources\Admin\V1\Blog;

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
 *     schema="BlogListResource",
 *     title="Blog List Resource",
 *     description="Blog resource representation",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Sample Blog Title"
 *     ),
 *     @OA\Property(
 *         property="end_title",
 *         type="string",
 *         example="Sample English Title"
 *     ),
 * )
 */
class BlogListResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            "end_title" => $this->en_title
        ];
    }
}
