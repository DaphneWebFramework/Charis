<?php declare(strict_types=1);
/**
 * FormCheckableComposite.php
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
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:id`: A unique identifier for the input element. If omitted and a
 *   `:label-text` is provided, an ID is generated automatically.
 * - `:name`: The name attribute for the input element, used for grouping
 *   related inputs and identifying the input's value during form submission.
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
abstract class FormCheckableComposite extends FormComposite
{
    /**
     * Constructs a new instance.
     *
     * @param array<string, bool|int|float|string>|null $attributes
     *   (Optional) An associative array where HTML attributes apply to the
     *   wrapper element, and pseudo attributes configure inner child elements.
     */
    public function __construct(?array $attributes = null)
    {
        // 1. Consume pseudo attributes.
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

        // 2. Generate identifiers.
        if ($id === null && $labelText !== null) {
            $id = 'form-checkable-' . \uniqid();
        }
        $helpId = $helpText !== null ? 'help-text-' . \uniqid() : null;

        // 3. Create child components.
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

        // 4. Pass attributes and content to parent constructor.
        parent::__construct($attributes, $content);
    }

    #region FormComposite overrides --------------------------------------------

    protected function getWrapperClassAttribute(): string
    {
        return 'form-check';
    }

    #endregion FormComposite overrides
}
