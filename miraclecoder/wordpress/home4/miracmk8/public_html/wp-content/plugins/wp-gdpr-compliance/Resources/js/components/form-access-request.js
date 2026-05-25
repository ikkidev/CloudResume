import { ajax, ajaxDataParams } from '../utils/helpers'

/**
 * Front Component for access request form
 */
export default class FormAccessRequest {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcFront.pluginPrefix
        this.ajaxUrl = wpgdprcFront.ajaxUrl
        this.ajaxNonce = wpgdprcFront.ajaxNonce
        this.ajaxArg = wpgdprcFront.ajaxArg
        this.successClassName = 'wpgdprc-message--success'
        this.errorClassName = 'wpgdprc-message--error'
        this.formAction = this.prefix + '_process_action'
        this.form = document.querySelector('.wpgdprc-form--access-request')
        this.isLoading = false
        this.isLoadingClassName = 'is-loading'
    }

    init () {
        this.handleForm()
    }

    handleForm () {
        if (!this.form) {
            return
        }

        this.form.addEventListener('submit', event => {
            event.preventDefault()
            this.accessRequest()
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

    async accessRequest () {
        if (this.isLoading) {
            return
        }

        this.setIsLoading()

        const feedback = this.form.querySelector('.wpgdprc-message')
        const consent = this.form.querySelector('#wpgdprc-form__consent')
        const emailAddress = this.form.querySelector('#wpgdprc-form__email')

        feedback.style.display = 'none'
        feedback.classList.remove(this.successClassName, this.errorClassName)
        feedback.innerHTML = ''

        // Do the call
        try {
            await ajax(this.ajaxUrl, {
                action: this.formAction,
                [this.ajaxArg]: this.ajaxNonce,
                ...ajaxDataParams({
                    type: 'access_request',
                    email: emailAddress.value,
                    consent: consent.checked
                })
            }, 'POST').then(response => {
                return response.json()
            }).then(response => {
                this.setIsLoading(false)
                const message = typeof response.message !== 'undefined' ? response.message : false
                if (message) {
                    this.form.reset()
                    emailAddress.blur()
                    feedback.innerHTML = message
                    feedback.classList.add(this.successClassName)
                    feedback.removeAttribute('style')
                }

                const error = typeof response.error !== 'undefined' ? response.error : false
                if (error) {
                    emailAddress.focus()
                    feedback.innerHTML = error
                    feedback.classList.add(this.errorClassName)
                    feedback.removeAttribute('style')
                }
            })
        } catch (error) {
            console.error(error)
            this.setIsLoading(false)
        }
    }
}
