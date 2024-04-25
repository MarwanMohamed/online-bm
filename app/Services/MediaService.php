<?php


namespace App\Services;


use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService extends BaseService
{
    protected $repository;

    public function canDeleteMedia(Media $media): bool
    {

        if ($media->model->is(me())) {
            return true;
        }

        if ($media->model instanceof User) {
            return me()->hasPermissionTo('edit_user');
        }

        return false;
    }

    public function getNecessaryPermissionForDelete(Media $media): array
    {
        if ($media->model instanceof User) {
            return ['edit_user'];
        }

        return ['Unknown'];
    }

}