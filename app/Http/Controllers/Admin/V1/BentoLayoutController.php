<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutIndexRequest;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutShowRequest;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutStoreRequest;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutUpdateRequest;
use App\Services\BentoLayoutService;
use Illuminate\Http\Request;

class BentoLayoutController extends Controller
{
    public function __construct(private BentoLayoutService $bentoLayoutService)
    {
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/bento-layouts",
     *     summary="List all bento layouts",
     *     description="Returns a list of all available bento layouts",
     *     operationId="adminBentoLayoutIndex",
     *     tags={"BentoLayout"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *         )
     *     )
     * )
     */
    public function index(BentoLayoutIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $bentoLayouts = $this->bentoLayoutService->index();

        return $this->apiResponse(data: $bentoLayouts);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/bento-layouts",
     *     summary="Create a new bento layout",
     *     description="Stores a new bento layout with given parameters",
     *     operationId="adminBentoLayoutStore",
     *     tags={"BentoLayout"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"param"},
     *             @OA\Property(property="param", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bento layout created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function store(BentoLayoutStoreRequest $request)
    {
        $data = $request->validated();

        $bento = $this->bentoLayoutService->create($data);

        return $this->apiResponse(data: $bento);
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/bento-layouts/{id}",
     *     summary="Get a specific bento layout",
     *     description="Returns the bento layout by ID",
     *     operationId="adminBentoLayoutShow",
     *     tags={"BentoLayout"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the bento layout",
     *         @OA\Schema(type="string", example="abc123")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bento layout retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function show(BentoLayoutShowRequest $request, string $id)
    {
        $bento = $this->bentoLayoutService->findById($id);

        return $this->apiResponse(data: $bento);
    }

    /**
     * @OA\Put(
     *     path="/admin/v1/bento-layouts/{id}",
     *     summary="Update a bento layout",
     *     description="Updates an existing bento layout by ID",
     *     operationId="adminBentoLayoutUpdate",
     *     tags={"BentoLayout"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the bento layout to update",
     *         @OA\Schema(type="string", example="abc123")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"param"},
     *             @OA\Property(property="param", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bento layout updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="")
     *         )
     *     )
     * )
     */
    public function update(BentoLayoutUpdateRequest $request, string $id)
    {
        $this->bentoLayoutService->update($id, $request->validated());

        return $this->apiResponse(message: __("response.bento_layout.update"));
    }

    /**
     * @OA\Delete(
     *     path="/admin/v1/bento-layouts/{id}",
     *     summary="Delete a bento layout",
     *     description="Deletes a bento layout by ID",
     *     operationId="adminBentoLayoutDestroy",
     *     tags={"BentoLayout"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the bento layout to delete",
     *         @OA\Schema(type="string", example="abc123")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bento layout deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $this->bentoLayoutService->destroy($id);

        return $this->apiResponse(message: __("response.bento_layout.destroy"));
    }
}
