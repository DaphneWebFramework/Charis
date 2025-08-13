<?php declare(strict_types=1);
/**
 * FormComposite.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormComposites;

use \Charis\Component;
use \Charis\FormControls\FormControl;

/**
 * Abstract base class for form composites, combining multiple components into a
 * cohesive structure.
 */
abstract class FormComposite extends Component
{
    /**
     * Provides the CSS class for the wrapper element.
     *
     * @return string
     *   The CSS class string for the wrapper element.
     */
    abstract protected function getCompositeClassAttribute(): string;

    /**
     * Creates the form control.
     *
     * @param array<string, mixed> $attributes
     *   Base attributes for the form control.
     * @return FormControl
     *   The form control instance.
     */
    abstract protected function createFormControl(array $attributes): FormControl;

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return ['class' => $this->getCompositeClassAttribute()];
    }

    #endregion Component overrides
}
