import { ajax } from '../utils/helpers'

/**
 * Admin Component for the dashboard welcome message
 */
export default class Message {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcAdmin.pluginPrefix
        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.messages = document.querySelectorAll('.wpgdprc-message')
        this.isLoading = false
        this.isLoadingClassName = 'is-loading'
        this.fadeOutClassName = 'fade-out'
    }

    init () {
        this.handleButtonClose()
    }

    handleButtonClose () {
        if (!this.messages) {
            return
        }

        this.messages.forEach(message => {
            const button = message.querySelector('.wpgdprc-message__button--close')

            if (!button) {
                return
            }

            button.addEventListener('click', event => {
                event.preventDefault()
                this.removeMessage(message)
            })

            button.addEventListener('keydown', event => {
                if (event.keyCode === 13) {
                    button.click()
                }
            })
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

    async removeMessage (message) {
        this.setIsLoading()
        message.classList.add(this.fadeOutClassName)

        // Do the call
        try {
            await ajax(this.ajaxUrl, {
                action: this.prefix + '_hide_welcome',
                [this.ajaxArg]: this.ajaxNonce
            }, 'POST').then(response => {
                return response.json()
            }).then(() => { // response
                this.setIsLoading(false)
                setTimeout(() => {
                    if (message.parentNode) {
                        message.parentNode.removeChild(message)
                    }
                }, 250)
            })
        } catch (error) {
            console.error(error)
            this.setIsLoading(false)
        }
    }
}
