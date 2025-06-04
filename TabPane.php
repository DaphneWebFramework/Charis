<?php declare(strict_types=1);
/**
 * TabPane.php
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
 * Represents a content pane in a tab navigation.
 *
 * Aside from HTML attributes, this component supports the following pseudo
 * attributes in its constructor:
 *
 * - `:key` (string, required): A unique name used to associate this pane with
 *   its corresponding tab item.
 * - `:active` (boolean): Indicates whether this pane is initially visible.
 *   Defaults to `false`.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navs-tabs/#javascript-behavior
 */
class TabPane extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array of HTML attributes and pseudo
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

        $id = "pane-{$key}";
        $tabId = "tab-{$key}";

        $attributes ??= [];
        $attributes['id'] ??= $id;
        if ($active) {
            $attributes['class'] = $this->combineClassAttributes(
                $attributes['class'] ?? '',
                'show active'
            );
        }
        $attributes['aria-labelledby'] ??= $tabId;

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
            'id' => '',
            'class' => 'tab-pane fade',
            'role' => 'tabpanel',
            'aria-labelledby' => '',
            'tabindex' => '0'
        ];
    }

    #endregion Component overrides
}
