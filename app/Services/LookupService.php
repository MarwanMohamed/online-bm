<?php

namespace App\Services;

use App\Repositories\LookupRepository;
use Illuminate\Http\Request;

class LookupService extends BaseService
{
    protected $repository;

    public function __construct(LookupRepository $lookupRepository)
    {
        $this->repository = $lookupRepository;
    }
}
