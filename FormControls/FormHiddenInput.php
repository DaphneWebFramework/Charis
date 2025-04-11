<?php declare(strict_types=1);
/**
 * FormHiddenInput.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormControls;

/**
 * Represents a hidden input.
 */
class FormHiddenInput extends FormInput
{
    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return [
            'type' => 'hidden'
        ];
    }

    #endregion Component overrides
}
