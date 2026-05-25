import { ajax, ajaxDataParams } from '../utils/helpers'

/**
 * Admin Component for the integrations form (Settings)
 */
export default class IntegrationsForm {
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
        this.formAction = this.prefix + '_update_integration'
        this.wrapper = document.querySelector('.wpgdprc-integrations')
        this.isHiddenClassName = 'is-hidden'
    }

    init () {
        this.handleUpdate()
    }

    handleUpdate () {
        if (!this.wrapper) {
            return
        }

        // handle input updates
        const fields = this.wrapper.querySelectorAll('input[type="text"], input[type="checkbox"]')
        fields.forEach(field => {
            field.addEventListener('change', event => {
                event.target.disabled = true

                const type = field.getAttribute('type')
                const value = type !== 'checkbox' ? event.target.value : event.target.checked
                this.updateIntegration(value, event.target.getAttribute('name'), type, event?.target?.dataset?.integration).then(() => {
                    event.target.disabled = false
                })
            })
        })

        // handle button clicking (and switching between enabled/disabled state)
        const buttons = this.wrapper.querySelectorAll('input[type="submit"]')
        buttons.forEach(button => {
            const section = button.getAttribute('data-type')
            const parent = this.wrapper.querySelector('.wpgdprc-integration-item--' + section)
            const enabledElements = parent.querySelectorAll('.wpgdprc-label, [data-action="manage"]')
            const manageButton = parent.querySelector('[data-action="manage"]')
            const disabledElement = parent.querySelector('[data-enable="1"]')

            button.addEventListener('click', event => {
                event.preventDefault()

                event.target.disabled = true

                const value = event.target.getAttribute('data-enable') === '1'
                this.updateIntegration(value, event.target.getAttribute('name'), 'button', event?.target?.dataset?.type).then(() => {
                    event.target.disabled = false
                })

                if (value) {
                    disabledElement.classList.add(this.isHiddenClassName)
                    enabledElements.forEach(element => {
                        element.classList.remove(this.isHiddenClassName)
                    })
                    // Trigger a click for the manage button so the integration container is expanded
                    manageButton.click()
                } else {
                    disabledElement.classList.remove(this.isHiddenClassName)
                    enabledElements.forEach(element => {
                        element.classList.add(this.isHiddenClassName)
                    })
                }
            })
        })
    }

    async updateIntegration (value, name, type, integration) {
        // Do the call
        try {
            await ajax(this.ajaxUrl, {
                action: this.formAction,
                [this.ajaxArg]: this.ajaxNonce,
                locale: this.locale,
                ...ajaxDataParams({
                    value: value,
                    name: name,
                    type: type,
                    integration: integration
                })
            }, 'POST').then(response => {
                return response.json()
            }).then(response => {
                const success = typeof response.success !== 'undefined' ? response.success : false
                if (!success) {
                    console.dir(response)
                    return
                }

                // @TODO show feedback that setting is updated
                console.dir(response)
            })
        } catch (error) {
            console.error(error)
        }
    }
}
