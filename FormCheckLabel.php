<?php declare(strict_types=1);
/**
 * FormCheckLabel.php
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
 * Represents a `<label>` element styled for use with Bootstrap check, radio, or
 * switch inputs.
 */
class FormCheckLabel extends FormLabel
{
    protected function getDefaultAttributes(): array
    {
        return \array_merge(parent::getDefaultAttributes(), [
            'class' => 'form-check-label'
        ]);
    }
}
