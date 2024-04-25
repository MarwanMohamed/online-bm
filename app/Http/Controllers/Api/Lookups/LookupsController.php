<?php

namespace App\Http\Controllers\Api\Lookups;

use App\B5Digital\Helpers\LookupHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lookup\StoreLookupRequest;
use App\Http\Requests\Lookup\UpdateLookupRequest;
use App\Http\Resources\Lookup\LookupCategoryResource;
use App\Http\Resources\Lookup\LookupResource;
use App\Models\Lookup\Lookup;
use App\Services\LookupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Lookups
 * @authenticated
 */
class LookupsController extends Controller
{
    use \App\Traits\ResponseTrait;

    /**
     * Instance from lookup service.
     *
     * @var \App\Services\LookupService
     */
    protected LookupService $lookupService;

    /**
     * LookupsController constructor.
     *
     * @param  \App\Services\LookupService  $lookupService
     */
    public function __construct(LookupService $lookupService)
    {
        $this->lookupService = $lookupService;
    }

    /**
     * List Lookup Categories
     *
     * @apiResourceCollection  \App\Http\Resources\Lookup\LookupCategoryResource
     * @apiResourceModel \App\Models\Lookup\LookupCategory
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function listCategories(Request $request)
    {
        return LookupCategoryResource::collection(LookupHelper::getCategories());
    }

    /**
     * List Lookups For Datatable
     *
     * @queryParam category_code string filter by category code Example:customer_types
     * @apiResourceCollection  \App\Http\Resources\Lookup\LookupResource
     * @apiResourceModel \App\Models\Lookup\Lookup
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $dataTables = $this->lookupService->datatable($request);

        return $dataTables->setTransformer(function ($item) {
                return LookupResource::make($item)->resolve();
            })->toJson();
    }

    /**
     * List All Lookups
     *
     * @queryParam category_code string filter by category code Example:customer_types
     * @apiResourceCollection \App\Http\Resources\Lookup\LookupResource
     * @apiResourceModel \App\Models\Lookup\Lookup
     * @param  Request  $request
     * @return mixed
     */
    public function listAll(Request $request)
    {
        $lookups = $this->lookupService->listAll($request);

        return $this->respondWithSuccess(
            trans('messages.model.list', ['model' => 'Lookup']),
            LookupResource::collection($lookups)
        );
    }

    /**
     * Add Lookup
     *
     * @apiResource  \App\Http\Resources\Lookup\LookupResource
     * @apiResourceModel \App\Models\Lookup\Lookup
     * @apiResourceAdditional message="lookup have created successfully"
     * @header Content-Type multipart/form-data
     * @bodyParam category_id int required Example:1
     * @bodyParam name string required Example:Employee
     * @bodyParam code string required Example:employee
     * @bodyParam value string required Example:employee
     * @bodyParam is_active string required Example:1
     * @bodyParam is_system string required Example:0
     * @bodyParam model_type string required Example:App\Models\User\User
     * @param  StoreLookupRequest  $request
     * @return JsonResponse|string
     */
    public function store(StoreLookupRequest $request)
    {
        $validatedData = $request->validated();

        $lookup = $this->lookupService->store($validatedData);

        return $this->respondCreated(
            trans('messages.model.store', ['model' => 'Lookup']),
            new LookupResource($lookup)
        );
    }

    /**
     * Update Lookup
     *
     * @apiResource  \App\Http\Resources\Lookup\LookupResource
     * @apiResourceModel \App\Models\Lookup\Lookup
     * @apiResourceAdditional message="lookup have updated successfully"
     * @header Content-Type application/x-www-form-urlencoded
     * @urlParam id int required Example:1
     * @bodyParam category_id int required Example:1
     * @bodyParam name string required Example:Employee
     * @bodyParam code string required Example:employee
     * @bodyParam value string required Example:employee
     * @bodyParam is_active string required Example:1
     * @bodyParam is_system string required Example:0
     * @bodyParam model_type string required Example:App\Models\User\User
     * @param  UpdateLookupRequest  $request
     * @param $id
     * @throws \Illuminate\Validation\ValidationException
     * @return mixed
     */
    public function update(UpdateLookupRequest $request, $id)
    {
        $validatedData = $request->validated();
        $lookup = $this->lookupService->update($id, $validatedData);

        return $this->respondWithSuccess(
            trans('messages.model.update', ['model' => 'Lookup']),
            new LookupResource($lookup)
        );
    }

    /**
     * Show Lookup
     *
     * @apiResource  \App\Http\Resources\Lookup\LookupResource
     * @apiResourceModel \App\Models\Lookup\Lookup
     * @urlParam id int required Example:1
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $lookup = $this->lookupService->findOrFail($id);

        return $this->respondWithSuccess(
            trans('messages.model.retrieve', ['model' => 'Lookup']),
            new LookupResource($lookup)
        );
    }
}
