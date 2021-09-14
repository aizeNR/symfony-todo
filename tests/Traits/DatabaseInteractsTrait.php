<?php

namespace App\Tests\Traits;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

trait DatabaseInteractsTrait
{
    /**
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    protected function setUpEntityManager()
    {
        if (!WebTestCase::$booted) {
            throw new \LogicException('Boot kernel!');
        }

        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function getRepository($entityName)
    {
        return $this->entityManager->getRepository($entityName);
    }

    protected function assertFindInDatabase($entityName, array $criteria)
    {
        $entity = $this->getRepository($entityName)->findOneBy($criteria);

        $message = "Entity $entityName with criteria (" . json_encode($criteria) . ") not found.";

        $this->assertNotNull($entity, $message);

        return $entity;
    }

    protected function assertNotFindInDatabase($entityName, array $criteria)
    {
        $entity = $this->getRepository($entityName)->findOneBy($criteria);

        $message = "Entity $entityName with criteria (" . json_encode($criteria) . ") found.";

        $this->assertNull($entity, $message);

        return $entity;
    }
}