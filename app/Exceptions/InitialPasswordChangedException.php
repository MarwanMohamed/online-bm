<?php

namespace App\Exceptions;

class InitialPasswordChangedException extends BaseException
{

    public function __construct($message = null)
    {
        parent::__construct($message);
    }

    public function getType()
    {
        return ErrorTypes::INITIAL_PASSWORD_CHANGED;
    }

    public function getDetails(): array
    {
        return [
            'errorCode' => ErrorTypes::INITIAL_PASSWORD_CHANGED
        ];
    }

    public function getStatusCode()
    {
        $type = $this->getType();
        return ErrorTypes::status($type);
    }

    public function render($request)
    {
        if ($request->wantsJson()) {
            return response([
                'type' => $this->getType(),
                'message' => __('errors.'.ErrorTypes::INITIAL_PASSWORD_CHANGED),
            ])->setStatusCode($this->getStatusCode());
        }
    }
}
