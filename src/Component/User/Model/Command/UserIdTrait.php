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

namespace ParkManager\Component\User\Model\Command;

use ParkManager\Component\User\Model\UserId;

trait UserIdTrait
{
    /**
     * @var UserId|null
     */
    protected $id;

    public function id(): UserId
    {
        if (null === $this->id) {
            $this->id = UserId::fromString($this->payload['id'] ?? $this->metadata['_entity_id']);
        }

        return $this->id;
    }
}
