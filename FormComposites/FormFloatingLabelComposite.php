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

use \Charis\ComponentHelper;
use \Charis\Label;
use \Charis\FormHelpText;

/**
 * @link https://getbootstrap.com/docs/5.3/forms/floating-labels/
 *
 * @codeCoverageIgnore
 */
abstract class FormFloatingLabelComposite extends FormComposite
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
        $disabled = ComponentHelper::ConsumePseudoAttribute(
            $attributes, ':disabled', false);

        // 2. Generate identifiers.
        if ($id === null && $labelText !== null) {
            $id = 'form-input-' . \uniqid();
        }
        $helpId = $helpText !== null ? 'form-help-text-' . \uniqid() : null;

        // 3. Create child components.
        $content = [
            $this->createFormInputComponent([
                ...($id !== null ? ['id' => $id] : []),
                ...($name !== null ? ['name' => $name] : []),
                ...($helpId !== null ? ['aria-describedby' => $helpId] : []),
                'placeholder' => '', // required
                'disabled' => $disabled
            ])
        ];
        if ($labelText !== null) {
            $content[] = new Label(['for' => $id], $labelText);
        }
        if ($helpText !== null) {
            $content[] = new FormHelpText(['id' => $helpId], $helpText);
        }

        // 4. Pass attributes and content to parent constructor.
        parent::__construct($attributes, $content);
    }

    #region FormComposite overrides --------------------------------------------

    protected function getCompositeClassAttribute(): string
    {
        return 'form-floating mb-3';
    }

    #endregion FormComposite overrides
}
