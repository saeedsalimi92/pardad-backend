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
 *     schema="BlogIndexResource",
 *     title="Blog Index Resource",
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
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"published", "draft", "archived"},
 *         example="published"
 *     ),
 *     @OA\Property(
 *         property="status_translate",
 *         type="string",
 *         example="منتشر شده"
 *     ),
 *     @OA\Property(
 *         property="published_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
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
 *     ),
 *     @OA\Property(
 *         property="props",
 *         type="object",
 *         description="Dynamic properties object",
 *         @OA\Property(
 *             property="socials",
 *             type="object",
 *             example={
 *                 "instagram": "www.instagram.com",
 *                 "telegram": "www.telegram.com"
 *             }
 *         ),
 *         @OA\Property(
 *             property="metas",
 *             type="object",
 *             example={
 *                 "keyword": "sample keyword",
 *                 "canonical": "www.canonical.com"
 *             }
 *         )
 *     )
 * )
 */
class BlogIndexResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "end_title" => $this->en_title,
            "status" => $this->status,
            "props" => $this->props,
            "status_translate" => $this->status_translate,
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
