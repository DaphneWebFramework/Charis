<?php declare(strict_types=1);
/**
 * FormFloatingLabelComposite.php
 *
 * (C) 2025 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormComposites;

use \Charis\Label;
use \Charis\FormHelpText;

/**
 * Abstract base class for form composites, combining an input control with a
 * floating label and optional help text.
 *
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:id`: A unique identifier for the input element. If omitted and a `:label`
 *   is provided, an ID is generated automatically.
 * - `:name`: The name attribute for the input element, used to identify the
 *   input's value during form submission.
 * - `:label`: Text for the associated `<label>` element. If omitted, no label
 *   is rendered.
 * - `:help`: Additional descriptive text. If provided, a `<div>` element with
 *   the "form-text" class is rendered.
 * - `:disabled`: Boolean indicating whether the input should be disabled.
 *   Defaults to `false`.
 * - `:required`: Boolean indicating whether the input is required. Defaults to
 *   `false`.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/floating-labels/
 */
abstract class FormFloatingLabelComposite extends FormComposite
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
        $label = $this->consumePseudoAttribute($attributes, ':label');
        $help = $this->consumePseudoAttribute($attributes, ':help');
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
        if ($helpId !== null) {
            $inputAttributes['aria-describedby'] = $helpId;
        }
        $inputAttributes['placeholder'] = ''; // mandatory for floating labels
        if ($autocomplete !== null) {
            $inputAttributes['autocomplete'] = $autocomplete;
        }
        $inputAttributes['disabled'] = $disabled;
        $inputAttributes['required'] = $required;

        $content = [
            $this->createFormInputComponent($inputAttributes)
        ];
        if ($label !== null) {
            $content[] = new Label(['for' => $id], $label);
        }
        if ($help !== null) {
            $content[] = new FormHelpText(['id' => $helpId], $help);
        }

        parent::__construct($attributes, $content);
    }

    #region FormComposite overrides --------------------------------------------

    protected function getCompositeClassAttribute(): string
    {
        return 'form-floating mb-3';
    }

    #endregion FormComposite overrides
}
