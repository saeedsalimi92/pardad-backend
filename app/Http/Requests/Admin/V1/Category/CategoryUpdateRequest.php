<?php

namespace App\Http\Requests\Admin\V1\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CategoryUpdateRequest",
 *     required={"contents", "image", "status"},
 *     @OA\Property(
 *         property="contents",
 *         type="object",
 *         @OA\Property(
 *             property="fa",
 *             type="object",
 *             required={"title", "content"},
 *             @OA\Property(property="title", type="string", maxLength=255, example="عنوان مقاله"),
 *             @OA\Property(property="content", type="string", example="<p>محتوای دسته بندی به فارسی</p>"),
 *         ),
 *         @OA\Property(
 *             property="en",
 *             type="object",
 *             required={"title", "content"},
 *             @OA\Property(property="title", type="string", maxLength=255, example="Blog Title"),
 *             @OA\Property(property="content", type="string", example="<p>English category content</p>"),
 *         )
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"ACTIVE", "INACTIVE"},
 *         example="INACTIVE"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Featured image file (max: 500kb)"
 *     ),
 *    @OA\Property(
 *          property="props",
 *          type="object",
 *          @OA\Property(
 *              property="fa",
 *              type="object",
 *              @OA\Property(property="meta_keyword", type="string", maxLength=255, example="عنوان مقاله"),
 *              @OA\Property(property="meta_description", type="string", example="محتوای مقاله به فارسی"),
 *              @OA\Property(property="canonical", type="string", example="http://www.category.pardad.com", nullable=true)
 *          ),
 *          @OA\Property(
 *              property="en",
 *              type="object",
 *              @OA\Property(property="title", type="string", maxLength=255, example="Blog Title"),
 *              @OA\Property(property="content", type="string", example="<p>English blog content</p>"),
 *              @OA\Property(property="description", type="string", example="http://www.category.pardad.com", nullable=true)
 *          )
 *      )
 *     )
 * )
 */
class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
