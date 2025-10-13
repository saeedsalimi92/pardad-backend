<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutIndexRequest;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutShowRequest;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutStoreRequest;
use App\Http\Requests\Admin\V1\BentoLayout\BentoLayoutUpdateRequest;
use App\Http\Resources\Admin\V1\BentoLayout\BentoLayoutIndexResource;
use App\Services\BentoLayoutService;

class BentoLayoutController extends Controller
{
    public function __construct(private readonly BentoLayoutService $bentoLayoutService)
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
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example=""
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(
     *                         property="param",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="i", type="string", example="0"),
     *                             @OA\Property(property="x", type="integer", example=8),
     *                             @OA\Property(property="y", type="integer", example=1),
     *                             @OA\Property(property="w", type="integer", example=2),
     *                             @OA\Property(property="h", type="integer", example=4),
     *                             @OA\Property(property="component", type="string", example="Panel1"),
     *                             @OA\Property(property="noResize", type="boolean", example=false),
     *                             @OA\Property(property="noMove", type="boolean", example=false),
     *                             @OA\Property(property="dashboard", type="string", example="user"),
     *                             @OA\Property(property="showMore", type="boolean", example=false),
     *                             @OA\Property(property="hasTitle", type="boolean", example=false),
     *                             @OA\Property(property="hasScreenshot", type="boolean", example=false),
     *                             @OA\Property(property="title", type="string", nullable=true, example=null)
     *                         )
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-06T18:01:24.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-06T18:01:24.000000Z"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="pagination_info",
     *                 ref="#/components/schemas/PaginationResource"
     *             )
     *         )
     *     )
     * )
     */
    public function index(BentoLayoutIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $bentoLayouts = $this->bentoLayoutService->index();

        $collection = BentoLayoutIndexResource::collection($bentoLayouts);
        return $this->apiResponse(data: $collection);
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
     *         description="Category data and featured image",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/BentoLayoutStoreRequest")
     *         ),
     *     ),
     *          @OA\Response(
     *          response=200,
     *          description="Bento layout retrieved",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example=""
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(
     *                      property="param",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="i", type="string", example="0"),
     *                          @OA\Property(property="x", type="integer", example=8),
     *                          @OA\Property(property="y", type="integer", example=1),
     *                          @OA\Property(property="w", type="integer", example=2),
     *                          @OA\Property(property="h", type="integer", example=4),
     *                          @OA\Property(property="component", type="string", example="Panel1"),
     *                          @OA\Property(property="noResize", type="boolean", example=false),
     *                          @OA\Property(property="noMove", type="boolean", example=false),
     *                          @OA\Property(property="dashboard", type="string", example="user"),
     *                          @OA\Property(property="showMore", type="boolean", example=false),
     *                          @OA\Property(property="hasTitle", type="boolean", example=false),
     *                          @OA\Property(property="hasScreenshot", type="boolean", example=false),
     *                          @OA\Property(property="title", type="string", nullable=true, example=null)
     *                      )
     *                  ),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-06T18:01:24.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-06T18:01:24.000000Z"),
     *                  @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *              ),
     *              @OA\Property(
     *                  property="pagination_info",
     *                  ref="#/components/schemas/PaginationResource"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Bento layout not found"
     *      )
     *  )
     */
    public function store(BentoLayoutStoreRequest $request): \Illuminate\Http\JsonResponse
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
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bento layout retrieved",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example=""
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(
     *                     property="param",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="i", type="string", example="0"),
     *                         @OA\Property(property="x", type="integer", example=8),
     *                         @OA\Property(property="y", type="integer", example=1),
     *                         @OA\Property(property="w", type="integer", example=2),
     *                         @OA\Property(property="h", type="integer", example=4),
     *                         @OA\Property(property="component", type="string", example="Panel1"),
     *                         @OA\Property(property="noResize", type="boolean", example=false),
     *                         @OA\Property(property="noMove", type="boolean", example=false),
     *                         @OA\Property(property="dashboard", type="string", example="user"),
     *                         @OA\Property(property="showMore", type="boolean", example=false),
     *                         @OA\Property(property="hasTitle", type="boolean", example=false),
     *                         @OA\Property(property="hasScreenshot", type="boolean", example=false),
     *                         @OA\Property(property="title", type="string", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-06T18:01:24.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-06T18:01:24.000000Z"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *             ),
     *             @OA\Property(
     *                 property="pagination_info",
     *                 ref="#/components/schemas/PaginationResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bento layout not found"
     *     )
     * )
     */
    public function show(BentoLayoutShowRequest $request, string $id): \Illuminate\Http\JsonResponse
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
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data and featured image",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/BentoLayoutUpdateRequest")
     *         ),
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
    public function update(BentoLayoutUpdateRequest $request, string $id): \Illuminate\Http\JsonResponse
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
     *         @OA\Schema(type="string", example="1")
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
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        $this->bentoLayoutService->destroy($id);

        return $this->apiResponse(message: __("response.bento_layout.destroy"));
    }
}
