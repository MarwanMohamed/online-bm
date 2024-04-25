<?php


namespace App\Services\SocialProviders;


use App\Contracts\ISocialProvider;
use App\Enums\SocialProvider as SocialProviderEnum;
use App\Exceptions\ProviderAuthorizationException;
use App\Services\UserService;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\User as SocialUser;

class GoogleProvider implements ISocialProvider
{

    protected $userService;
    public function __construct(UserService $userService)
    {
        Socialite::driver('google')->stateless();
        $this->userService= $userService;
    }

    public function getAuthRedirectUrl(): string
    {
        $redirectUrl = config('services.google.redirect');


        $redirectResponse = Socialite::driver('google')
            ->redirectUrl($redirectUrl)
            ->redirect();

        return $redirectResponse->getTargetUrl();
    }

    public function getUser($redirectUrl): SocialUser
    {
        try {
            return Socialite::driver('google')->with(['access_type' => 'offline'])->stateless()->user();
        } catch (ClientException $e) {
            $exceptionBody = json_decode((string) $e->getResponse()->getBody());
            $exceptionMessage = $exceptionBody->error . ': ' . $exceptionBody->error_description;
            throw new ProviderAuthorizationException(SocialProviderEnum::GOOGLE, $exceptionMessage);
        }
    }

    public function handleLoginCallback(User $googleUser)
    {
        try {

            DB::beginTransaction();

            if (!$dbUser=$this->userService->findByEmail($googleUser->getEmail())){
                $dbUser= $this->userService->store([
                    "password"=>randomPassword(10),
                    "email"=>$googleUser->getEmail(),
                    "name"=>$googleUser->getName(),
                ]);
            }

            // Check If User Verified
            $dbUser= $this->userService->checkEmailVerification($dbUser);

            if (!$dbUser->socialAccounts()->where('provider_name',SocialProviderEnum::GOOGLE())->exists()){
                $dbUser->socialAccounts()->create([
                    "user_provider_id"=>$googleUser->getId(),
                    "provider_name"=>SocialProviderEnum::GOOGLE(),
                ]);
            }

            DB::commit();

            return $dbUser;
        }catch (\Exception $exception){
            throw  new ProviderAuthorizationException(SocialProviderEnum::GOOGLE, $exception->getMessage());
        }
    }
}