<?php declare(strict_types=1);
/**
 * Utility.php
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
 * Contains utility functions intended for internal use by the `Component` class
 * and its subclasses.
 */
trait Utility
{
    /**
     * Merges user-provided attributes with default attributes, producing a
     * final set of HTML attributes suitable for rendering.
     *
     * If the `class` attribute is present, its values from both sources are
     * combined. When a mutually exclusive class group is defined (e.g.,
     * `'btn-primary btn-secondary btn-success'`), only one class from the group
     * will be retained. User-supplied negative class directives (e.g.,
     * `-btn-primary`) remove matching class names from the default list.
     * Additionally, if the user provides `false` as the class value, the entire
     * `class` attribute is removed from the result, overriding any default.
     *
     * @param ?array<string, mixed> $userAttributes
     *   Attributes provided by the user. Can be `null`.
     * @param array<string, mixed> $defaultAttributes
     *   Default attributes defined by the component.
     * @param array<int, string> $mutuallyExclusiveClassGroups
     *   (Optional) Mutually exclusive class groups to resolve conflicts. Each
     *   group is a space-separated class names where only one class should
     *   survive. Defaults to an empty array.
     * @return array<string, mixed>
     *   Final resolved attributes, suitable for rendering.
     */
    protected function mergeAttributes(
        ?array $userAttributes,
        array $defaultAttributes,
        array $mutuallyExclusiveClassGroups = []
    ): array
    {
        static $classKey = 'class';

        // 1. Determine whether the `class` attribute exists in either side.
        $hasUserClass = $userAttributes !== null &&
            \array_key_exists($classKey, $userAttributes);
        $hasDefaultClass =
            \array_key_exists($classKey, $defaultAttributes);

        // 2. If neither side has a `class` attribute, merge the two arrays.
        if (!$hasUserClass && !$hasDefaultClass) {
            goto Merge;
        }

        // 3. Extract class values.
        $userClass = $hasUserClass
            ? $userAttributes[$classKey]
            : null;
        $defaultClass = $hasDefaultClass
            ? $defaultAttributes[$classKey]
            : null;

        // 4. If user sets `class=true` or `class=false`, remove `class` from
        // default attributes and preserve the boolean for the renderer.
        if (\is_bool($userClass)) {
            if ($hasDefaultClass) {
                unset($defaultAttributes[$classKey]);
            }
            goto Merge;
        }

        // 5. Check whether either class value is resolvable (string-like).
        $isUserClassResolvable = $this->isResolvableClassAttribute($userClass);
        $isDefaultClassResolvable = $this->isResolvableClassAttribute($defaultClass);
        if ($isUserClassResolvable || $isDefaultClassResolvable)
        {
            // 5.1. Process negative class directives.
            [$userClass, $defaultClass] = $this->filterNegativeClassDirectives(
                $isUserClassResolvable ? (string)$userClass : '',
                $isDefaultClassResolvable ? (string)$defaultClass : ''
            );

            // 5.2. Resolve combined class list and apply mutual exclusion rules.
            $resolvedClasses = $this->resolveClassAttributes(
                $userClass,
                $defaultClass,
                $mutuallyExclusiveClassGroups
            );

            // 5.3. Inject resolved class string back into the appropriate side.
            if ($userAttributes !== null) {
                $userAttributes[$classKey] = $resolvedClasses;
            } else {
                $defaultAttributes[$classKey] = $resolvedClasses;
            }
        }

    Merge:
        // 6. Return final merged attributes.
        return $userAttributes === null
            ? $defaultAttributes
            : \array_merge($defaultAttributes, $userAttributes);
    }

    /**
     * Merges two `class` attribute strings into a single string with duplicates
     * removed.
     *
     * This method does not support negative class directives.
     *
     * @param string $classes1
     *   A space-separated string of class names.
     * @param string $classes2
     *   Another space-separated string of class names.
     * @return string
     *   A space-separated string containing all unique class names.
     */
    protected function combineClassAttributes(
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
     * Returns and removes a pseudo attribute from the provided attributes
     * array.
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
     *   The value of the pseudo attribute, or the default value if not found.
     */
    protected function consumePseudoAttribute(
        ?array &$attributes,
        string $key,
        mixed $defaultValue = null
    ): mixed
    {
        // Pseudo attributes must start with `:` and can only contain
        // alphanumeric characters and hyphens (`-`). The first character
        // after the `:` must be an alphabetic character.
        static $pseudoAttributeNamePattern = '/^:[a-zA-Z][a-zA-Z0-9\-]*$/';

        if ($attributes === null
         || !\preg_match($pseudoAttributeNamePattern, $key)
         || !\array_key_exists($key, $attributes))
        {
            return $defaultValue;
        }
        $value = $attributes[$key];
        unset($attributes[$key]);
        return $value;
    }

    /**
     * Returns and removes all scoped pseudo attributes from the provided
     * attributes array.
     *
     * Scoped pseudo attributes use the syntax `:<scope>:<name>`, such as
     * `:div:role`, where `div` is the scope and `role` is the attribute name.
     * All matching attributes are extracted and returned in a new associative
     * array with the prefix (e.g., `:div:`) removed from each key.
     *
     * This method is useful in composite components that encapsulate internal
     * HTML elements, allowing callers to assign attributes to those elements
     * without requiring the component to explicitly support every possible
     * pseudo attribute that may need to be forwarded.
     *
     * @param ?array<string, mixed> $attributes
     *   An associative array of attributes. The array will be modified in place
     *   by removing the matching scoped pseudo attributes. Can be `null`.
     * @param string $scope
     *   The scope used to extract attributes (e.g., `div` matches `:div:*`).
     *   Must not include colons.
     * @return array<string, mixed>
     *   An associative array of scoped pseudo attributes, with the prefix
     *   (e.g., `:div:`) removed from each key.
     */
    protected function consumeScopedPseudoAttributes(
        ?array &$attributes,
        string $scope
    ): array
    {
        if ($attributes === null) {
            return [];
        }
        $result = [];
        $scope = ":$scope:";
        $scopeLength = \strlen($scope);
        foreach ($attributes as $name => $value) {
            if (\strncmp($name, $scope, $scopeLength) !== 0) {
                continue;
            }
            $attribute = \substr($name, $scopeLength);
            if ($attribute === '') { // e.g. ":div:"
                continue;
            }
            $result[$attribute] = $value;
            unset($attributes[$name]);
        }
        return $result;
    }

    /**
     * Determines whether a value can be interpreted as a valid `class`
     * attribute string.
     *
     * @param mixed $value
     *   The value to check.
     * @return bool
     *   Returns `true` if the value is a string, an object implementing
     *   `Stringable`, an integer, or a float. Returns `false` otherwise.
     */
    protected function isResolvableClassAttribute(mixed $value): bool
    {
        return \is_string($value)
            || $value instanceof \Stringable
            || \is_int($value)
            || \is_float($value);
    }

    /**
     * Processes negative class directives in a user-defined `class` attribute
     * string by removing matching class names from the default list.
     *
     * A negative class directive is any class name prefixed with `-`, e.g.
     * `-btn-primary`. These are excluded from the final output and removed from
     * the default class names.
     *
     * @param string $userClasses
     *   A space-separated string of user-defined class names, which may include
     *   negative directives.
     * @param string $defaultClasses
     *   A space-separated string of default class names.
     * @return array<int, string>
     *   A two-element array where the first element is the filtered user class
     *   string (without negatives), and the second element is the default class
     *   string with matching negatives removed.
     */
    protected function filterNegativeClassDirectives(
        string $userClasses,
        string $defaultClasses
    ): array
    {
        $userClasses = $this->parseClassAttribute($userClasses);
        $defaultClasses = $this->parseClassAttribute($defaultClasses);
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
     * Resolves class names by merging default and user-defined class lists,
     * removing duplicates and enforcing mutually exclusive groups.
     *
     * @param string $userClasses
     *   A space-separated string of user-defined class names (e.g., `'btn-lg
     *   btn-primary'`).
     * @param string $defaultClasses
     *   A space-separated string of default class names (e.g., `'btn
     *   btn-primary'`).
     * @param string[] $mutuallyExclusiveClassGroups
     *   An array of space-separated class name groups (e.g., `['btn-primary
     *   btn-secondary btn-success', 'btn-sm btn-lg']`).
     * @return string
     *   A space-separated string of resolved class names.
     */
    protected function resolveClassAttributes(
        string $userClasses,
        string $defaultClasses,
        array $mutuallyExclusiveClassGroups
    ): string
    {
        // 1. Parse the user classes, default classes, and mutually exclusive
        // class groups into arrays. Then, flip the default and user class
        // arrays to swap their keys with their values for efficient lookups.
        $userClasses = \array_flip($this->parseClassAttribute($userClasses));
        $defaultClasses = \array_flip($this->parseClassAttribute($defaultClasses));
        $mutuallyExclusiveClassGroups = \array_map(
            [$this, 'parseClassAttribute'],
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

    /**
     * Parses a space-separated `class` attribute string into individual class
     * names.
     *
     * @param string $classes
     *   A space-separated string of class names.
     * @return string[]
     *   An array of class names. If the input is empty or contains only
     *   whitespace, an empty array is returned.
     */
    protected function parseClassAttribute(string $classes): array
    {
        $classes = \trim($classes);
        if ($classes === '') {
            return [];
        }
        return \explode(' ', \preg_replace('/\s+/', ' ', $classes));
    }
}
