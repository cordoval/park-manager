<?php

declare(strict_types=1);

/*
 * Copyright (c) the Contributors as noted in the AUTHORS file.
 *
 * This file is part of the Park-Manager project.
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace ParkManager\Bridge\Doctrine\Test;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Sebastiaan Stok <s.stok@rollerworks.net>
 */
abstract class EntityRepositoryTestCase extends KernelTestCase
{
    /**
     * @var ContainerInterface|null
     */
    protected $container;

    protected function setUp()
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();

        $this->setUpDatabaseTransaction();
    }

    protected function tearDown()
    {
        $this->tearDownDatabaseTransaction();
        $this->container = null;
        parent::tearDown();
    }

    protected function assertInTransaction(?string $manager = null)
    {
        self::assertTrue($this->getEntityManager($manager)->getConnection()->getTransactionNestingLevel() > 0, 'Expected to be in a transactional');
    }

    protected function setUpDatabaseTransaction(?string $manager = null): void
    {
        $em = $this->getEntityManager($manager);
        while ($em->getConnection()->getTransactionNestingLevel() > 0) {
            $em->rollback();
        }

        $em->beginTransaction();
    }

    protected function tearDownDatabaseTransaction(?string $manager = null): void
    {
        $em = $this->getEntityManager($manager);
        while ($em->getConnection()->getTransactionNestingLevel() > 0) {
            $em->rollback();
        }
    }

    protected function getEntityManager(?string $manager = 'doctrine.orm.default_entity_manager'): EntityManagerInterface
    {
        /** @var EntityManager $em */
        return $this->container->get($manager ?? $this->getDefaultManagerName());
    }

    protected function getDefaultManagerName(): string
    {
        return 'doctrine.orm.default_entity_manager';
    }
}
