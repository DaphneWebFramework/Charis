<?php declare(strict_types=1);
/**
 * FormCheckable.php
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
 * Abstract base class for checkable form components such as `FormCheck`,
 * `FormRadio`, and `FormSwitch`.
 *
 * This component supports the following pseudo attributes in its constructor:
 *
 * - `:id`: A unique identifier for the input element. If omitted and a
 * - `:name`: The name attribute for the input element, used for grouping
 *   related inputs and identifying the input's value during form submission.
 *   `:label-text` is provided, an ID is generated automatically.
 * - `:label-text`: Text for the associated `<label>` element. If omitted, no
 *   label is rendered.
 * - `:help-text`: Additional descriptive text. If provided, a `<div>` element
 *   with the "form-text" class is rendered.
 * - `:checked`: Boolean indicating whether the input should be checked.
 *   Defaults to `false`.
 * - `:disabled`: Boolean indicating whether the input should be disabled.
 *   Defaults to `false`.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/checks-radios/
 *
 * @codeCoverageIgnore
 */
abstract class FormCheckable extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param array<string, bool|int|float|string>|null $attributes
     *   (Optional) An associative array of HTML attributes and pseudo
     *   attributes.
     */
    public function __construct(?array $attributes = null)
    {
        $id = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':id');
        $name = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':name');
        $labelText = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':label-text');
        $helpText = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':help-text');
        $checked = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':checked', false);
        $disabled = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':disabled', false);

        if ($id === null && $labelText !== null) {
            $id = 'form-checkable-' . \uniqid();
        }
        $helpId = $helpText !== null ? 'help-text-' . \uniqid() : null;

        $content = [
            $this->createFormInputComponent([
                ...($id !== null ? ['id' => $id] : []),
                ...($name !== null ? ['name' => $name] : []),
                ...($helpId !== null ? ['aria-describedby' => $helpId] : []),
                'checked' => $checked,
                'disabled' => $disabled,
            ])
        ];
        if ($labelText !== null) {
            $content[] = new FormCheckLabel(['for' => $id], $labelText);
        }
        if ($helpText !== null) {
            $content[] = new FormText(['id' => $helpId], $helpText);
        }

        parent::__construct($attributes, $content);
    }

    /**
     * Provides the CSS class for the wrapper element.
     *
     * @return string
     *   The CSS class string for the wrapper element.
     */
    abstract protected function getWrapperClassAttribute(): string;

    /**
     * Creates the form input component.
     *
     * @param array<string, bool|int|float|string> $attributes
     *   Attributes for the input component.
     * @return FormInput
     *   The form input component instance.
     */
    abstract protected function createFormInputComponent(array $attributes): FormInput;

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => $this->getWrapperClassAttribute()];
    }

    #endregion Component overrides
}
