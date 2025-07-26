<?php declare(strict_types=1);
/**
 * Spinner.php
 *
 * (C) 2025 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis;

/**
 * Represents a Bootstrap spinner.
 *
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:type` (string): The spinner style. Accepted values are 'border' and
 *   'grow'. Defaults to 'border'.
 * - `:size` (string): Size modifier. Only 'sm' is supported. Defaults to
 *   standard size.
 * - `:label` (string): Accessible text for screen readers. Defaults to
 *   "Loading...".
 *
 * @link https://getbootstrap.com/docs/5.3/components/spinners/
 */
class Spinner extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array where standard HTML attributes apply to
     *   the wrapper element, and pseudo attributes configure internal structure.
     *   Pass `null` or an empty array to indicate no attributes. Defaults to
     *   `null`.
     */
    public function __construct(?array $attributes = null)
    {
        // 1
        $type = $this->consumePseudoAttribute($attributes, ':type', 'border');
        $size = $this->consumePseudoAttribute($attributes, ':size');
        $label = $this->consumePseudoAttribute($attributes, ':label', 'Loading...');
        // 2
        if (!\in_array($type, ['border', 'grow'], true)) {
            $type = 'border';
        }
        if ($size !== null && $size !== 'sm') {
            $size = null;
        }
        // 3
        $classes = null;
        if ($type === 'grow') {
            $classes[] = 'spinner-grow';
        }
        if ($size === 'sm') {
            $classes[] = "spinner-{$type}-sm";
        }
        // 4
        if ($classes !== null) {
            $attributes['class'] = $this->combineClassAttributes(
                $attributes['class'] ?? '',
                \implode(' ', $classes)
            );
        }
        // 5
        $content = new Generic('span', ['class' => 'visually-hidden'], $label);
        parent::__construct($attributes, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'class' => 'spinner-border',
            'role' => 'status'
        ];
    }

    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [
            'spinner-border spinner-grow',      // base class conflict
            'spinner-border spinner-grow-sm',   // invalid mix
            'spinner-border-sm spinner-grow',   // invalid mix (opposite)
            'spinner-border-sm spinner-grow-sm' // size conflict
        ];
    }

    #endregion Component overrides
}
