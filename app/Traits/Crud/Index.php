<?php

namespace App\Traits\Crud;

use Illuminate\Support\Facades\App;
use Yajra\DataTables\Facades\DataTables;

trait Index
{
    protected $filter;
    protected $orderBy;
    protected $paginate;
    protected $datatableResponse;

    /**
     * this will return a dataTable response or a normal resource response
     * and will edit any image attribute to the link of the image
     * as in the database we only save image name
     *
     * @return mixed
     */
    public function index($request = null)
    {
        if (! $this->datatableResponse) {
            $paginateOrGet = $this->paginate ? 'paginate' : 'get';

            $result = $this->indexQuery($request)->$paginateOrGet($this->paginate ?: ['*']);
            return $result;
        }

        $dataTables = DataTables::eloquent($this->indexQuery($request));

        if (! empty($this->filter)) {
            foreach ($this->filter as $filter) {
                $dataTables->filterColumn($filter['column'], function ($queryM, $_keyword) use ($filter) {
                    $queryM->whereRaw($filter['sql'], ["%{$_keyword}%"]);
                });
            }
        }

        if (! empty($this->orderBy)) {
            foreach ($this->orderBy as $order) {
                $dataTables->orderColumn($order['column'], $order['sql']);
            }
        }

        return $dataTables;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function setPaginate($perPage)
    {
        $this->paginate = $perPage;

        return $this;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function setDatatableResponse(bool $bool = true)
    {
        $this->datatableResponse = $bool;

        return $this;
    }

    /**
     * this method will return the translations on a model
     * if it uses translatable package
     *
     * @return mixed
     */
    public function indexQuery($request = null)
    {
        $model = new $this->model;

        if (! ($translatedAttrs = $model->translatedAttributes)) {
            return $this->chainOnIndexQuery($model->query(), $request);
        }

        $query = $this->joinTranslationQuery($model, $translatedAttrs);

        return $this->chainOnIndexQuery($query, $request);
    }

    /**
     * join translation table on the main query
     *
     * @param $model
     * @param $translatedAttrs
     * @return mixed
     */
    public function joinTranslationQuery($model, $translatedAttrs)
    {
        $translationTable = (new $model->getTranslationModelName())->getTable();

        $translationColumns = '';

        foreach ($translatedAttrs as $key => $attr) {
            $translationColumns .= " , {$translationTable}.{$attr}";
        }

        return $model
            ->query()
            ->leftJoin($translationTable, function ($join) use ($model, $translationTable) {
                $join->on("{$model->getTable()}.id", '=', $model->translationForeignKey)
                    ->where("{$translationTable}.locale", App::getLocale());
            })
            ->selectRaw("{$model->getTable()}.* {$translationColumns}");
    }

    /**
     * this methods can be overridden in the controllers
     * to chain on the query
     *
     * @param $query
     * @return mixed
     */
    public function chainOnIndexQuery($query, $request = null)
    {
        return $query;
    }
}
