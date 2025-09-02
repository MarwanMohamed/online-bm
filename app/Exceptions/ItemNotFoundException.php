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
            'id' => implode(',', $this->ids),
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
        
        // Handle web requests (Filament admin panel)
        if ($request->is('admin/*')) {
            // For Filament admin panel, redirect to the index page with a notification
            $resourceName = ucfirst($this->model);
            $indexUrl = "/admin/{$this->model}s"; // Default fallback
            
            // Try to determine the correct admin URL pattern
            if ($this->model === 'insurance') {
                $indexUrl = '/admin/insurances';
            }
            
            return redirect($indexUrl)->with('error', $this->getCustomMessage());
        }
        
        // For other web requests, show a 404 page
        abort(404, $this->getCustomMessage());
    }
}
