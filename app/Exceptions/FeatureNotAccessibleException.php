<?php

namespace App\Exceptions;

use App\Enums\Feature;

class FeatureNotAccessibleException extends BaseException
{
    private $feature;

    public function __construct(Feature $feature, string $message = "", int $code = null)
    {
        parent::__construct($this->message);

        $this->setMessage($message != "" ? $message : trans("errors.features.not-accessible", ['feature' => $feature->value]));
        $this->feature = $feature;
        if (!is_null($code)) {
            $this->code = $code;
        }

    }

    public function getType()
    {
        return ErrorTypes::FEATURE_NOT_ACCESSIBLE;
    }

    public function getStatusCode()
    {
        if ($this->code != 0)
            return $this->code;

        $type = $this->getType();
        return ErrorTypes::status($type);
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function getDetails(): array
    {
        return [
            'feature' => [
                'id' => $this->feature->value,
                'name' => $this->feature->name,
            ],
            'errorCode' => ErrorTypes::FEATURE_NOT_ACCESSIBLE
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
