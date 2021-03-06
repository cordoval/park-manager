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

namespace ParkManager\Module\Webhosting\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use ParkManager\Bridge\Doctrine\EventSourcedEntityRepository;
use ParkManager\Module\Webhosting\Model\Package\Exception\WebhostingPackageNotFound;
use ParkManager\Module\Webhosting\Model\Package\{
    WebhostingPackage,
    WebhostingPackageId,
    WebhostingPackageRepository
};
use Prooph\ServiceBus\EventBus;

/**
 * @author Sebastiaan Stok <s.stok@rollerworks.net>
 */
final class WebhostingPackageOrmRepository extends EventSourcedEntityRepository implements WebhostingPackageRepository
{
    public function __construct(EntityManagerInterface $entityManager, EventBus $eventBus, string $className = WebhostingPackage::class)
    {
        parent::__construct($entityManager, $eventBus, $className);
    }

    public function get(WebhostingPackageId $id): WebhostingPackage
    {
        /** @var WebhostingPackage|null $package */
        $package = $this->find($id->toString());

        if (null === $package) {
            throw WebhostingPackageNotFound::withId($id);
        }

        return $package;
    }

    public function save(WebhostingPackage $package): void
    {
        $this->doTransactionalPersist($package);
        $this->doDispatchEvents($package);
    }

    public function remove(WebhostingPackage $package): void
    {
        $this->doTransactionalRemove($package);
        $this->doDispatchEvents($package);
    }
}
