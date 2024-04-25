<?php

namespace App\Repositories;

use App\Enums\Feature;
use App\Events\UserCreated;
use App\Exceptions\BadRequestException;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use YlsIdeas\FeatureFlags\Facades\Features;

class UserRepository extends BaseRepository
{
    protected $model = User::class;

    public function chainOnIndexQuery($query, $request = null)
    {
        if (request('name')) {
            $names = explode(',', request('name'));
            foreach ($names as $name) {
                $query->orWhere(function (Builder $builder) use ($name) {
                   $builder->orWhere('name', 'like', "%$name%");
                });
            }
        }

        if (request('selected_ids') && is_array(request('selected_ids'))) {
            $query->whereIn('id', request('selected_ids'));
        }

        if (! is_null(request('is_active'))) {
            $query->where('is_active', request()->boolean('is_active'));
        }

        return $query;
    }

    public function store(array $data = [])
    {
        $user = User::create($data);

        if (Features::accessible(Feature::ENFORCEMENT_CHANGE_PASSWORD->value)) {
            $user->forceFill(['is_initial_password_changed' => true])->save();
        }

        if (isset($data['profile_picture'])) {
            $user->addMediaFromRequest('profile_picture')->toMediaCollection('profile_picture');
        }

        return $user->refresh()->loadRelations();
    }

    public function update(int $id, array $data)
    {
        /** @var User $user */
        $user = $this->findOrFail($id);

        $user->update($data);

        if (
            $user->wasChanged('password')
            && $user->isNot(me())
            && Features::accessible(Feature::ENFORCEMENT_CHANGE_PASSWORD->value)
        ) {
            $user->forceFill(['is_initial_password_changed' => true])->save();
        }

        if (isset($data['profile_picture'])) {
            $user->addMediaFromRequest('profile_picture')->toMediaCollection('profile_picture');
        }

        return $user->refresh()->loadRelations();
    }

    public function removeProfilePicture(User $user)
    {
        $media = $user->getFirstMedia('profile_picture');
        if (!$media) {
            throw new BadRequestException(trans('messages.pictureNotFound'));
        }

        $media->delete();

        return $user->refresh()->loadRelations();
    }

    public function toggleActive($id): User
    {
        /** @var User $user */
        $user = User::query()->findOrFail($id);

        if ($user->is_active) {
            return $user->markAsInactive();
        }

        return $user->markAsActive();
    }
}
