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

    /**
     * The name of the `class` attribute in HTML.
     */
    private const CLASS_ATTRIBUTE_NAME = 'class';

    #region public -------------------------------------------------------------

    /**
     * Merges user-provided attributes with default attributes, producing a
     * final set of HTML attributes suitable for rendering.
     *
     * If the `class` attribute is present, its values from both sources
     * are combined. When a mutually exclusive class group is defined
     * (e.g., `'btn-primary btn-secondary btn-success'`), only one class
     * from the group will be retained. User-supplied negative class directives
     * (e.g., `-btn-primary`) remove matching classes from the default list.
     * Additionally, if the user provides `false` as the class value, the entire
     * class attribute is removed from the result, overriding any default.
     *
     * @param ?array<string, mixed> $userAttributes
     *   Attributes provided by the user. Can be `null`.
     * @param array<string, mixed> $defaultAttributes
     *   Default attributes defined by the component.
     * @param array<int, string> $mutuallyExclusiveClassGroups
     *   Mutually exclusive class groups to resolve conflicts. Each group is a
     *   space-separated class names where only one class should survive.
     * @return array<string, mixed>
     *   Final resolved attributes, suitable for rendering.
     */
    public static function MergeAttributes(
        ?array $userAttributes,
        array $defaultAttributes,
        array $mutuallyExclusiveClassGroups
    ): array
    {
        // 1. Determine whether the `class` attribute exists in either side.
        $hasUserClass = $userAttributes !== null &&
            \array_key_exists(self::CLASS_ATTRIBUTE_NAME, $userAttributes);
        $hasDefaultClass =
            \array_key_exists(self::CLASS_ATTRIBUTE_NAME, $defaultAttributes);

        // 2. Extract class values.
        $userClass = $hasUserClass
            ? $userAttributes[self::CLASS_ATTRIBUTE_NAME]
            : null;
        $defaultClass = $hasDefaultClass
            ? $defaultAttributes[self::CLASS_ATTRIBUTE_NAME]
            : null;

        // 3. If user sets `class=true` or `class=false`, remove `class` from
        // default attributes and preserve the boolean for the renderer.
        if (\is_bool($userClass)) {
            if ($hasDefaultClass) {
                unset($defaultAttributes[self::CLASS_ATTRIBUTE_NAME]);
            }
            return \array_merge($defaultAttributes, $userAttributes);
        }

        // 4. Check whether either class value is resolvable (string-like).
        $isUserClassResolvable = self::isResolvableClassAttribute($userClass);
        $isDefaultClassResolvable = self::isResolvableClassAttribute($defaultClass);

        if ($isUserClassResolvable || $isDefaultClassResolvable)
        {
            // 4.1. Process negative class directives.
            [$userClass, $defaultClass] = self::filterNegativeClassDirectives(
                $isUserClassResolvable ? (string)$userClass : '',
                $isDefaultClassResolvable ? (string)$defaultClass : ''
            );

            // 4.2. Resolve combined class list and apply mutual exclusion rules.
            $resolvedClasses = self::resolveClassAttributes(
                $userClass,
                $defaultClass,
                $mutuallyExclusiveClassGroups
            );

            // 4.3. Inject resolved class string back into the appropriate side.
            if ($userAttributes !== null) {
                $userAttributes['class'] = $resolvedClasses;
            } else {
                $defaultAttributes['class'] = $resolvedClasses;
            }
        }

        // 5. Return final merged attributes.
        if ($userAttributes === null) {
            return $defaultAttributes;
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
     * @param ?array<string, mixed> $attributes
     *   An associative array of attributes. The array will be modified in place
     *   by removing the specified pseudo attribute if found. Can be `null`.
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
     * Checks if the given value is a resolvable class attribute.
     *
     * @param mixed $value
     *   The value to check.
     * @return bool
     *   Returns `true` if the value is a string, integer, float, or an object
     *   implementing `Stringable`. Returns `false` otherwise.
     */
    private static function isResolvableClassAttribute(mixed $value): bool
    {
        return \is_string($value)
            || $value instanceof \Stringable
            || \is_int($value)
            || \is_float($value);
    }

    /**
     * Processes negative class directives in a user-defined class attribute
     * string by removing them from the default classes.
     *
     * A negative class is any class prefixed with `-`, e.g., `-btn-primary`.
     * These are removed from the default classes and not included in the result.
     *
     * @param string $userClasses
     *   A space-separated string of user-defined classes including negative
     *   classes. e.g., `-btn-primary btn-darkgreen`.
     * @param string $defaultClasses
     *   A space-separated string of default classes.
     * @return array<int, string>
     *   A tuple containing two strings: the filtered user class string and the
     *   filtered default class string. The user class string contains only
     *   positive classes, while the default class string has negative classes
     *   removed.
     */
    private static function filterNegativeClassDirectives(
        string $userClasses,
        string $defaultClasses
    ): array
    {
        $userClasses = self::parseClassAttribute($userClasses);
        $defaultClasses = self::parseClassAttribute($defaultClasses);
        $negatives = [];
        $positives = [];
        foreach ($userClasses as $class) {
            if (\str_starts_with($class, '-')) {
                $negatives[] = \substr($class, 1);
            } else {
                $positives[] = $class;
            }
        }
        $defaultClasses = \array_values(\array_diff($defaultClasses, $negatives));
        return [
            \implode(' ', $positives),
            \implode(' ', $defaultClasses)
        ];
    }

    /**
     * Resolve classes by merging default classes with user-defined classes,
     * handling duplicates and managing mutually exclusive groups.
     *
     * @param string $userClasses
     *   A space-separated string of user-defined class names (e.g., `'btn-lg
     *   btn-primary'`).
     * @param string $defaultClasses
     *   A space-separated string of default class names defined by the
     *   component (e.g., `'btn btn-primary'`).
     * @param string[] $mutuallyExclusiveClassGroups
     *   An array of space-separated strings representing mutually exclusive
     *   class groups (e.g., `['btn-primary btn-secondary btn-success',
     *   'btn-sm btn-lg']`).
     * @return string
     *   A resolved and merged class string with duplicates removed and mutually
     *   exclusive conflicts resolved.
     */
    private static function resolveClassAttributes(
        string $userClasses,
        string $defaultClasses,
        array $mutuallyExclusiveClassGroups
    ): string
    {
        // 1. Parse the user classes, default classes, and mutually exclusive
        // class groups into arrays. Then, flip the default and user class
        // arrays to swap their keys with their values for efficient lookups.
        $userClasses = \array_flip(self::parseClassAttribute($userClasses));
        $defaultClasses = \array_flip(self::parseClassAttribute($defaultClasses));
        $mutuallyExclusiveClassGroups = \array_map(
            [self::class, 'parseClassAttribute'],
            $mutuallyExclusiveClassGroups
        );

        // 2. Merge default classes and user-defined classes. Default classes
        // are placed first, followed by user-defined classes.
        $mergedClasses = $defaultClasses + $userClasses;

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
