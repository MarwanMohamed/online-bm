<?php

namespace App\Exceptions;

use App\Enums\SocialProvider;

class ProviderAuthorizationException extends BaseException
{
    private $provider;

    public function __construct(SocialProvider $provider, string $message, int $code = null)
    {
        parent::__construct($message, $code);

        $this->provider = $provider;
    }

    public function getType()
    {
        return ErrorTypes::PROVIDER_UNAUTHORIZED;
    }

    public function getStatusCode()
    {
        $type = $this->getType();
        return ErrorTypes::status($type);
    }

    public function getDetails(): array
    {
        return [
            'provider' => [
                'id' => $this->provider->value,
                'name' => $this->provider->name,
            ],
            'errorCode' => $this->getCode()
        ];
    }

    public function render($request)
    {
        if ($request->wantsJson()) {
            return response([
                'type' => $this->getType(),
                'message' => $this->getMessage(),
                'details' => $this->getDetails()
            ])->setStatusCode($this->getStatusCode());
        }
    }
}
