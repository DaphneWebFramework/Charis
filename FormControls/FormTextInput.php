<?php declare(strict_types=1);
/**
 * FormTextInput.php
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
 * Represents a text input.
 */
class FormTextInput extends FormInput
{
    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return \array_merge(parent::getDefaultAttributes(), [
            'type' => 'text'
        ]);
    }

    #endregion Component overrides
}
