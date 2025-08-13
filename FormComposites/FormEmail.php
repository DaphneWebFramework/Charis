<?php declare(strict_types=1);
/**
 * FormEmail.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormComposites;

use \Charis\FormControls\FormControl;
use \Charis\FormControls\FormEmailInput;

/**
 * Represents an email input with a label and optional help text.
 */
class FormEmail extends FormStandardComposite
{
    #region FormComposite overrides --------------------------------------------

    protected function createFormControl(array $attributes): FormControl
    {
        return new FormEmailInput($attributes);
    }

    #endregion FormComposite overrides
}
