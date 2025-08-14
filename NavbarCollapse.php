<?php declare(strict_types=1);
/**
 * NavbarCollapse.php
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
 * Represents the collapsible section of a navigation bar.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navbar/#toggler
 */
class NavbarCollapse extends Collapse
{
    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return $this->mergeAttributes(
            [
                'class' => 'navbar-collapse'
            ],
            parent::getDefaultAttributes(),
            parent::getMutuallyExclusiveClassAttributeGroups()
        );
    }

    #endregion Component overrides
}
