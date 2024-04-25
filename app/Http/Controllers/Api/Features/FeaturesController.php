<?php

namespace App\Http\Controllers\Api\Features;

use App\Http\Controllers\Controller;
use App\Http\Resources\Features\FeatureResource;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Features
 * @authenticated
 */
class FeaturesController extends Controller
{
    use ResponseTrait;

    /**
     * List System Features
     *
     *
     * @response {
    "message": "Features listed successfully",
    "data": [
    {
    "id": 1,
    "title": "AUTO_LOGIN_AFTER_RESET_PASSWORD",
    "feature": "AUTO_LOGIN_AFTER_RESET_PASSWORD",
    "description": null,
    "active_at": null,
    "accessible": true
    }
    ]
    }
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAll(Request $request)
    {
        $table = config("features.gateways.database.table");
        $features = DB::table($table)->get();
        return $this->respondWithSuccess(
            trans('messages.model.list', ['model' => 'Features']),
            FeatureResource::collection($features)
        );
    }
}
