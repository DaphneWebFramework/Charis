<?php declare(strict_types=1);
/**
 * NavbarDropdown.php
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
 * Represents a dropdown navigation item in a navigation bar.
 *
 * Aside from HTML attributes that apply to the wrapper `<li>` element, this
 * component supports the following pseudo attributes in its constructor:
 *
 * - `:label` (string): The text content of the item. Defaults to an empty string.
 * - `:disabled` (boolean): Indicates whether the item is non-interactive.
 *   Defaults to `false`.
 * - `:link:*` (mixed): Additional HTML attributes forwarded to the internal
 *   `<a>` element.
 * - ':menu:*' (mixed): Additional HTML attributes forwarded to the internal
 *   `<ul>` element.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navbar/#nav
 */
class NavbarDropdown extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array where standard HTML attributes apply to
     *   the wrapper element, and pseudo attributes configure internal structure.
     *   Pass `null` or an empty array to indicate no attributes. Defaults to
     *   `null`.
     * @param array<NavbarDropdownItem|NavbarDropdownDivider> $items
     *   (Optional) The menu items to render inside the dropdown container.
     *   Defaults to an empty array.
     */
    public function __construct(?array $attributes = null, array $items = [])
    {
        $label = $this->consumePseudoAttribute($attributes, ':label', '');
        $disabled = $this->consumePseudoAttribute($attributes, ':disabled', false);

        $linkAttributes = $this->mergeAttributes(
            $this->consumeScopedPseudoAttributes($attributes, 'link'),
            [
                'class' => 'nav-link dropdown-toggle',
                'href' => '#',
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => 'false'
            ]
        );
        $menuAttributes = $this->mergeAttributes(
            $this->consumeScopedPseudoAttributes($attributes, 'menu'),
            [
                'class' => 'dropdown-menu'
            ]
        );
        if ($disabled) {
            $linkAttributes['class'] .= ' disabled';
            $linkAttributes['aria-disabled'] = 'true';
        }
        $content = [
            new Generic('a', $linkAttributes, $label),
            new Generic('ul', $menuAttributes, $items)
        ];

        parent::__construct($attributes, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'li';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => 'nav-item dropdown'];
    }

    #endregion Component overrides
}
