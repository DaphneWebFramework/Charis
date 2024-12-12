<?php declare(strict_types=1);
/**
 * FormRadio.php
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
 * Represents a Bootstrap Radio component.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/checks-radios/#radios
 */
class FormRadio extends FormCheckable
{
    #region FormCheckable overrides --------------------------------------------

    protected function getWrapperClassAttribute(): string
    {
        return 'form-check';
    }

    protected function createFormInputComponent(array $attributes): FormInput
    {
        return new FormRadioInput($attributes);
    }

    #endregion FormCheckable overrides
}
