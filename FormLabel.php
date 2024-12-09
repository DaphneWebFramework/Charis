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
 * Represents a `<label>` element styled as a Bootstrap form control label.
 */
class FormLabel extends Component
{
    protected function getTagName(): string
    {
        return 'label';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'class' => 'form-label',
            'for' => ''
        ];
    }

    protected function getMutuallyExclusiveClassGroups(): array
    {
        return [
            'form-label form-check-label'
        ];
    }
}
