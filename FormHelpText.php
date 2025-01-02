<?php declare(strict_types=1);
/**
 * FormHelpText.php
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
 * Represents contextual help text for a Bootstrap form control.
 */
class FormHelpText extends Component
{
    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'id' => '',
            'class' => 'form-text'
        ];
    }
}
