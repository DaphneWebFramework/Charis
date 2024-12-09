<?php declare(strict_types=1);
/**
 * FormInput.php
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
 * Represents an `<input>` element styled as a Bootstrap form control.
 */
class FormInput extends FormControl
{
    protected function getTagName(): string
    {
        return 'input';
    }

    protected function isSelfClosing(): bool
    {
        return true;
    }
}
