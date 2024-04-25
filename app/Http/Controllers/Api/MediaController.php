<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Exceptions\UnauthorizedException;

/**
 * @group Media
 * @authenticated
 */
class MediaController extends Controller
{

    public $service;

    /**
     * MediaController constructor.
     * @param $service
     */
    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }


    /**
     * Delete Media By Id
     *
     * @urlParam id int The ID of the media.
     * @response 403 {
     *     "type": "FORBIDDEN",
     *     "message": "User does not have the right permissions. Necessary permissions are edit_user"
        }
     *
     * @response 200 {
     *      "data": {
     *          "message": "The media has been deleted.",
     *      }
     *  }
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Media $media)
    {
        if (! $this->service->canDeleteMedia($media)) {
            throw UnauthorizedException::forPermissions(
                $this->service->getNecessaryPermissionForDelete($media)
            );
        }

        $media->delete();

        return response()->json([
            'message' => __('The media has been deleted.'),
        ]);
    }
}
