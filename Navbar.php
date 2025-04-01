<?php declare(strict_types=1);
/**
 * Navbar.php
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
 * Represents a navigation bar.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navbar/
 */
class Navbar extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'nav';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'class' => 'navbar bg-body-tertiary'
        ];
    }

    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [
            'navbar-expand navbar-expand-sm navbar-expand-md navbar-expand-lg navbar-expand-xl navbar-expand-xxl',
            'bg-body-tertiary bg-primary bg-secondary bg-success bg-info bg-warning bg-danger bg-light bg-dark'
        ];
    }

    #endregion Component overrides
}
