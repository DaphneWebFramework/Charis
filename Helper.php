<?php declare(strict_types=1);
/**
 * Helper.php
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
 * Contains utility functions intended for internal use by the `Component` class
 * and its subclasses.
 */
abstract class Helper
{
    /**
     * Regex pattern for validating pseudo attribute names.
     *
     * Pseudo attributes must start with `:` and can only contain alphanumeric
     * characters and hyphens (`-`). The first character after the `:` must be
     * an alphabetic character.
     */
    private const PSEUDO_ATTRIBUTE_NAME_PATTERN = '/^:[a-zA-Z][a-zA-Z0-9\-]*$/';

    #region public -------------------------------------------------------------

    /**
     * Resolves attributes by merging defaults, resolving classes, and handling
     * mutually exclusive class groups.
     *
     * @param array<string, bool|int|float|string> $defaultAttributes
     *   Default attributes defined by the component.
     * @param array<string, bool|int|float|string>|null $userAttributes
     *   (Optional) Attributes provided by the user.
     * @param string[] $mutuallyExclusiveClassGroups
     *   (Optional) Mutually exclusive class groups to resolve conflicts.
     * @return array<string, bool|int|float|string>
     *   Resolved attributes ready for rendering.
     */
    public static function MergeAttributes(
        array $defaultAttributes,
        ?array $userAttributes = null,
        array $mutuallyExclusiveClassGroups = []
    ): array
    {
        $userAttributes ??= [];
        if (\array_key_exists('class', $defaultAttributes) ||
            \array_key_exists('class', $userAttributes))
        {
            $userAttributes['class'] = self::resolveClassAttributes(
                $defaultAttributes['class'] ?? '',
                $userAttributes['class'] ?? '',
                $mutuallyExclusiveClassGroups
            );
        }
        return \array_merge($defaultAttributes, $userAttributes);
    }

    /**
     * Merges two HTML `class` attribute strings into a single string with
     * duplicates removed.
     *
     * @param string $classes1
     *   A space-separated string of class names.
     * @param string $classes2
     *   Another space-separated string of class names.
     * @return string
     *   A space-separated string containing all class names with duplicates
     *   removed.
     */
    public static function CombineClassAttributes(
        string $classes1,
        string $classes2
    ): string
    {
        return \implode(' ', \array_unique(\array_merge(
            self::parseClassAttribute($classes1),
            self::parseClassAttribute($classes2)
        )));
    }

    /**
     * Returns and removes the specified pseudo attribute from the given
     * attributes array.
     *
     * @param array<string, bool|int|float|string>|null $attributes
     *   An associative array of attributes. The array will be modified in place
     *   by removing the specified pseudo attribute if found.
     * @param string $key
     *   The key of the pseudo attribute to consume. Keys must match the defined
     *   pattern and are case-sensitive.
     * @param mixed $defaultValue
     *   (Optional) The value to return if the key is not present or invalid.
     *   Defaults to `null`.
     * @return mixed
     *   The value of the consumed pseudo attribute, or the default value if not
     *   found.
     */
    public static function ConsumePseudoAttribute(
        ?array &$attributes,
        string $key,
        mixed $defaultValue = null
    ): mixed
    {
        if ($attributes === null
         || !\preg_match(self::PSEUDO_ATTRIBUTE_NAME_PATTERN, $key)
         || !\array_key_exists($key, $attributes))
        {
            return $defaultValue;
        }
        $value = $attributes[$key];
        unset($attributes[$key]);
        return $value;
    }

    #endregion public

    #region private ------------------------------------------------------------

    /**
     * Parses an HTML `class` attribute string into an array of individual class
     * names.
     *
     * @param string $classes
     *   A space-separated string of class names.
     * @return string[]
     *   An array of class names. If the input string is empty or contains only
     *   whitespace, an empty array is returned.
     */
    private static function parseClassAttribute(string $classes): array
    {
        $classes = \trim($classes);
        if ($classes === '') {
            return [];
        }
        return \explode(' ', \preg_replace('/\s+/', ' ', $classes));
    }

    /**
     * Resolve classes by merging default classes with user-defined classes,
     * handling duplicates and managing mutually exclusive groups.
     *
     * @param string $defaultClasses
     *   A space-separated string of default class names defined by the
     *   component (e.g., `'btn btn-primary'`).
     * @param string $userClasses
     *   A space-separated string of user-defined class names (e.g., `'btn-lg
     *   btn-primary'`).
     * @param string[] $mutuallyExclusiveClassGroups
     *   (Optional) An array of space-separated strings representing mutually
     *   exclusive class groups (e.g., `['btn-primary btn-secondary btn-success',
     *   'btn-sm btn-lg']`).
     * @return string
     *   A resolved and merged class string with duplicates removed and mutually
     *   exclusive conflicts resolved.
     */
    private static function resolveClassAttributes(
        string $defaultClasses,
        string $userClasses,
        array $mutuallyExclusiveClassGroups = []
    ): string
    {
        // 1. Parse the default classes, user classes, and mutually exclusive
        // class groups into arrays. Then, flip the default and user class
        // arrays to swap their keys with their values for efficient lookups.
        $defaultClasses = \array_flip(self::parseClassAttribute($defaultClasses));
        $userClasses = \array_flip(self::parseClassAttribute($userClasses));
        $mutuallyExclusiveClassGroups = \array_map(
            [self::class, 'parseClassAttribute'],
            $mutuallyExclusiveClassGroups
        );

        // 2. Merge the user-defined classes and default classes. User classes
        // take precedence.
        $mergedClasses = $userClasses + $defaultClasses;

        // 3. Resolve conflicts in mutually exclusive class groups.
        foreach ($mutuallyExclusiveClassGroups as $group)
        {
            // 3.1. Flip the mutually exclusive class group array to create a
            // mapping of class names to keys for quick lookups.
            $group = \array_flip($group);

            // 3.2. Find classes that are both in the mutually exclusive class
            // group and in the merged classes.
            $conflictingClasses = \array_intersect_key($group, $mergedClasses);

            // 3.3. If more than one conflicting class is present, resolve the
            // conflict.
            if (count($conflictingClasses) > 1)
            {
                // 3.3.1. Remove all conflicting classes from the merged classes.
                foreach ($conflictingClasses as $class => $_) {
                    unset($mergedClasses[$class]);
                }

                // 3.3.2. Find user classes that are in the mutually exclusive
                // class group.
                $userClassesInGroup = \array_intersect_key($userClasses, $group);

                // 3.3.3. Select the first user class from the intersecting
                // classes if it exists.
                $selectedUserClass = \key($userClassesInGroup) ?? null;

                // 3.3.4. If a user class was selected, add it back to the
                // merged classes.
                if ($selectedUserClass !== null) {
                    $mergedClasses[$selectedUserClass] = null;
                }
            }
        }

        // 4. Retrieve the keys from the merged classes and join them with
        // spaces to create the final class string.
        $mergedClasses = \array_keys($mergedClasses);
        return \implode(' ', $mergedClasses);
    }

    #endregion private
}
