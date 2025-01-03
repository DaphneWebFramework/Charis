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

namespace Charis\FormControls;

use \Charis\Component;

/**
 * Abstract base class for form controls, defining common attributes and
 * behaviors.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/form-control/
 */
abstract class FormControl extends Component
{
    #region Component overrides ------------------------------------------------

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

    #endregion Component overrides
}
