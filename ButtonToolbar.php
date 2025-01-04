<?php declare(strict_types=1);
/**
 * ButtonToolbar.php
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
 * Represents a toolbar for organizing button groups.
 *
 * @link https://getbootstrap.com/docs/5.3/components/button-group/#button-toolbar
 */
class ButtonToolbar extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'class' => 'btn-toolbar',
            'role' => 'toolbar'
        ];
    }

    #endregion Component overrides
}
