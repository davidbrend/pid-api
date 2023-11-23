<?php

namespace App\Api\V1\PointsOfSale;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\Response;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Negotiation\Http\ArrayEntity;
use App\Api\V1\BaseV1Controller;
use App\Api\V1\PointsOfSale\Models\Response\PointOfSaleResponse;
use App\Facades\PidFacade;

#[Path('/pointsOfSale')]
#[Tag('PointsOfSale')]
class PointsOfSaleController extends BaseV1Controller
{

    public function __construct(protected PidFacade $facade)
    {
    }

    #[Path('/find')]
    #[Method('GET')]
    #[RequestParameter(name: "is open", type: "bool", in: "query", required: false, description: "Find open points")]
    #[RequestParameter(name:"from", type:"datetime", in:"query", required:false, description:"DateTime from")]
    #[RequestParameter(name:"to", type:"datetime", in:"query", required:false, description:"DateTime to")]
    #[RequestParameter(name:"limit", type:"int", in:"query", required:false, description:"Data limit")]
    #[RequestParameter(name:"offset", type:"int", in:"query", required:false, description:"Data offset")]
    #[Response(description: "Success", code: "200", entity: PointOfSaleResponse::class)]
    #[Response(description: "Not found", code: "404")]
    public function getAllProducts(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        $isOpen = $request->getParameter('is open', false);
        $from = $request->getParameter('from');
        $to = $request->getParameter('to');
        $limit = $request->getParameter('limit');
        $offset = $request->getParameter('offset');
        return $response->withStatus(ApiResponse::S200_OK)
            ->withEntity(ArrayEntity::from($this->facade->getAllPointsOfSaleByCriteria($from, $to, $isOpen, $offset, $limit)));
    }
}