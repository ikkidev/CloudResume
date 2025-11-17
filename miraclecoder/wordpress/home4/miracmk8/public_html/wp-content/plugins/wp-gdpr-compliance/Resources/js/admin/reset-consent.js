import { ajax } from '../utils/helpers'

/**
 * Admin Component for the resetting cookie/consent
 */
export default class ResetConsent {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcAdmin.pluginPrefix
        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.button = document.querySelector('[data-action="reset-consent"]')
        this.tileTextClassName = 'wpgdprc-tile__text'
        this.tileTextSuccessClassName = 'wpgdprc-tile__text--success'
        this.tileTextWarningClassName = 'wpgdprc-tile__text--warning'
        this.isLoading = false
        this.isLoadingClassName = 'is-loading'
    }

    init () {
        this.handleClickResetButton()
    }

    handleClickResetButton () {
        if (!this.button) {
            return
        }

        this.button.addEventListener('click', event => {
            event.preventDefault()
            if (this.isLoading) {
                return
            }
            this.resetConsent()
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

    async resetConsent () {
        this.setIsLoading()

        // Do the call
        try {
            await ajax(this.ajaxUrl, {
                action: this.prefix + '_reset_consent',
                [this.ajaxArg]: this.ajaxNonce
            }, 'POST').then(response => {
                return response.json()
            }).then(response => {
                this.setIsLoading(false)
                const success = typeof response.success !== 'undefined' ? response.success : false
                const tileTextVariation = success ? this.tileTextSuccessClassName : this.tileTextWarningClassName

                const message = document.createElement('p')
                message.setAttribute('class', this.tileTextClassName + ' ' + tileTextVariation)
                message.innerHTML = response.message
                this.button.outerHTML = message.outerHTML
            })
        } catch (error) {
            console.error(error)
            this.setIsLoading(false)
        }
    }
}
