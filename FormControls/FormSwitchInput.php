<?php declare(strict_types=1);
/**
 * FormSwitchInput.php
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
 * Represents a switch input.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches
 */
class FormSwitchInput extends FormInput
{
    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return $this->mergeAttributes(
            [
                'class' => 'form-check-input',
                'type' => 'checkbox',
                'role' => 'switch',
                'switch' => true // Enables haptics on mobile Safari (iOS 17.4+)
            ],
            parent::getDefaultAttributes(),
            parent::getMutuallyExclusiveClassAttributeGroups()
        );
    }

    #endregion Component overrides
}
