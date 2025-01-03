<?php declare(strict_types=1);
/**
 * Button.php
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
 * Represents a button.
 *
 * @link https://getbootstrap.com/docs/5.3/components/buttons/
 */
class Button extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'button';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'type' => 'button',
            'class' => 'btn btn-primary'
        ];
    }

    protected function getMutuallyExclusiveClassAttributeGroups(): array
    {
        return [
            'btn-primary btn-secondary btn-success btn-info btn-warning btn-danger btn-light btn-dark btn-outline-primary btn-outline-secondary btn-outline-success btn-outline-info btn-outline-warning btn-outline-danger btn-outline-light btn-outline-dark btn-link',
            'btn-lg btn-sm'
        ];
    }

    #endregion Component overrides
}
