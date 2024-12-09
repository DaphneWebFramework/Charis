<?php declare(strict_types=1);
/**
 * FormCheck.php
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
 * Represents a Bootstrap Check component.
 *
 * This component supports the following pseudo attributes in its constructor:
 * - `:id`: A unique identifier for the input element. If omitted, a generated
 *   ID will be used if `:label-text` is provided.
 * - `:label-text`: The text content for the associated label. If present, a
 *   `<label>` element will be rendered.
 * - `:help-text`: Additional descriptive text. If present, a `<div>` element
 *   will be rendered.
 * - `:checked`: A boolean indicating whether the input should be checked.
 *   Defaults to `false`.
 * - `:disabled`: A boolean indicating whether the input should be disabled.
 *   Defaults to `false`.
 *
 * Standard HTML attributes (e.g., `class`, `style`, `data-*`) can also be
 * passed in the `$attributes` parameter and will be merged with the component's
 * default attributes.
 *
 * @link https://getbootstrap.com/docs/5.3/forms/checks-radios/#checks
 */
class FormCheck extends Component
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
        $id = ComponentHelper::ConsumePseudoAttribute($attributes, ':id');
        $labelText = ComponentHelper::ConsumePseudoAttribute($attributes, ':label-text');
        $helpText = ComponentHelper::ConsumePseudoAttribute($attributes, ':help-text');
        $checked = ComponentHelper::ConsumePseudoAttribute($attributes, ':checked', false);
        $disabled = ComponentHelper::ConsumePseudoAttribute($attributes, ':disabled', false);

        if ($id === null && $labelText !== null) {
            $id = 'form-check-' . \uniqid();
        }

        $helpId = $helpText !== null ? 'help-text-' . \uniqid() : null;

        $content = [
            new FormCheckInput([
                ...($id !== null ? ['id' => $id] : []),
                ...($helpId !== null ? ['aria-describedby' => $helpId] : []),
                'checked' => $checked,
                'disabled' => $disabled
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

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => 'form-check'];
    }
}
