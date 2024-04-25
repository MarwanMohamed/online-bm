<?php

namespace App\Traits;

use Illuminate\Http\Response as illuminateResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ResponseTrait
{
    protected $statusCode = 200;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param  $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    protected function respondCreated($message, $data = null)
    {
        return $this->setStatusCode(illuminateResponse::HTTP_CREATED)->respond([
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function respondWithSuccess($message, $data = null)
    {
        return $this->respond([
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * @param $data
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    protected function respondWithPagination(LengthAwarePaginator $paginatedResult, $data)
    {
        $data = array_merge($data, [

            'paginator' => [
                'totalCount' => $paginatedResult->total(),
                'totalPages' => ceil($paginatedResult->total() / $paginatedResult->perPage()),
                'currentPage' => $paginatedResult->currentPage(),
                'limit' => $paginatedResult->perPage(),
            ],
        ]);

        return $this->respond($data);
    }
}
