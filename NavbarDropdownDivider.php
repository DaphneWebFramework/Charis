<?php declare(strict_types=1);
/**
 * NavbarDropdownDivider.php
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
 * Represents a visual divider inside a navbar dropdown menu.
 */
class NavbarDropdownDivider extends Component
{
    /**
     * Constructs a new instance.
     */
    public function __construct()
    {
        $content = new Generic('hr', ['class' => 'dropdown-divider'], null, true);
        parent::__construct(null, $content);
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'li';
    }

    #endregion Component overrides
}
