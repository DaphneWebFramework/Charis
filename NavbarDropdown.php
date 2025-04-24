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
 * Represents a dropdown navigation item inside a navbar.
 *
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:label`: The text for the dropdown. Defaults to an empty string.
 * - `:disabled`: Boolean indicating whether the dropdown is disabled. Defaults
 *   to `false`.
 */
class NavbarDropdown extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array where standard HTML attributes apply to
     *   the wrapper element, and pseudo attributes configure inner components.
     *   Pass `null` or an empty array to indicate no attributes. Defaults to
     *   `null`.
     * @param array<NavbarDropdownItem|NavbarDropdownDivider> $items
     *   (Optional) The dropdown menu items to render inside the dropdown
     *   container. Defaults to an empty array.
     */
    public function __construct(?array $attributes = null, array $items = [])
    {
        $label = $this->consumePseudoAttribute($attributes, ':label', '');
        $disabled = $this->consumePseudoAttribute($attributes, ':disabled', false);

        $linkAttributes = [
            'class' => 'nav-link dropdown-toggle',
            'href' => '#',
            'role' => 'button',
            'data-bs-toggle' => 'dropdown',
            'aria-expanded' => 'false'
        ];
        if ($disabled) {
            $linkAttributes['class'] .= ' disabled';
            $linkAttributes['aria-disabled'] = 'true';
        }
        $content = [
            new Generic('a', $linkAttributes, $label),
            new Generic('ul', ['class' => 'dropdown-menu'], $items)
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
