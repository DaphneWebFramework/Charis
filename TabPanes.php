<?php declare(strict_types=1);
/**
 * TabPanes.php
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
 * Represents a container for tab panes in a tab navigation.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navs-tabs/#javascript-behavior
 */
class TabPanes extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => 'tab-content'];
    }

    #endregion Component overrides
}
