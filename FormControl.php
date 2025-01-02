<?php declare(strict_types=1);
/**
 * FormControl.php
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
 * Abstract base class for Bootstrap Form Control components.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/form-control/
 *
 * @codeCoverageIgnore
 */
abstract class FormControl extends Component
{
    protected function getDefaultAttributes(): array
    {
        return [
            'class' => 'form-control'
        ];
    }

    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [
            'form-control form-control-plaintext form-check-input',
            'form-control-lg form-control-sm'
        ];
    }
}
