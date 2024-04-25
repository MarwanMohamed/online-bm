<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserVerify;
use App\Notifications\SendEmailVerificationNotification;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Enums\UserAction;
use YlsIdeas\FeatureFlags\Facades\Features;
use App\Enums\Feature;


class UserService extends BaseService
{
    protected $repository;
    protected $roleService;

    public function __construct(UserRepository $userRepository, RoleService $roleService)
    {
        $this->repository = $userRepository;
        $this->roleService = $roleService;
    }

    public function store(array $data)
    {
        $data['plain_password'] = $data['password'];
        $data['password'] = Hash::make($data['password']);

        $user = $this->repository->store($data);

        if (isset($data['role_id'])) {
            $role = $this->roleService->findOrFail($data['role_id']);
            $user->assignRole($role->name);
        }

        return $user;
    }

    public function update(int $id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->repository->update($id, $data);

        if (isset($data['role_id'])) {
            $user->roles()->sync($data['role_id'] ?? []);
        }

        return $user;
    }

    public function removeProfilePicture(User $user)
    {
        return $this->repository->removeProfilePicture($user);
    }

    public function findByEmail(string $email)
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function toggleActive($id): User
    {
        return $this->repository->toggleActive($id);
    }

    public function sendVerificationEmail($user)
    {
        $userVerify= UserVerify::create([
            'user_id' => $user->id,
            'expire_at' => Carbon::now()->addHours(24),
            'token' => Str::random(64),
            "action_type" => UserAction::EMAIL_VERIFICATION(),
        ]);

        $url= config('app.frontend-url') ."/email-verify?".http_build_query(['hash'=>$userVerify->token, "email"=>$user->email]);
        $user->notify(new SendEmailVerificationNotification($url));
    }

    public function deleteChangeEmailRequests($user)
    {
        $user->emailChangeRequests()->delete();
    }

    public function sendChangeEmailRequest($user, $newEmail, $referrer)
    {
        $this->deleteChangeEmailRequests($user);
        $userVerify = UserVerify::query()->updateOrCreate([
            'user_id' => $user->id,
            'draft_email' => $newEmail,
            'action_type' => UserAction::CHANGE_EMAIL_REQUEST->value,
        ], [
            'expire_at' => now()->addHours(24),
            'token' => Str::random(64),
        ]);

        $url = sprintf(
            '%s/email-verify?%s',
            trim($referrer, '/'),
            http_build_query(['hash' => $userVerify->token])
        );

        Notification::route('mail', [
            $newEmail => $user->name,
        ])->notify(
            new SendEmailVerificationNotification($url)
        );
        return $userVerify;
    }

    public function checkEmailVerification($user)
    {
        if(! $user->is_email_verified) {
            $user->is_email_verified = 1;
            $user->is_active = 1;
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        return $user;
    }

    public function handleChangeEmailRequest(UserVerify $userVerify): string
    {
        $user = $userVerify->user;

        $user->update([
            'email' => $userVerify->draft_email,
        ]);

        $userVerify->delete();

        return trans('messages.verification.email_changed');
    }

    /**
     * @param UserVerify $userVerify
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function verifyUserEmail(UserVerify $userVerify){

        $user = $userVerify->user;

        if(! $user->is_email_verified) {

            $this->checkEmailVerification($user);

            $userVerify->delete();

            Auth::login($user);

            return trans("messages.verification.email_verified");
        } else {

            $userVerify->delete();
            return trans("messages.verification.email_already_verified");
        }
    }
}

