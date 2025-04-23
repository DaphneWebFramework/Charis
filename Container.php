<?php declare(strict_types=1);
/**
 * Container.php
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
 * Represents a layout container.
 *
 * @link https://getbootstrap.com/docs/5.3/layout/containers/
 */
class Container extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => 'container'];
    }

    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [
            'container container-sm container-md container-lg container-xl container-xxl container-fluid'
        ];
    }

    #endregion Component overrides
}
