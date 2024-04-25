<?php

namespace App\Home\Helpers;

use App\Models\Lookup\Lookup;
use App\Models\Lookup\LookupCategory;

class LookupHelper
{
    /**
     * Retrieve the lookup category by its code.
     *
     * @return \App\Models\Lookup\LookupCategory[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getCategories()
    {
        return LookupCategory::cached();
    }

    /**
     * Retrieve the lookup category by its code.
     *
     * @param string $code
     * @return \App\Models\Lookup\LookupCategory
     */
    public static function getLookupCategoryByCode($code)
    {
        return LookupCategory::cached()->where('code', $code)->first();
    }

    /**
     * Retrieve the lookup by the given code, model type or category id.
     *
     * @param string|null $code
     * @param string|null $entityType
     * @param int|null $categoryId
     * @return \App\Models\Lookup\Lookup
     */
    public static function getLookup($code = null, $entityType = null, $categoryId = null)
    {
        return Lookup::cached()
            ->when($code, function ($lookups) use ($code) {
                return $lookups->where('code', $code);
            })
            ->when($entityType, function ($lookups) use ($entityType) {
                return $lookups->where('model_type', $entityType);
            })
            ->when($code, function ($lookups) use ($code) {
                return $lookups->where('code', $code);
            })
            ->when($categoryId, function ($lookups) use ($categoryId) {
                return $lookups->where('category_id', $categoryId);
            })
            ->first();
    }
}
