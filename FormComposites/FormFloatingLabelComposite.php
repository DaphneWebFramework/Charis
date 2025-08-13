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
 * - `:label` (string): Text for the label element. If omitted, no label is
 *   rendered.
 * - `:label:*` (mixed): Additional HTML attributes forwarded to the label
 *   element.
 * - `:input:*` (mixed): Additional HTML attributes forwarded to the input
 *   element.
 * - `:help` (string): Text for additional help below the input. If omitted,
 *   no help text is rendered.
 * - `:help:*` (mixed): Additional HTML attributes forwarded to the help text
 *   element.
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
        $label = $this->consumePseudoAttribute($attributes, ':label');
        $help = $this->consumePseudoAttribute($attributes, ':help');

        $attributeDefaults = ['placeholder' => '']; // Mandatory for floating labels
        if ($label !== null) {
            $attributeDefaults['id'] = 'form-input-' . \uniqid();
        }
        if ($help !== null) {
            $attributeDefaults['aria-describedby'] = 'form-help-' . \uniqid();
        }
        $inputAttributes = $this->mergeAttributes(
            $this->consumeScopedPseudoAttributes($attributes, 'input'),
            $attributeDefaults
        );

        $content = [];

        $content[] = $this->createFormInputComponent($inputAttributes);

        if ($label !== null) {
            $attributeDefaults = [];
            if (\array_key_exists('id', $inputAttributes)) {
                $attributeDefaults['for'] = $inputAttributes['id'];
            }
            $labelAttributes = $this->mergeAttributes(
                $this->consumeScopedPseudoAttributes($attributes, 'label'),
                $attributeDefaults
            );
            $content[] = new Label($labelAttributes, $label);
        }

        if ($help !== null) {
            $attributeDefaults = [];
            if (\array_key_exists('aria-describedby', $inputAttributes)) {
                $attributeDefaults['id'] = $inputAttributes['aria-describedby'];
            }
            $helpAttributes = $this->mergeAttributes(
                $this->consumeScopedPseudoAttributes($attributes, 'help'),
                $attributeDefaults
            );
            $content[] = new FormHelpText($helpAttributes, $help);
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
