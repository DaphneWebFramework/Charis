<?php declare(strict_types=1);
/**
 * NavbarNav.php
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
 * Represents a group of navigation items in a navigation bar.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navbar/#nav
 */
class NavbarNav extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'ul';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => 'navbar-nav'];
    }

    #endregion Component overrides
}
