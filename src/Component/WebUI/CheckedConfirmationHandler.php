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

namespace ParkManager\Component\WebUI;

/**
 * The CheckedConfirmationHandler works same as the ConfirmationHandler except
 * that it requires a matching value is provided to reduce mistakenly confirming.
 *
 * Some operations cannot be easily undone (delete/transfer), to prevent accidentally
 * pressing the confirmation button (for any reason) the user is asked to provide (typing)
 * a specific value like the name of the account they wish to delete.
 * Otherwise the confirmation is rejected.
 *
 * Typing the name forces the user to think if what there about to do is correct
 * (instead of blindly pressing YES), and reduces "small-screen area mistakes".
 *
 * @author Sebastiaan Stok <s.stok@rollerworks.net>
 */
final class CheckedConfirmationHandler extends BaseConfirmationHandler
{
    protected $templateContext = [
        'cancel_url' => null,
        'required_value' => '',
        'provided_value' => '',
        'error' => null,
    ];

    /**
     * Configure the confirmation handler for usage.
     *
     * @param string $title          The title of the confirmation (eg. "park_manager.module.action.confirm.title")
     * @param string $message        The message of the confirmation (eg. "park_manager.module.action.confirm.body")
     * @param string $requiredValue
     * @param string $yesButtonLabel The label of the confirmation button (should contain a clear indication about what
     *                               will happen "Remove user")
     *
     * @return CheckedConfirmationHandler
     */
    public function configure(string $title, string $message, string $requiredValue, string $yesButtonLabel): self
    {
        $this->templateContext['title'] = $title;
        $this->templateContext['message'] = $message;
        $this->templateContext['yes_btn_label'] = $yesButtonLabel;
        $this->templateContext['required_value'] = $requiredValue;

        return $this;
    }

    /**
     * Returns whether the action was confirmed (and has a valid token).
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        $this->guardNeedsRequest();

        if (!$this->request->isMethod('POST') || !$this->checkToken()) {
            return false;
        }

        if (!is_scalar($val = $this->request->request->get('_value', ''))) {
            $val = '';
        }

        $this->templateContext['provided_value'] = $val = (string) $val;

        // Remove spaces and lowercase the input (this is about sanity not strictness)
        $reqVal = mb_strtolower(trim($this->templateContext['required_value']));
        $val = mb_strtolower(trim($val));

        if ('' === $val || substr_compare($val, $reqVal, -mb_strlen($reqVal, '8bit')) !== 0) {
            $this->templateContext['error'] = 'Value does not match expected "{{ value }}".';

            return false;
        }

        return true;
    }
}
