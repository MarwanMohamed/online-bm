<?php

namespace App\Repositories;

use App\Models\Lookup\Lookup;

class LookupRepository extends BaseRepository
{
    protected $model = Lookup::class;

    public function chainOnIndexQuery($query, $request = null)
    {
        return $query->when(request()->category_code, function ($query) {
            $query->whereRelation('category', 'code', request('category_code'));
        });
    }

    public function store($data = [])
    {
        $data['is_active'] = !! data_get($data, 'is_active', true);
        $data['is_system'] = !! data_get($data, 'is_system', false);

        return parent::store($data);
    }
}
