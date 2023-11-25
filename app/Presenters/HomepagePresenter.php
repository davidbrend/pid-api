<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Base\Presenters\BasePresenter;
use App\Facades\PidFacade;
use GuzzleHttp\Exception\GuzzleException;
use Nette\Application\AbortException;
use Nette\DI\Attributes\Inject;

class HomepagePresenter extends BasePresenter
{
    #[Inject]
    public PidFacade $facade;

    public function renderDefault(): void
    {
        $date = new \DateTimeImmutable();
        $t1 = (clone $date)->setDate(2023, 11, 23)->setTime(23,00);
        bdump($this->facade->getAllPointsOfSaleByCriteria(date: $t1));
    }

    /**
     * @throws GuzzleException
     * @throws AbortException
     */
    public function handleUpdatePointsOfSale(): void
    {
        $this->facade->synchronizePointsOfSaleFromPID();
        $this->redirect('this');
    }
}
