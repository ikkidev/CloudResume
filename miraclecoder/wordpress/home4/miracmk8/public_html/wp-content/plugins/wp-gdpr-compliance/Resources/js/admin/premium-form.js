import { ajax, ajaxDataParams } from '../utils/helpers'

/**
 * Admin Component for the premium mode form (Premium)
 */
export default class PremiumModeForm {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcAdmin.pluginPrefix
        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.locale = wpgdprcAdmin.locale
        this.formAction = this.prefix + '_update_premium'
        this.pageWrap = document.querySelector('.wrap.wpgdprc')
        this.header = this.pageWrap ? this.pageWrap.querySelector('.wpgdprc-header') : undefined
        this.switchMode = document.querySelector('.wpgdprc-form__field--pluginmode input')
        this.isLoading = false
        this.isLoadingClassName = 'is-loading'
    }

    init () {
        this.handlePluginMode()
    }

    setIsLoading (isLoading = true) {
        this.isLoading = isLoading

        if (isLoading) {
            document.body.classList.add(this.isLoadingClassName)
        } else {
            document.body.classList.remove(this.isLoadingClassName)
        }
    }

    handlePluginMode () {
        if (!this.switchMode) {
            return
        }

        this.switchMode.addEventListener('change', event => {
            if (this.isLoading) {
                return
            }
            this.updatePremium(event.target.checked)
        })
    }

    async updatePremium (checked = false, userType = null) {
        this.setIsLoading()

        // Do the call
        try {
            await ajax(this.ajaxUrl, {
                action: this.formAction,
                [this.ajaxArg]: this.ajaxNonce,
                locale: this.locale,
                ...ajaxDataParams({
                    checked: checked,
                    userType: userType
                })
            }, 'POST').then(response => {
                return response.json()
            }).then(response => {
                this.setIsLoading(false)
                const success = typeof response.success !== 'undefined' ? response.success : false
                if (!success) {
                    console.dir(response)
                    return
                }

                const premium = typeof response.premium !== 'undefined' ? response.premium : false
                this.pageWrap.setAttribute('data-mode', premium ? 'premium' : 'free')
                if (this.switchMode) {
                    this.switchMode.checked = premium
                }

                const header = typeof response.header !== 'undefined' ? response.header : ''
                if (header && header.length) {
                    this.header.innerHTML = header
                }
            })
        } catch (error) {
            console.error(error)
            this.setIsLoading(false)
        }
    }
}
