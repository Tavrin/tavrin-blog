<?php

namespace App\Repository;

use Sirius\database\EntityManager;
use Sirius\database\Repository;

class MediaRepository extends Repository
{
    public function __construct(?EntityManager $entityManager = null)
    {
        parent::__construct($entityManager, 'media');
    }
}