<?php

namespace Modules\System\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ActionsResponse extends JsonResource
{
    protected ?string $message;
    protected int $statusCode;
    protected ?array $errors;
    protected ?array $meta;
    protected mixed $view;
    protected mixed $route;

    /**
     * ActionsResponse constructor.
     *
     * @param mixed $resource
     * @param string|null $message
     * @param int $statusCode
     * @param array|null $errors
     * @param array|null $meta
     * @param mixed $view
     * @param mixed $route
     */
    public function __construct($resource = null, string $message = null, int $statusCode = 200, ?array $errors = null, ?array $meta = null, $view = null, $route = null)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        $this->meta = $meta;
        $this->view = $view;
        $this->route = $route;
    }

    public static function success($resource = null, string $message = null, ?array $meta = null, $view = null, $route = null) : static {
        return new static($resource, $message ?? __('success'), 200, null, $meta, $view, $route);
    }

    public static function created($resource = null, string $message = null, ?array $meta = null, $view = null, $route = null) : static {
        return new static($resource, $message ?? __('resource_created_successfully'), 201, null, $meta, $view, $route);
    }

    public static function updated($resource = null, string $message = null, ?array $meta = null, $view = null, $route = null) : static {
        return new static($resource, $message ?? __('resource_updated_successfully'), 200, null, $meta, $view, $route);
    }

    public static function deleted($resource = null, string $message = null, ?array $meta = null, $view = null, $route = null) : static {
        return new static($resource, $message ?? __('resource_deleted_successfully'), 200, null, $meta, $view, $route);
    }

    public static function forbidden(?string $message = null) : static
    {
        return new static(message: $message ?? __('forbidden'), statusCode: 403,);
    }

    
    public static function notFound() : static
    {
        return new static(message: __('not found'), statusCode: 404,);
    }

    public function isSuccess() : bool
    {
        return in_array(substr($this->statusCode, 0, 1), [2,3]);
    }

    public function isFailed() : bool
    {
        return ! $this->isSuccess();
    }

    public static function failed(?string $message = null, $statusCode = 422, array $errors = []) : static
    {
        return new static(message: $message ?? __('failed'), statusCode: 422, errors: $errors);
    }

    public static function succeeded(?string $message = null, int $statusCode = 200, mixed $resource = null) : static
    {
        return new static($resource, message: $message ?? __('succeeded'), statusCode: $statusCode);
    }

    public function getMessage(?string $default = null) : ?string {
        return $this->message ?? $default;
    }

    public function getStatusCode() : int {
        return $this->statusCode;
    }
    /**
     * Decide how to respond
     */
    public function toResponse($request)
    {

        if ($request->is('api/*') || $request->expectsJson()) {
            // 3️⃣ Default JSON response
            return parent::toResponse($request);
        } else {
            // 1️⃣ View response
            if ($this->view instanceof View) {
                return response($this->view, $this->statusCode);
            } elseif (is_string($this->view)) {
                return response(view($this->view, $request), $this->statusCode);
            }

            // 2️⃣ Redirect response
            if ($this->route) {
                return redirect($this->route);
            }
        }
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
        $extraMeta = [
            'server_time' => time(),
        ];
        return [
            'status' => $this->errors ? 'error' : (in_array(substr($this->statusCode, 0, 1), [2,3]) ? 'success' : 'warning'),
            'status_code' => $this->statusCode,
            'message' => $this->message,
            'data' => $data,
            'pagination' => $pagination,
            'errors' => $this->errors,
            'meta' => array_merge_recursive($extraMeta,  $this->meta ?? [] )
        ];
    }


    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
