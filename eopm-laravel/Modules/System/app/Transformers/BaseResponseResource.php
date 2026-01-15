<?php

namespace Modules\System\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseResponseResource extends JsonResource
{
    protected ?string $message;
    protected int $statusCode;
    protected ?array $errors;
    protected ?array $meta;

    /**
     * BaseResponseResource constructor.
     *
     * @param mixed $resource
     * @param string|null $message
     * @param int $statusCode
     * @param array|null $errors
     * @param array|null $meta
     */
    public function __construct($resource = null, string $message = null, int $statusCode = 200, ?array $errors = null, ?array $meta = null)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        $this->meta = $meta;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $resource = $this->resource;
        $isPaginated = $resource instanceof LengthAwarePaginator;

        if ($resource instanceof AnonymousResourceCollection) {
            $resource = $resource->resource;
            $isPaginated = $resource instanceof LengthAwarePaginator;
        }

        $data = $isPaginated ? $resource->items() : ($resource['data'] ?? $resource);

        $pagination = $isPaginated
            ? [
                'current_page' => $resource->currentPage(),
                'per_page' => $resource->perPage(),
                'total_records' => $resource->total(),
                'current_records' => $resource->count(),
                'total_pages' => $resource->lastPage(),
                'has_next' => $resource->hasMorePages(),
                'has_previous' => $resource->currentPage() > 1,
            ]
            : ($resource['pagination'] ?? null);

        return [
            'status' => $this->errors ? 'error' : 'success',
            'message' => $this->message,
            'data' => $data,
            'pagination' => $pagination,
            'errors' => $this->errors,
            'meta' => $this->meta,
        ];
    }


    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
