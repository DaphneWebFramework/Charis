<?php declare(strict_types=1);
/**
 * Modal.php
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
 * Represents a modal dialog.
 *
 * Aside from HTML attributes that apply to the wrapper `<div class="modal">`
 * element, this component supports the following pseudo attributes in its
 * constructor:
 *
 * - `:title` (string): The text content of the modal title. Defaults to an
 *   empty string.
 * - `:body` (mixed): The content of the modal body. Can be a string, a
 *   `Component` instance, or an array of components. Defaults to an empty
 *   string.
 * - `:secondary-button-label` (string): The label for the secondary footer
 *   button. Defaults to "Close".
 * - `:primary-button-label` (string): The label for the primary footer button.
 *   Defaults to "Save changes".
 * - `:footer` (mixed): The entire content of the footer. When provided,
 *   completely overrides the default secondary/primary buttons. Can be a
 *   string, a `Component` instance, or an array of components.
 * - `:dialog:*` (mixed): Additional HTML attributes forwarded to the internal
 *   `<div class="modal-dialog">` element.
 *
 * @link https://getbootstrap.com/docs/5.3/components/modal/
 */
class Modal extends Component
{
    /**
     * Constructs a new instance.
     *
     * @param ?array<string, mixed> $attributes
     *   (Optional) An associative array where standard HTML attributes apply to
     *   the wrapper element, and pseudo attributes configure internal structure.
     *   Pass `null` or an empty array to indicate no attributes. Defaults to
     *   `null`.
     */
    public function __construct(?array $attributes = null)
    {
        // 1
        $title = $this->consumePseudoAttribute($attributes, ':title', '');
        $body = $this->consumePseudoAttribute($attributes, ':body', '');
        $secondaryButtonLabel = $this->consumePseudoAttribute(
            $attributes,
            ':secondary-button-label',
            'Close'
        );
        $primaryButtonLabel = $this->consumePseudoAttribute(
            $attributes,
            ':primary-button-label',
            'Save changes'
        );
        $footer = $this->consumePseudoAttribute($attributes, ':footer', [
            new Button([
                'class' => 'btn-secondary',
                'data-bs-dismiss' => 'modal'
            ], $secondaryButtonLabel),
            new Button(null, $primaryButtonLabel)
        ]);
        // 2
        $modalDialogAttributes = $this->mergeAttributes(
            $this->consumeScopedPseudoAttributes($attributes, 'dialog'),
            ['class' => 'modal-dialog']
        );
        // 3
        $modalTitleId = 'modal-title-' . \uniqid();
        $attributes['aria-labelledby'] = $modalTitleId;
        // 4
        parent::__construct(
            $attributes,
            new Generic('div', $modalDialogAttributes,
                new Generic('div', ['class' => 'modal-content'], [
                    new Generic('div', ['class' => 'modal-header'], [
                        new Generic('h5', [
                            'class' => 'modal-title',
                            'id' => $modalTitleId
                        ], $title),
                        new Generic('button', [
                            'type' => 'button',
                            'class' => 'btn-close',
                            'data-bs-dismiss' => 'modal',
                            'aria-label' => 'Close'
                        ])
                    ]),
                    new Generic('div', ['class' => 'modal-body'], $body),
                    new Generic('div', ['class' => 'modal-footer'], $footer)
                ])
            )
        );
    }

    #region Component overrides ------------------------------------------------

    protected function getTagName(): string
    {
        return 'div';
    }

    protected function getDefaultAttributes(): array
    {
        return [
            'class' => 'modal',
            'aria-hidden' => 'true',
            'aria-labelledby' => '',
            'tabindex' => '-1'
        ];
    }

    #endregion Component overrides
}
