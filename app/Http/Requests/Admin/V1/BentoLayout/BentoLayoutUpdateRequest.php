<?php

namespace App\Http\Requests\Admin\V1\BentoLayout;

use App\Models\BentoLayout;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BentoLayoutUpdateRequest",
 *     required={"param"},
 *     @OA\Property(
 *         property="param",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"i", "x", "y", "w", "h", "component"},
 *             @OA\Property(property="i", type="string", example="0"),
 *             @OA\Property(property="x", type="integer", example=8),
 *             @OA\Property(property="y", type="integer", example=1),
 *             @OA\Property(property="w", type="integer", example=2),
 *             @OA\Property(property="h", type="integer", example=4),
 *             @OA\Property(property="component", type="string", example="Panel1"),
 *             @OA\Property(property="noResize", type="boolean", example=false),
 *             @OA\Property(property="noMove", type="boolean", example=false),
 *             @OA\Property(property="dashboard", type="string", example="user"),
 *             @OA\Property(property="showMore", type="boolean", example=false),
 *             @OA\Property(property="hasTitle", type="boolean", example=false),
 *             @OA\Property(property="hasScreenshot", type="boolean", example=false),
 *             @OA\Property(property="title", type="string", example="")
 *         ),
 *         example={
 *             {
 *                 "i": "0",
 *                 "x": 8,
 *                 "y": 1,
 *                 "w": 2,
 *                 "h": 4,
 *                 "component": "Panel1",
 *                 "noResize": false,
 *                 "noMove": false,
 *                 "dashboard": "user",
 *                 "showMore": false,
 *                 "hasTitle": false,
 *                 "hasScreenshot": false,
 *                 "title": ""
 *             }
 *         }
 *     )
 * )
 */
class BentoLayoutUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->user()->can('update', BentoLayout::class)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "param" => ['required', 'array'],
        ];
    }
}
