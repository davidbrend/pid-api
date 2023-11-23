<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Base\Presenters\BasePresenter;
use App\Facades\PidFacade;
use GuzzleHttp\Exception\GuzzleException;
use Nette\DI\Attributes\Inject;

class HomepagePresenter extends BasePresenter
{
    #[Inject]
    public PidFacade $facade;

    public function renderDefault(): void
    {

    }

    /**
     * @throws GuzzleException
     */
    public function handleUpdatePointsOfSale(): void
    {
        $this->facade->synchronizePointsOfSaleFromPID();
    }
}
