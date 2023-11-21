<?php

namespace App\Base\Database;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Doctrine\ORM\Decorator\EntityManagerDecorator as DoctrineEntityManagerDecorator;

final class EntityManagerDecorator extends DoctrineEntityManagerDecorator
{

}