<?php declare(strict_types=1);
/**
 * FormPasswordInput.php
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
 * Represents a password input.
 */
class FormPasswordInput extends FormInput
{
    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return $this->mergeAttributes(
            [
                'type' => 'password',
                'minlength' => 8, // NIST & OWASP recommended minimum
                'maxlength' => 72 // Safe upper limit for bcrypt algorithm
            ],
            parent::getDefaultAttributes(),
            parent::getMutuallyExclusiveClassAttributeGroups()
        );
    }

    #endregion Component overrides
}
