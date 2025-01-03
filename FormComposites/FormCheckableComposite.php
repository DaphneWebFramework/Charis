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

namespace Charis\FormComposites;

use \Charis\Helper;
use \Charis\FormCheckLabel;
use \Charis\FormHelpText;

/**
 * Abstract base class for checkable form components, such as `FormCheck`,
 * `FormRadio`, and `FormSwitch`.
 *
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:id`: A unique identifier for the input element. If omitted and a `:label`
 *   is provided, an ID is generated automatically.
 * - `:name`: The name attribute for the input element, used for grouping
 *   related inputs and identifying the input's value during form submission.
 * - `:label`: Text for the associated `<label>` element. If omitted, no label
 *   is rendered.
 * - `:help`: Additional descriptive text. If provided, a `<div>` element with
 *   the "form-text" class is rendered.
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
        $id = Helper::ConsumePseudoAttribute($attributes, ':id');
        $name = Helper::ConsumePseudoAttribute($attributes, ':name');
        $label = Helper::ConsumePseudoAttribute($attributes, ':label');
        $help = Helper::ConsumePseudoAttribute($attributes, ':help');
        $checked = Helper::ConsumePseudoAttribute($attributes, ':checked', false);
        $disabled = Helper::ConsumePseudoAttribute($attributes, ':disabled', false);

        // 2. Generate identifiers.
        if ($id === null && $label !== null) {
            $id = 'form-input-' . \uniqid();
        }
        $helpId = $help !== null ? 'form-help-' . \uniqid() : null;

        // 3. Create child components.
        $content = [
            $this->createFormInputComponent([
                ...($id !== null ? ['id' => $id] : []),
                ...($name !== null ? ['name' => $name] : []),
                ...($helpId !== null ? ['aria-describedby' => $helpId] : []),
                'checked' => $checked,
                'disabled' => $disabled
            ])
        ];
        if ($label !== null) {
            $content[] = new FormCheckLabel(['for' => $id], $label);
        }
        if ($help !== null) {
            $content[] = new FormHelpText(['id' => $helpId], $help);
        }

        // 4. Pass attributes and content to parent constructor.
        parent::__construct($attributes, $content);
    }

    #region FormComposite overrides --------------------------------------------

    protected function getCompositeClassAttribute(): string
    {
        return 'form-check';
    }

    #endregion FormComposite overrides
}
