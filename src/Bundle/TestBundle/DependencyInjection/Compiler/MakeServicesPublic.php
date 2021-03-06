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

namespace ParkManager\Bundle\TestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MakeServicesPublic implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->findDefinition('doctrine')->setPublic(true);
        $container->findDefinition('doctrine.dbal.default_connection')->setPublic(true);
        $container->findDefinition('doctrine.orm.default_entity_manager')->setPublic(true);
    }
}
