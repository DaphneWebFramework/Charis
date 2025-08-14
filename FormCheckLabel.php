<?php declare(strict_types=1);
/**
 * FormCheckLabel.php
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
 * Represents a label for checkable inputs, such as checks, radios, and switches.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/checks-radios/#approach
 */
class FormCheckLabel extends FormLabel
{
    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return $this->mergeAttributes(
            [
                'class' => 'form-check-label'
            ],
            parent::getDefaultAttributes(),
            parent::getMutuallyExclusiveClassAttributeGroups()
        );
    }

    #endregion Component overrides
}
