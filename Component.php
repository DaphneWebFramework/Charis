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
 * Abstract base class for defining and rendering HTML components.
 *
 * Subclasses must implement the `getTagName` method to specify the HTML tag
 * for the component. Subclasses can optionally override:
 *
 * - `getDefaultAttributes`: Returns the default attributes for the component.
 * - `getMutuallyExclusiveClassAttributeGroups`: Specifies groups of CSS classes
 *   where only one class from each group can be applied simultaneously.
 * - `isSelfClosing`: Indicates whether the component is self-closing (e.g.,
 *   `<input/>`, `<img/>`).
 */
abstract class Component implements \Stringable
{
    /**
     * Regex pattern for validating HTML tag names.
     */
    private const TAG_NAME_PATTERN = '/^[a-zA-Z][a-zA-Z0-9\-]*$/';

    /**
     * Regex pattern for validating HTML attribute names.
     */
    private const ATTRIBUTE_NAME_PATTERN = '/^[a-zA-Z][a-zA-Z0-9\:\-\.\_]*$/';

    /**
     * An associative array of HTML attributes, where keys are attribute names
     * and values can be scalar types (`bool`, `int`, `float`, or `string`).
     * Can be `null` if no attributes are defined.
     *
     * @var array<string, bool|int|float|string>|null
     */
    private readonly ?array $attributes;

    /**
     * The content of the component, which can be a string, a single `Component`
     * instance, an array of strings and `Component` instances, or `null` for
     * no content.
     *
     * @var string|Component|array<string|Component>|null
     */
    private readonly string|Component|array|null $content;

    #region public -------------------------------------------------------------

    /**
     * Constructs a new instance.
     *
     * @param array<string, bool|int|float|string>|null $attributes
     *   (Optional) An associative array of HTML attributes, where keys are
     *   attribute names and values can be scalar types (`bool`, `int`, `float`,
     *   or `string`). Pass `null` or an empty array to indicate no attributes.
     *   Defaults to `null`.
     * @param string|Component|array<string|Component>|null $content
     *   (Optional) The content of the component, which can be a string, a
     *   single `Component` instance, an array of strings and `Component`
     *   instances, or `null` for no content. Defaults to `null`.
     */
    public function __construct(
        ?array $attributes = null,
        string|Component|array|null $content = null)
    {
        $this->attributes = Helper::MergeAttributes(
            $attributes,
            $this->getDefaultAttributes(),
            $this->getMutuallyExclusiveClassAttributeGroups()
        );
        $this->content = $content;
    }

    /**
     * Converts the component to its HTML string representation.
     *
     * This method simply calls the `Render` method, performs no additional
     * processing.
     *
     * @return string
     *   The HTML string representation of the component.
     * @throws \InvalidArgumentException
     *   If the `Render` method throws this exception.
     * @throws \LogicException
     *   If the `Render` method throws this exception.
     *
     * @see Render
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
     *   If the tag name does not match the required pattern, an attribute name
     *   is not a string or does not match the required pattern, an attribute
     *   value is not scalar, or the content array contains an item that is
     *   neither a string nor a `Component` instance.
     * @throws \LogicException
     *   If the component is self-closing but has non-empty content.
     */
    public function Render(): string
    {
        $tagName = $this->getTagName();
        if (!\preg_match(self::TAG_NAME_PATTERN, $tagName)) {
            throw new \InvalidArgumentException('Invalid tag name.');
        }
        $html = "<{$tagName}";
        $renderedAttributes = $this->renderAttributes();
        if ($renderedAttributes !== '') {
            $html .= " $renderedAttributes";
        }
        if ($this->isSelfClosing()) {
            if (!empty($this->content)) {
                throw new \LogicException('Self-closing components cannot have content.');
            }
            $html .= '/>';
        } else {
            $html .= ">{$this->renderContent()}</{$tagName}>";
        }
        return $html;
    }

    #endregion public

    #region protected ----------------------------------------------------------

    /**
     * Provides the tag name.
     *
     * Subclasses must override this method to define the tag name.
     *
     * @return string
     *   The HTML tag name (e.g., "div", "span").
     */
    abstract protected function getTagName(): string;

    /**
     * Provides default attributes.
     *
     * Subclasses can override this method to define their own default attributes.
     * These attributes are merged with user-provided attributes at runtime.
     *
     * By default, returns an empty array.
     *
     * @return array<string, bool|int|float|string>
     *   An associative array of default attributes where keys are attribute
     *   names and values are scalar types (`bool`, `int`, `float`, or `string`).
     */
    protected function getDefaultAttributes(): array
    {
        return [];
    }

    /**
     * Provides mutually exclusive class groups.
     *
     * Subclasses can override this method to define groups of class names that
     * should not coexist. When multiple classes from the same group are provided,
     * the conflict is resolved to retain only one.
     *
     * By default, returns an empty array.
     *
     * @return string[]
     *   An array of space-separated strings representing mutually exclusive
     *   class groups (e.g., `['btn-primary btn-secondary btn-success', 'btn-sm
     *   btn-lg']`).
     */
    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [];
    }

    /**
     * Indicates whether the component is self-closing.
     *
     * Subclasses can override this method to return `true` for self-closing
     * components.
     *
     * By default, returns `false`.
     *
     * @return bool
     *   Returns `true` if the component is self-closing (e.g., `<img/>`),
     *   otherwise `false`.
     */
    protected function isSelfClosing(): bool
    {
        return false;
    }

    #endregion protected

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
     *   If an attribute name is not a string, does not match the required
     *   pattern, or an attribute value is not scalar.
     */
    private function renderAttributes(): string
    {
        if (empty($this->attributes)) {
            return '';
        }
        $items = [];
        foreach ($this->attributes as $name => $value) {
            if (!\is_string($name)) {
                throw new \InvalidArgumentException('Attribute name must be a string.');
            }
            if (!\preg_match(self::ATTRIBUTE_NAME_PATTERN, $name)) {
                throw new \InvalidArgumentException('Invalid attribute name.');
            }
            if (!\is_scalar($value)) {
                throw new \InvalidArgumentException('Attribute value must be scalar.');
            }
            if ($value === true) {
                $items[] = $name;
            } elseif ($value !== false) {
                if (\is_string($value)) {
                    $value = \htmlspecialchars($value, \ENT_QUOTES | \ENT_HTML5);
                } else {
                    $value = (string)$value;
                }
                $items[] = "$name=\"$value\"";
            }
        }
        return \implode(' ', $items);
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
        } elseif (\is_array($this->content)) {
            $items = [];
            foreach ($this->content as $item) {
                if (!\is_string($item) && !$item instanceof Component) {
                    throw new \InvalidArgumentException(
                        'Content array must only contain strings or Component instances.');
                }
                $items[] = (string)$item;
            }
            return \implode('', $items);
        } else {
            return (string)$this->content;
        }
    }

    #endregion private
}
