<?php declare(strict_types=1);
/**
 * NavbarDropdownItem.php
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
 * Represents an item inside a navbar dropdown menu.
 *
 * Aside from HTML attributes that apply to the wrapper element, this component
 * supports the following pseudo attributes in its constructor:
 *
 * - `:label`: The text for the link. Defaults to an empty string.
 * - `:href`: The URL for the link. Defaults to `#`.
 * - `:disabled`: Boolean indicating whether the link is disabled. Defaults to
 *   `false`.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navbar/#nav
 */
class NavbarDropdownItem extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array where standard HTML attributes apply to
     *   the wrapper element, and pseudo attributes configure the inner anchor.
     *   Pass `null` or an empty array to indicate no attributes. Defaults to
     *   `null`.
     */
    public function __construct(?array $attributes = null)
    {
        $label = $this->consumePseudoAttribute($attributes, ':label', '');
        $href = $this->consumePseudoAttribute($attributes, ':href', '#');
        $disabled = $this->consumePseudoAttribute($attributes, ':disabled', false);

        $linkAttributes = [
            'class' => 'dropdown-item',
            'href' => $href
        ];
        if ($disabled) {
            $linkAttributes['class'] .= ' disabled';
            $linkAttributes['aria-disabled'] = 'true';
        }
        $content = new Generic('a', $linkAttributes, $label);

        parent::__construct(null, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'li';
    }

    #endregion Component overrides
}
