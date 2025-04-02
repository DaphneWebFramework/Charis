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

use \Charis\Helper;
use \Charis\Label;
use \Charis\FormHelpText;

/**
 * Abstract base class for form composites, combining an input control with a
 * floating label and optional help text.
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
        // 1. Consume pseudo attributes.
        $id = $this->consumePseudoAttribute($attributes, ':id');
        $name = $this->consumePseudoAttribute($attributes, ':name');
        $label = $this->consumePseudoAttribute($attributes, ':label');
        $help = $this->consumePseudoAttribute($attributes, ':help');
        $disabled = $this->consumePseudoAttribute($attributes, ':disabled', false);

        // 2. Generate identifiers.
        if ($id === null && $label !== null) {
            $id = 'form-input-' . \uniqid();
        }
        $helpId = $help !== null ? 'form-help-' . \uniqid() : null;

        // 3. Create inner components.
        $content = [
            $this->createFormInputComponent([
                ...($id !== null ? ['id' => $id] : []),
                ...($name !== null ? ['name' => $name] : []),
                ...($helpId !== null ? ['aria-describedby' => $helpId] : []),
                'placeholder' => '', // required
                'disabled' => $disabled
            ])
        ];
        if ($label !== null) {
            $content[] = new Label(['for' => $id], $label);
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
        return 'form-floating mb-3';
    }

    #endregion FormComposite overrides
}
