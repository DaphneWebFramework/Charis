<?php declare(strict_types=1);
/**
 * FormLabel.php
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
 * Represents a label for a form control.
 */
class FormLabel extends Label
{
    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return \array_merge(parent::getDefaultAttributes(), [
            'class' => 'form-label'
        ]);
    }

    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [
            'form-label form-check-label'
        ];
    }

    #endregion Component overrides
}
