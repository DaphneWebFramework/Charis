<?php declare(strict_types=1);
/**
 * FormTextAreaFL.php
 *
 * (C) 2025 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormComposites;

use \Charis\FormControls\FormControl;
use \Charis\FormControls\FormTextAreaControl;

/**
 * Represents a text area with a floating label and optional help text.
 */
class FormTextAreaFL extends FormFloatingLabelComposite
{
    #region FormComposite overrides --------------------------------------------

    protected function createFormControl(array $attributes): FormControl
    {
        return new FormTextAreaControl($attributes);
    }

    #endregion FormComposite overrides
}
