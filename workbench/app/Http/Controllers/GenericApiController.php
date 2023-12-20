<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jassoec\GenericMaker\App\Contracts\GenericApiServiceContract;


abstract class GenericApiController extends Controller
{
    public function __construct(
        protected GenericApiServiceContract $service,
        protected array $storeRules = [],
        protected array $updateRules = [],
        protected array $storeMessages = [],
        protected array $updateMessages = [],
        protected array $where = [],
        protected array $with = [],
        protected array $orderBy = []
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            return $this->getSuccessResponse(
                $this->service->getPaginatedData(
                    $request->query('search', ''),
                    $request->query('per_page', 10),
                    $request->query('page', 1),
                    $this->where,
                    $this->with,
                    $this->orderBy
                )
            );
        } catch (\Throwable $th) {
            return $this->getErrorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            return $this->getSuccessResponse(
                data: $this->service->create($request->all()),
                status: Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            return $this->getErrorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->getSuccessResponse(
                $this->service->readOne($id, $this->with)
            );
        } catch (\Throwable $th) {
            return $this->getErrorResponse($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            return $this->getSuccessResponse(
                $this->service->update($id, $request->all())
            );
        } catch (\Throwable $th) {
            return $this->getErrorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return $this->getSuccessResponse(
                $this->service->delete($id)
            );
        } catch (\Throwable $th) {
            return $this->getErrorResponse($th);
        }
    }

    protected function getSuccessResponse(
        mixed $data,
        int $status = Response::HTTP_OK,
        array $metadata = [],
        array $headers = []
    ): JsonResponse {
        if ($data instanceof \Illuminate\Contracts\Pagination\Paginator) {
            return response()->json([
                'data' => $data->items(),
                'metadata' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                    'path' => $data->path(),
                    'first_page_url' => $data->url(1),
                    'next_page_url' => $data->nextPageUrl(),
                    'prev_page_url' => $data->previousPageUrl(),
                ],
            ], $status, $headers);
        }

        return response()->json([
            'data' => $data,
            'metadata' => $metadata,
        ], $status, $headers);
    }

    protected function getErrorResponse(
        \Throwable $exception,
        string $message = '',
        int $status = Response::HTTP_BAD_REQUEST,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        $response = ["message" => $message ?? $exception->getMessage()];

        if (!is_null($exception) && config('app.debug')) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'trace'   => $exception->getTraceAsString()
            ];
        }

        return response()->json(
            $response,
            $exception->getCode() ?? $status,
            $headers,
            $options
        );
    }
}
