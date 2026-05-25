import { _getValuesByCheckedBoxes, ajax, ajaxDataParams } from '../utils/helpers'

/**
 * Front Component for delete request form
 */
export default class FormDeleteRequest {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcFront.pluginPrefix
        this.ajaxUrl = wpgdprcFront.ajaxUrl
        this.ajaxNonce = wpgdprcFront.ajaxNonce
        this.ajaxArg = wpgdprcFront.ajaxArg
        this.token = wpgdprcFront.token
        this.forms = document.querySelectorAll('.wpgdprc-form--delete-request')
        this.statusErrorClassName = 'wpgdprc-status--error'
        this.statusProcessingClassName = 'wpgdprc-status--processing'
        this.statusRemovedClassName = 'wpgdprc-status--removed'
        this.messageErrorClassName = 'wpgdprc-message--error'
        this.isLoading = false
        this.isLoadingClassName = 'is-loading'
        this.isHiddenClassName = 'is-hidden'
    }

    init () {
        this.handleForm()
    }

    handleForm () {
        if (!this.forms) {
            return
        }

        this.forms.forEach(form => {
            this.handleFormSubmit(form)
            this.handleSelectAllCheckboxes(form)
        })
    }

    handleSelectAllCheckboxes (form) {
        const selectAll = form.querySelector('.wpgdprc-select-all')

        if (!form || !selectAll) {
            return
        }

        const checkboxes = form.querySelectorAll('.wpgdprc-checkbox')

        checkboxes.forEach(elem => {
            elem.addEventListener('change', event => {
                if (elem.checked === false) {
                    selectAll.checked = false
                    return
                }
                selectAll.checked = true
                checkboxes.forEach(elem => {
                    if (elem.checked === false) {
                        selectAll.checked = false
                    }
                })
            })
        })

        selectAll.addEventListener('change', event => {
            const target = event.target
            const checked = target.checked

            checkboxes.forEach(e => {
                e.checked = checked
            })
        })
    }

    handleFormSubmit (form) {
        if (!form) {
            return
        }

        form.addEventListener('submit', event => {
            event.preventDefault()
            const selectAll = form.querySelector('.wpgdprc-select-all')
            const checkboxes = form.querySelectorAll('.wpgdprc-checkbox')
            const values = _getValuesByCheckedBoxes(checkboxes)
            selectAll.checked = false
            this.deleteRequest(form, values)
        })
    }

    setIsLoading (isLoading = true) {
        this.isLoading = isLoading

        if (isLoading) {
            document.body.classList.add(this.isLoadingClassName)
        } else {
            document.body.classList.remove(this.isLoadingClassName)
        }
    }

    /**
     * Delete Request - Delete single and multiple selected checkbox values.
     * @param form
     * @param values
     * @param delay
     * @returns {Promise<void>}
     */
    async deleteRequest (form, values = [], delay = 0) {
        if (!form || !values) {
            return
        }

        const formWpgdprc = form.getAttribute('data-wpgdprc')
        const feedback = form.querySelector('.wpgdprc-message')
        const value = values.slice(0, 1)

        if (!value.length) {
            return
        }

        const row = form.querySelector('tr[data-id="' + value[0] + '"]')
        row.classList.remove(this.statusErrorClassName)
        row.classList.add(this.statusProcessingClassName)
        feedback.classList.add(this.isHiddenClassName)
        feedback.classList.remove(this.messageErrorClassName)
        feedback.innerHTML = ''

        this.setIsLoading()

        setTimeout(async () => {
            // Do the call
            try {
                await ajax(this.ajaxUrl, {
                    action: this.prefix + '_process_action',
                    [this.ajaxArg]: this.ajaxNonce,
                    ...ajaxDataParams({
                        type: 'delete_request',
                        token: this.token,
                        settings: JSON.parse(formWpgdprc),
                        value: value[0]
                    })
                }, 'POST').then(response => {
                    return response.json()
                }).then(response => {
                    this.setIsLoading(false)
                    const message = (typeof response.message !== 'undefined') ? response.message : false
                    const error = (typeof response.error !== 'undefined') ? response.error : false

                    if (message) {
                        values.splice(0, 1)
                        row.querySelector('input[type="checkbox"]').remove()
                        row.classList.add(this.statusRemovedClassName)
                        feedback.innerHTML = message
                        feedback.classList.remove(this.isHiddenClassName)

                        // Execute delete request as long as there are values
                        if (values.length) {
                            this.deleteRequest(form, values, 500)
                        }
                    }

                    if (error) {
                        row.classList.add(this.statusErrorClassName)
                        feedback.innerHTML = error
                        feedback.classList.add(this.messageErrorClassName)
                        feedback.classList.remove(this.isHiddenClassName)
                    }
                })
            } catch (error) {
                console.error(error)
                this.setIsLoading(false)
            }
        }, (delay || 0))
    }
}
