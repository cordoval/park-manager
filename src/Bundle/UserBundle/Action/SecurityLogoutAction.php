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

namespace ParkManager\Bundle\UserBundle\Action;

/**
 * @author Sebastiaan Stok <s.stok@rollerworks.net>
 *
 * @codeCoverageIgnore
 */
final class SecurityLogoutAction
{
    public function __invoke()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
