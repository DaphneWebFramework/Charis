<?php declare(strict_types=1);
/**
 * FormRadio.php
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
use \Charis\FormControls\FormRadioInput;

/**
 * Represents a radio input with a label and optional help text.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/checks-radios/#radios
 */
class FormRadio extends FormCheckableComposite
{
    #region FormComposite overrides --------------------------------------------

    protected function createFormControl(array $attributes): FormControl
    {
        return new FormRadioInput($attributes);
    }

    #endregion FormComposite overrides
}
