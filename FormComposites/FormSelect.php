<?php declare(strict_types=1);
/**
 * FormSelect.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace Charis\FormComposites;

use \Charis\FormControls\FormControl;
use \Charis\FormControls\FormSelectControl;
use \Charis\Option; // for the correct types in the generated documents

/**
 * Represents a select with a label and optional help text.
 */
class FormSelect extends FormStandardComposite
{
    /** @var Option[]|null */
    private readonly ?array $options;

    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array where standard HTML attributes apply to
     *   the wrapper element, and pseudo attributes configure inner components.
     *   Pass `null` or an empty array to indicate no attributes. Defaults to
     *   `null`.
     * @param Option[]|null $options
     *   (Optional) An array of options to render inside the select control.
     *   Defaults to `null`.
     */
    public function __construct(array $attributes = null, array $options = null)
    {
        $this->options = $options;
        parent::__construct($attributes);
    }

    #region FormComposite overrides --------------------------------------------

    protected function createFormControl(array $attributes): FormControl
    {
        return new FormSelectControl($attributes, $this->options);
    }

    #endregion FormComposite overrides
}
