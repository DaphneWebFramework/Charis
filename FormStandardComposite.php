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

namespace Charis;

/**
 * Abstract base class for standard form components with a label, input, and
 * optional help text.
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
 * - `:placeholder`: Specifies a hint or short description that appears inside
 *   the input element when it is empty.
 * - `:disabled`: Boolean indicating whether the input should be disabled.
 *   Defaults to `false`.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/form-control/
 *
 * @codeCoverageIgnore
 */
abstract class FormStandardComposite extends FormComposite
{
    /**
     * Constructs a new instance.
     *
     * @param array<string, bool|int|float|string>|null $attributes
     *   (Optional) An associative array where HTML attributes apply to the
     *   wrapper element, and pseudo attributes configure inner child elements.
     *
     * @todo Add `:placeholder` attribute.
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
        $placeholder = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':placeholder');
        $disabled = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':disabled', false);

        // 2. Generate identifiers.
        if ($id === null && $labelText !== null) {
            $id = 'form-input-' . \uniqid();
        }
        $helpId = $helpText !== null ? 'form-help-text-' . \uniqid() : null;

        // 3. Create child components.
        $content = [];
        if ($labelText !== null) {
            $content[] = new FormLabel(['for' => $id], $labelText);
        }
        $content[] = $this->createFormInputComponent([
            ...($id !== null ? ['id' => $id] : []),
            ...($name !== null ? ['name' => $name] : []),
            ...($helpId !== null ? ['aria-describedby' => $helpId] : []),
            ...($placeholder !== null ? ['placeholder' => $placeholder] : []),
            'disabled' => $disabled
        ]);
        if ($helpText !== null) {
            $content[] = new FormHelpText(['id' => $helpId], $helpText);
        }

        // 4. Pass attributes and content to parent constructor.
        parent::__construct($attributes, $content);
    }

    #region FormComposite overrides --------------------------------------------

    protected function getCompositeClassAttribute(): string
    {
        return 'mb-3';
    }

    #endregion FormComposite overrides
}
