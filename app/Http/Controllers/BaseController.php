<?php

namespace App\Http\Controllers;

use App\Services\ApiResponder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

/**
 * Class RestfulController
 * @package App\Http\Controllers
 */
class BaseController extends Controller
{
    /**
     * @var
     */
    protected $statusCode;

    /**
     * @var ApiResponder
     */
    protected ApiResponder $responder;

    /**
     * @var TransformerAbstract
     */
    protected TransformerAbstract $transformer;

    /**
     * @var mixed|Application
     */
    private mixed $request;

    public function __construct()
    {
        $this->request = app('request');
        $this->responder = new ApiResponder();
    }

    public function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function addMeta($metaKey, $metaValue): self
    {
        $this->responder->addMeta($metaKey, $metaValue);

        return $this;
    }

    public function withPaginator(LengthAwarePaginator $paginator, $transformer = null): JsonResponse
    {
        $statusCode = $this->statusCode ?: ResponseCode::HTTP_OK;
        return $this->responder->respondWithPaginator($paginator, $transformer ?: $this->transformer, $statusCode);
    }

    public function withItem($item, $transformer = null): JsonResponse
    {
        $statusCode = $this->statusCode ?: ResponseCode::HTTP_OK;
        return $this->responder->respondWithItem($item, $transformer ?: $this->transformer, $statusCode);
    }

    public function respondWithEmpty(): Response
    {
        return $this->responder->respondWithEmpty();
    }

    public function withCollection($collection, $transformer = null): JsonResponse
    {
        $statusCode = $this->statusCode ?: ResponseCode::HTTP_OK;
        return $this->responder->respondWithCollection($collection, $transformer ?: $this->transformer, $statusCode);
    }

    public function withArray(array $data): JsonResponse
    {
        $statusCode = $this->statusCode ?: ResponseCode::HTTP_OK;
        return $this->responder->respondWithArray($data, $statusCode);
    }

    public function withException(array $data): JsonResponse
    {
        $statusCode = $this->statusCode ?: ResponseCode::HTTP_BAD_REQUEST;
        return $this->responder->respondWithArray($data, $statusCode);
    }

    protected function getQueryBuilderParams($perPage = 10): array
    {
        return [
            'per_page'  => $this->request->get('per_page', $perPage),
        ];
    }
}
