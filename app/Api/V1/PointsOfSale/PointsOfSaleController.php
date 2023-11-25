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
    #[RequestParameter(name: "IsOpen", type: "bool", in: "query", required: false, description: "Find open points")]
    #[RequestParameter(name:"date", type:"datetime", in:"query", required:false, description:"Filter by date and time (E.g. 2017-07-21T17:32:28Z)")]
    #[Response(description: "Success", code: "200", entity: PointOfSaleResponse::class)]
    #[Response(description: "Not found", code: "404")]
    public function getAllProducts(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        $isOpen = $request->getParameter('IsOpen', false);
        $date = $request->getParameter('date', new \DateTime());

        return $response->withStatus(ApiResponse::S200_OK)
            ->withEntity(ArrayEntity::from($this->facade->getAllPointsOfSaleByCriteria($date, $isOpen)));
    }
}