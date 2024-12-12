<?php declare(strict_types=1);
/**
 * FormRadioInput.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis;

/**
 * Represents an `<input>` element styled as a Bootstrap radio input.
 */
class FormRadioInput extends FormInput
{
    protected function getDefaultAttributes(): array
    {
        return \array_merge(parent::getDefaultAttributes(), [
            'class' => 'form-check-input',
            'type' => 'radio',
            'name' => ''
        ]);
    }
}
