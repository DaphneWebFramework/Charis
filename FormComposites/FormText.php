<?php declare(strict_types=1);
/**
 * FormText.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormComposites;

use \Charis\FormControls\FormInput;
use \Charis\FormControls\FormTextInput;

class FormText extends FormStandardComposite
{
    #region FormComposite overrides --------------------------------------------

    protected function createFormInputComponent(array $attributes): FormInput
    {
        return new FormTextInput($attributes);
    }

    #endregion FormComposite overrides
}
