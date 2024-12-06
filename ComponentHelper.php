<?php declare(strict_types=1);
/**
 * ComponentHelper.php
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
 * Provides helper functions for component classes.
 */
class ComponentHelper
{
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
            $userAttributes['class'] = self::ResolveClasses(
                $defaultAttributes['class'] ?? '',
                $userAttributes['class'] ?? '',
                $mutuallyExclusiveClassGroups
            );
        }
        return \array_merge($defaultAttributes, $userAttributes);
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
    public static function ResolveClasses(
        string $defaultClasses,
        string $userClasses,
        array $mutuallyExclusiveClassGroups = []
        ): string
    {
        // 1. Parse the default classes, user classes, and mutually exclusive
        // class groups into arrays. Then, flip the default and user class
        // arrays to swap their keys with their values for efficient lookups.
        $defaultClasses = \array_flip(self::ParseClassList($defaultClasses));
        $userClasses = \array_flip(self::ParseClassList($userClasses));
        $mutuallyExclusiveClassGroups = \array_map([self::class, 'ParseClassList'],
            $mutuallyExclusiveClassGroups);
        // 2. Merge the user-defined classes and default classes. User classes
        // take precedence.
        $mergedClasses = $userClasses + $defaultClasses;
        // 3. Resolve conflicts in mutually exclusive class groups.
        foreach ($mutuallyExclusiveClassGroups as $group) {
            // 3.1. Flip the mutually exclusive class group array to create a
            // mapping of class names to keys for quick lookups.
            $group = \array_flip($group);
            // 3.2. Find classes that are both in the mutually exclusive class
            // group and in the merged classes.
            $conflictingClasses = \array_intersect_key($group, $mergedClasses);
            // 3.3. If more than one conflicting class is present, resolve the
            // conflict.
            if (count($conflictingClasses) > 1) {
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
     * Parses a space-separated class string into an array of individual class
     * names, normalizing spaces and trimming extra whitespace.
     *
     * @param string $classes
     *   A space-separated string of class names (e.g., 'btn btn-primary').
     * @return string[]
     *   An array of class names. If the input string is empty or consists only
     *   of whitespace, an empty array is returned.
     */
    public static function ParseClassList(string $classes): array
    {
        $classes = \trim($classes);
        if ($classes === '') {
            return [];
        }
        return \explode(' ', \preg_replace('/\s+/', ' ', $classes));
    }
}
