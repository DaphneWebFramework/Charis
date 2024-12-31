<?php declare(strict_types=1);
/**
 * FormSwitch.php
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
 * Represents a Bootstrap Switch component.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches
 */
class FormSwitch extends FormCheckableComposite
{
    #region FormComposite overrides --------------------------------------------

    protected function getWrapperClassAttribute(): string
    {
        return parent::getWrapperClassAttribute() . ' form-switch';
    }

    protected function createFormInputComponent(array $attributes): FormInput
    {
        return new FormSwitchInput($attributes);
    }

    #endregion FormComposite overrides
}
