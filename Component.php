<?php declare(strict_types=1);
/**
 * Component.php
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
 * Represents an abstract base for HTML-like components.
 */
abstract class Component implements \Stringable
{
    /**
     * The HTML tag name for this component (e.g., "div", "span").
     *
     * @var string
     */
    private readonly string $tagName;

    /**
     * An associative array of HTML attributes, where keys are attribute names
     * and values can be scalar types (`bool`, `int`, `float`, or `string`).
     *
     * @var array<string, bool|int|float|string>
     */
    private readonly array $attributes;

    /**
     * The content of the component, which can be a string, a single `Component`
     * instance, an array of strings and `Component` instances, or `null` for
     * no content.
     *
     * @var string|Component|array<string|Component>|null
     */
    private readonly string|Component|array|null $content;

    /**
     * Indicates whether this component is self-closing (e.g., `<img/>`).
     *
     * @var bool
     */
    private readonly bool $isSelfClosing;

    /**
     * Creates a new Component instance.
     *
     * @param string $tagName
     *   The HTML tag name for this component (e.g., "div", "span").
     * @param array<string, bool|int|float|string> $attributes
     *   (Optional) An associative array of HTML attributes, where keys are
     *   attribute names and values can be scalar types (`bool`, `int`, `float`,
     *   or `string`). Defaults to an empty array.
     * @param string|Component|array<string|Component>|null $content
     *   (Optional) The content of the component, which can be a string, a
     *   single `Component` instance, an array of strings and `Component`
     *   instances, or `null` for no content. Defaults to `null`.
     * @param bool $isSelfClosing
     *   (Optional) Indicates whether this component is self-closing (e.g.,
     *   `<img />`). Defaults to `false`.
     */
    protected function __construct(
        string $tagName,
        array $attributes = [],
        string|Component|array|null $content = null,
        bool $isSelfClosing = false)
    {
        $this->tagName = $tagName;
        $this->attributes = $attributes;
        $this->content = $content;
        $this->isSelfClosing = $isSelfClosing;
    }

    #region public -------------------------------------------------------------

    /**
     * Converts the component to its HTML string representation.
     *
     * @return string
     *   The HTML string representation of the component.
     * @throws \InvalidArgumentException
     *   If the tag name is empty, contains invalid characters, an attribute
     *   name is not a string, is empty, an attribute value is not scalar, or
     *   the content array contains an item that is neither a string nor a
     *   `Component` instance.
     * @throws \LogicException
     *   If the component is self-closing but has non-empty content.
     */
    public function __toString(): string
    {
        return $this->Render();
    }

    /**
     * Generates the HTML string representation of the component.
     *
     * @return string
     *   The HTML string representation of the component.
     * @throws \InvalidArgumentException
     *   If the tag name is empty, contains invalid characters, an attribute
     *   name is not a string, is empty, an attribute value is not scalar, or
     *   the content array contains an item that is neither a string nor a
     *   `Component` instance.
     * @throws \LogicException
     *   If the component is self-closing but has non-empty content.
     */
    public function Render(): string
    {
        if ($this->tagName === '') {
            throw new \InvalidArgumentException('Tag name cannot be empty.');
        }
        if (!\preg_match('/^[a-zA-Z][a-zA-Z0-9\-]*$/', $this->tagName)) {
            throw new \InvalidArgumentException('Invalid tag name.');
        }
        $html = "<{$this->tagName}";
        $renderedAttributes = $this->renderAttributes();
        if ($renderedAttributes !== '') {
            $html .= " $renderedAttributes";
        }
        if ($this->isSelfClosing) {
            if (!empty($this->content)) {
                throw new \LogicException(
                    'Self-closing components cannot have content.');
            }
            $html .= '/>';
        } else {
            $html .= ">{$this->renderContent()}</{$this->tagName}>";
        }
        return $html;
    }

    #endregion public

    #region private ------------------------------------------------------------

    /**
     * Renders the HTML attributes as a string.
     *
     * Boolean attribute values are treated specially: `true` renders the
     * attribute name only (e.g., `checked`), while `false` causes the entire
     * attribute to be ignored and not rendered.
     *
     * @return string
     *   The rendered attributes (e.g., `id="example" class="foo bar"`).
     * @throws \InvalidArgumentException
     *   If an attribute name is not a string, is empty, or an attribute value
     *   is not scalar.
     */
    private function renderAttributes(): string
    {
        $items = [];
        foreach ($this->attributes as $name => $value) {
            if (!is_string($name)) {
                throw new \InvalidArgumentException(
                    'Attribute name must be a string.');
            }
            if ($name === '') {
                throw new \InvalidArgumentException(
                    'Attribute name cannot be empty.');
            }
            if (!is_scalar($value)) {
                throw new \InvalidArgumentException(
                    'Attribute value must be scalar.');
            }
            if ($value === true) {
                $items[] = self::encode($name);
            } elseif ($value !== false) {
                $name = self::encode($name);
                $value = self::encode((string)$value);
                $items[] = "$name=\"$value\"";
            }
        }
        return implode(' ', $items);
    }

    /**
     * Renders the content as string.
     *
     * @return string
     *   The rendered content.
     * @throws \InvalidArgumentException
     *   If the content array contains an item that is neither a string nor
     *   a `Component` instance.
     */
    private function renderContent(): string
    {
        if ($this->content === null) {
            return '';
        } elseif (is_array($this->content)) {
            $items = [];
            foreach ($this->content as $item) {
                if (!is_string($item) && !$item instanceof Component) {
                    throw new \InvalidArgumentException(
                        'Content array must only contain strings or Component instances.');
                }
                $items[] = (string)$item;
            }
            return implode('', $items);
        } else {
            return (string)$this->content;
        }
    }

    /**
     * Encodes a string for safe HTML output.
     *
     * @param string $string
     *   The string to encode.
     * @return string
     *   The encoded string.
     */
    private static function encode(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
    }

    #endregion private
}
