<?php declare(strict_types=1);
/**
 * NavbarToggler.php
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
 * Represents a toggle button for a collapsible section in a navigation bar.
 *
 * @link https://getbootstrap.com/docs/5.3/components/navbar/#toggler
 */
class NavbarToggler extends Button
{
    public function __construct(?array $attributes = null)
    {
        $content = new Generic('span', ['class' => 'navbar-toggler-icon']);
        parent::__construct($attributes, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getDefaultAttributes(): array
    {
        return \array_merge(parent::getDefaultAttributes(), [
            'class' => 'navbar-toggler',
            'data-bs-toggle' => 'collapse',
            'data-bs-target' => '',
            'aria-controls' => '',
            'aria-expanded' => 'false',
            'aria-label' => 'Toggle navigation'
        ]);
    }

    #endregion Component overrides
}
