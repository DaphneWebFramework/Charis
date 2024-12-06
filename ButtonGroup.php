<?php declare(strict_types=1);
/**
 * ButtonGroup.php
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
 * Represents a Bootstrap [Button Group](https://getbootstrap.com/docs/5.3/components/button-group/)
 * component.
 */
class ButtonGroup extends Component
{
    /**
     * Default attributes.
     *
     * Includes the `class` attribute set to "btn-group" and the `role`
     * attribute set to "group". The `aria-label` attribute is provided
     * but left blank for the user to fill in as needed for accessibility
     * purposes.
     *
     * @var array<string, bool|int|float|string>
     */
    private const DEFAULT_ATTRIBUTES = [
        'class' => 'btn-group',
        'role' => 'group',
        'aria-label' => ''
    ];

    /**
     * Mutually exclusive CSS class groups.
     *
     * Ensures that "btn-group" and "btn-group-vertical" classes cannot be
     * applied simultaneously to the same button group element.
     *
     * @var string[]
     */
    private const MUTUALLY_EXCLUSIVE_CLASS_GROUPS = [
        'btn-group btn-group-vertical',
        'btn-group-lg', 'btn-group-sm'
    ];

    /**
     * Constructs a new instance.
     *
     * @param array<string, bool|int|float|string>|null $attributes
     *   (Optional) An associative array of HTML attributes, where keys are
     *   attribute names and values can be scalar types (`bool`, `int`, `float`,
     *   or `string`). Pass `null` or an empty array to use default attributes.
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
        parent::__construct(
            'div',
            ComponentHelper::MergeAttributes(
                self::DEFAULT_ATTRIBUTES,
                $attributes,
                self::MUTUALLY_EXCLUSIVE_CLASS_GROUPS
            ),
            $content
        );
    }
}
