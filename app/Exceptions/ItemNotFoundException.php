<?php

namespace App\Exceptions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ItemNotFoundException extends BaseException
{
    private $ids;

    private $model;

    public function __construct(string $class, array $ids = [])
    {
        parent::__construct(__CLASS__);

        $this->ids = Arr::wrap($ids);

        $path = explode('\\', $class);
        $this->model = Str::lower(end($path));

        $this->message = $this->getCustomMessage();
    }

    public function getType()
    {
        return ErrorTypes::ITEM_NOT_FOUND;
    }

    public function getStatusCode()
    {
        $type = $this->getType();
        return ErrorTypes::status($type);
    }

    public function getCustomMessage(): string
    {
        return ErrorTypes::message(ErrorTypes::ITEM_NOT_FOUND, [
            'model' => $this->model,
            'id' => implode(',', $this->ids),
        ]);
    }

    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'model' => $this->model,
        ];
    }

    public function render($request)
    {
        if ($request->wantsJson()) {
            return response([
                'type' => $this->getType(),
                'message' => $this->getCustomMessage(),
            ])->setStatusCode($this->getStatusCode());
        }
    }
}
