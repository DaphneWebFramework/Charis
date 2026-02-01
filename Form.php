<?php declare(strict_types=1);
/**
 * Form.php
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
 * Represents a form.
 */
class Form extends Component
{
    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'form';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'spellcheck' => 'false',
            'autocorrect' => 'off'
        ];
    }

    #endregion Component overrides
}
