<?php

namespace App\Exceptions;

class BadRequestException extends BaseException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function getType()
    {
        return ErrorTypes::BAD_REQUEST;
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
                'message' => $this->getMessage(),
                'details' => $this->getDetails()
            ])->setStatusCode($this->getStatusCode());
        }
    }
}
