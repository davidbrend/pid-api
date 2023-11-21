<?php

declare(strict_types=1);

namespace App\Base\Database;

use Doctrine\ORM\EntityRepository;

/**
 * @template T of object
 * @extends EntityRepository<T>
 */
abstract class BaseRepository extends EntityRepository
{
}
