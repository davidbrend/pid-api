<?php

namespace App\Api\V1;

use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\UI\Controller\IController;

#[Path("/api/v1")]
abstract class BaseV1Controller implements IController
{
}