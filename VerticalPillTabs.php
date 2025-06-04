<?php declare(strict_types=1);
/**
 * VerticalPillTabs.php
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
 * Represents a container for vertically oriented tab items in a pill-based tab
 * navigation.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navs-tabs/#javascript-behavior
 */
class VerticalPillTabs extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'class' => 'nav nav-pills flex-column me-3',
            'role' => 'tablist',
            'aria-orientation' => 'vertical'
        ];
    }

    #endregion Component overrides
}
