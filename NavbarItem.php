<?php declare(strict_types=1);
/**
 * NavbarItem.php
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
 * Represents a navigation item in a navigation bar.
 *
 * Aside from HTML attributes that apply to the wrapper `<li>` element, this
 * component supports the following pseudo attributes in its constructor:
 *
 * - `:label` (string): The text content of the item. Defaults to an empty string.
 * - `:href` (string): The target URL when the item is clicked. Defaults to "#".
 * - `:active` (boolean): Indicates whether the item represents the current page.
 *   Defaults to `false`.
 * - `:disabled` (boolean): Indicates whether the item is non-interactive.
 *   Defaults to `false`.
 * - `:link:*` (mixed): Additional HTML attributes forwarded to the internal
 *   `<a>` element.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navbar/#nav
 */
class NavbarItem extends Component
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
        $label = $this->consumePseudoAttribute($attributes, 'label', '');
        $href = $this->consumePseudoAttribute($attributes, 'href', '#');
        $active = $this->consumePseudoAttribute($attributes, 'active', false);
        $disabled = $this->consumePseudoAttribute($attributes, 'disabled', false);

        $linkAttributes = $this->mergeAttributes(
            $this->consumeScopedAttributes($attributes, 'link'),
            [
                'class' => 'nav-link',
                'href' => $href
            ]
        );
        if ($active) {
            $linkAttributes['class'] .= ' active';
            $linkAttributes['aria-current'] = 'page';
        }
        if ($disabled) {
            $linkAttributes['class'] .= ' disabled';
            $linkAttributes['aria-disabled'] = 'true';
        }
        $content = new Generic('a', $linkAttributes, $label);

        parent::__construct($attributes, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'li';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => 'nav-item'];
    }

    #endregion Component overrides
}
