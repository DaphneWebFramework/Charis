<?php declare(strict_types=1);
/**
 * FormSelectControl.php
 *
 * (C) 2025 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormControls;

/**
 * Represents a select control.
 */
class FormSelectControl extends FormControl
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'select';
    }

    protected function getDefaultAttributes(): array
    {
        return $this->mergeAttributes(
            [
                'class' => 'form-select'
            ],
            parent::getDefaultAttributes(),
            parent::getMutuallyExclusiveClassAttributeGroups()
        );
    }

    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [
            'form-select-lg form-select-sm'
        ];
    }

    #endregion Component overrides
}
