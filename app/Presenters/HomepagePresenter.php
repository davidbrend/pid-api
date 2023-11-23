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
        $date = new \DateTimeImmutable();
        $t1 = (clone $date)->setTime(8,0);
        // $t2 = (clone $date)->setTime(5,0);
        $this->facade->getAllPointsOfSaleByCriteria(isOpen: true);
    }

    /**
     * @throws GuzzleException
     */
    public function handleUpdatePointsOfSale(): void
    {
        $this->facade->synchronizePointsOfSaleFromPID();
    }
}
