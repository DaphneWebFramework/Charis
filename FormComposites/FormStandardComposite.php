<?php declare(strict_types=1);
/**
 * FormStandardComposite.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormComposites;

use \Charis\FormLabel;
use \Charis\FormHelpText;

/**
 * Abstract base class for form composites, combining an input control with a
 * label and optional help text.
 *
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:id`: A unique identifier for the input element. If omitted and a `:label`
 *   is provided, an ID is generated automatically.
 * - `:name`: The name attribute for the input element, used to identify the
 *   input's value during form submission.
 * - `:value`: The initial value of the input element.
 * - `:label`: Text for the associated `<label>` element. If omitted, no label
 *   is rendered.
 * - `:help`: Additional descriptive text. If provided, a `<div>` element with
 *   the "form-text" class is rendered.
 * - `:placeholder`: Specifies a hint or short description that appears inside
 *   the input element when it is empty.
 * - `:autocomplete`: Provides a hint to browsers for autofilling the input
 *   field with previously entered data.
 * - `:disabled`: Boolean indicating whether the input should be disabled.
 *   Defaults to `false`.
 * - `:required`: Boolean indicating whether the input is required. Defaults to
 *   `false`.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/form-control/
 */
abstract class FormStandardComposite extends FormComposite
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array where standard HTML attributes apply to
     *   the wrapper element, and pseudo attributes configure inner components.
     *   Pass `null` or an empty array to indicate no attributes. Defaults to
     *   `null`.
     */
    public function __construct(?array $attributes = null)
    {
        $id = $this->consumePseudoAttribute($attributes, ':id');
        $name = $this->consumePseudoAttribute($attributes, ':name');
        $value = $this->consumePseudoAttribute($attributes, ':value');
        $label = $this->consumePseudoAttribute($attributes, ':label');
        $help = $this->consumePseudoAttribute($attributes, ':help');
        $placeholder = $this->consumePseudoAttribute($attributes, ':placeholder');
        $autocomplete = $this->consumePseudoAttribute($attributes, ':autocomplete');
        $disabled = $this->consumePseudoAttribute($attributes, ':disabled', false);
        $required = $this->consumePseudoAttribute($attributes, ':required', false);

        if ($id === null && $label !== null) {
            $id = 'form-input-' . \uniqid();
        }
        $helpId = $help !== null ? 'form-help-' . \uniqid() : null;

        $inputAttributes = [];
        if ($id !== null) {
            $inputAttributes['id'] = $id;
        }
        if ($name !== null) {
            $inputAttributes['name'] = $name;
        }
        if ($value !== null) {
            $inputAttributes['value'] = $value;
        }
        if ($helpId !== null) {
            $inputAttributes['aria-describedby'] = $helpId;
        }
        if ($placeholder !== null) {
            $inputAttributes['placeholder'] = $placeholder;
        }
        if ($autocomplete !== null) {
            $inputAttributes['autocomplete'] = $autocomplete;
        }
        $inputAttributes['disabled'] = $disabled;
        $inputAttributes['required'] = $required;

        $content = [];
        if ($label !== null) {
            $content[] = new FormLabel(['for' => $id], $label);
        }
        $content[] = $this->createFormInputComponent($inputAttributes);
        if ($help !== null) {
            $content[] = new FormHelpText(['id' => $helpId], $help);
        }

        parent::__construct($attributes, $content);
    }

    #region FormComposite overrides --------------------------------------------

    protected function getCompositeClassAttribute(): string
    {
        return 'mb-3';
    }

    #endregion FormComposite overrides
}
