<?php declare(strict_types=1);
/**
 * Button.php
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
 * Represents a Bootstrap Button component.
 *
 * @link https://getbootstrap.com/docs/5.3/components/buttons/
 */
class Button extends Component
{
    /**
     * Default attributes.
     *
     * Includes the `type` attribute set to `button` and the default CSS class
     * `btn btn-primary`, aligning with Bootstrap button styling.
     *
     * @var array<string, string>
     */
    private const DEFAULT_ATTRIBUTES = [
        'type' => 'button',
        'class' => 'btn btn-primary'
    ];

    /**
     * Mutually exclusive CSS class groups.
     *
     * Ensures conflicting styles such as `btn-primary` and `btn-secondary`, or
     * `btn-lg` and `btn-sm`, are not combined on the same button element.
     *
     * @var string[]
     */
    private const MUTUALLY_EXCLUSIVE_CLASS_GROUPS = [
        'btn-primary btn-secondary btn-success btn-info btn-warning btn-danger btn-light btn-dark btn-outline-primary btn-outline-secondary btn-outline-success btn-outline-info btn-outline-warning btn-outline-danger btn-outline-light btn-outline-dark btn-link',
        'btn-lg btn-sm'
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
            'button',
            ComponentHelper::MergeAttributes(
                self::DEFAULT_ATTRIBUTES,
                $attributes,
                self::MUTUALLY_EXCLUSIVE_CLASS_GROUPS
            ),
            $content
        );
    }
}
