<?php

namespace App\Api\V1;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\UI\Controller\IController;
use Apitte\OpenApi\ISchemaBuilder;

#[Path("/api/v1/openapi")]
#[Tag('OpenAPI')]
final class OpenApiController implements IController
{
    public function __construct(protected ISchemaBuilder $builder)
    {
    }

    #[Path("/")]
    #[Method('GET')]
    public function index(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        $openApi = $this->builder->build();

        return $response
            ->withAddedHeader('Access-Control-Allow-Origin', '*')
            ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST')
            ->withAddedHeader('Access-Control-Allow-Headers', 'Content-Type')
            ->writeJsonBody($openApi->toArray());
    }
}