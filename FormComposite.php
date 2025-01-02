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

namespace Charis;

/**
 * Abstract base class for composite form components, such as `FormStandardComposite`,
 * `FormFloatingLabelComposite`, and `FormCheckableComposite`.
 *
 * @codeCoverageIgnore
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
     * Creates the form input component.
     *
     * @param array<string, bool|int|float|string> $attributes
     *   Base attributes for the input component.
     * @return FormInput
     *   The form input component instance.
     */
    abstract protected function createFormInputComponent(array $attributes): FormInput;

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
