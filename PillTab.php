<?php declare(strict_types=1);
/**
 * PillTab.php
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
 * Represents a tab item in a pill-based tab navigation.
 *
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:key` (string, required): A unique name used to associate this item with
 *   its corresponding content pane.
 * - `:active` (boolean): Indicates whether this item is initially active.
 *   Defaults to `false`.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navs-tabs/#javascript-behavior
 */
class PillTab extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array of standard HTML attributes and pseudo
     *   attributes. Defaults to `null`.
     * @param string|Component|array<string|Component>|null $content
     *   (Optional) The content or child elements of the component. This can be
     *   a string, a `Component` instance, an array of strings and `Component`
     *   instances, or `null` for no content. Defaults to `null`.
     */
    public function __construct(
        ?array $attributes = null,
        string|Component|array|null $content = null
    ) {
        $key = $this->consumePseudoAttribute($attributes, ':key');
        if (!\is_string($key) || $key === '') {
            throw new \InvalidArgumentException(
                'The ":key" attribute must be a non-empty string.');
        }
        $active = $this->consumePseudoAttribute($attributes, ':active', false);

        $id = "tab-{$key}";
        $paneId = "pane-{$key}";

        $attributes ??= [];
        $attributes['id'] ??= $id;
        if ($active) {
            if (($attributes['disabled'] ?? false) === true) {
                $active = false;
            } else {
                $attributes['class'] = $this->combineClassAttributes(
                    $attributes['class'] ?? '',
                    'active'
                );
            }
        }
        $attributes['data-bs-target'] ??= "#{$paneId}";
        $attributes['aria-controls'] ??= $paneId;
        $attributes['aria-selected'] = $active ? 'true' : 'false';

        parent::__construct($attributes, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'button';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'id' => '',
            'class' => 'nav-link',
            'type' => 'button',
            'role' => 'tab',
            'data-bs-toggle' => 'pill',
            'data-bs-target' => '#',
            'aria-controls' => '',
            'aria-selected' => ''
        ];
    }

    #endregion Component overrides
}
