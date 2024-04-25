<?php

namespace App\Http\Requests;

class DatatableRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start' => ['nullable', 'int'],
            'length' => ['nullable', 'int'],
            'search.value' => ['nullable'],
            'columns.*.name' => ['nullable'],
            'columns.*.data' => ['nullable'],
            'columns.*.searchable' => ['nullable'],
            'columns.*.orderable' => ['nullable'],
            'order.*.column' => ['nullable'],
            'order.*.dir' => ['nullable'],
        ];
    }

    /**
     *
     * This method used to add parameters to scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'start' => [
                'description' => 'The index of the first item you want results for',
                'example' => 1,
            ],
            'length' => [
                'description' => 'The number of items you want included on each page of results. There could be fewer items remaining than the value you specify.',
                'example' => 20,
            ],
            'search.value' => [
                'description' => 'The value of the datatable search for the displayed columns',
                'example' => '',
            ],
            'columns.*.name' => [
                'description' => 'The name of the column in the datatable',
                'example' => 'id',
            ],
            'columns.*.data' => [
                'description' => 'The name of the column in the database',
                'example' => 'id',
            ],
            'columns.*.searchable' => [
                'description' => 'Determine whither the column is searchable',
                'example' => true,
            ],
            'columns.*.orderable' => [
                'description' => 'Determine whither the column is sortable',
                'example' => true,
            ],
            'order.*.column' => [
                'description' => 'The column that you want to sort by',
                'example' => 'id',
            ],
            'order.*.dir' => [
                'description' => 'The sorting direction that you want to sort by',
                'example' => 'DESC',
            ],
        ];
    }
}
