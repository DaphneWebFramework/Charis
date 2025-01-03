<?php declare(strict_types=1);
/**
 * Generic.php
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
 * A component for rendering arbitrary HTML elements.
 */
class Generic extends Component
{
    /**
     * The HTML tag name for the component.
     *
     * @var string
     */
    private readonly string $tagName;

    /**
     * Indicates whether the component is self-closing.
     *
     * @var bool
     */
    private readonly bool $isSelfClosing;

    /**
     * Constructs a new instance.
     *
     * @param string $tagName
     *   The HTML tag name for the component (e.g., "div", "span").
     * @param array<string, bool|int|float|string>|null $attributes
     *   (Optional) An associative array of HTML attributes, where keys are
     *   attribute names and values can be scalar types (`bool`, `int`, `float`,
     *   or `string`). Pass `null` or an empty array to indicate no attributes.
     *   Defaults to `null`.
     * @param string|Component|array<string|Component>|null $content
     *   (Optional) The content of the component, which can be a string, a
     *   single `Component` instance, an array of strings and `Component`
     *   instances, or `null` for no content. Defaults to `null`.
     * @param bool $isSelfClosing
     *   (Optional) Indicates whether the component is self-closing (e.g.,
     *   `<img/>`). Defaults to `false`.
     */
    public function __construct(
        string $tagName,
        ?array $attributes = null,
        string|Component|array|null $content = null,
        bool $isSelfClosing = false)
    {
        $this->tagName = $tagName;
        $this->isSelfClosing = $isSelfClosing;
        parent::__construct($attributes, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return $this->tagName;
    }

    protected function isSelfClosing(): bool
    {
        return $this->isSelfClosing;
    }

    #endregion Component overrides
}
