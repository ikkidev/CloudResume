/**
 * Admin Component for the privacy policy form (Settings)
 */
export default class PrivacyPolicyForm {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcAdmin.pluginPrefix
        this.switchField = document.querySelector('[name="' + this.prefix + '_settings_enable_privacy_policy_extern"]')
        this.pagePicker = document.querySelector('fieldset#external_no')
        this.urlInput = document.querySelector('fieldset#external_yes')
    }

    init () {
        this.handleSwitch()
    }

    handleSwitch () {
        if (!this.switchField) {
            return
        }

        this.toggleFields(this.switchField.checked)

        this.switchField.addEventListener('change', event => {
            this.toggleFields(event.target.checked)
        })
    }

    toggleFields (external) {
        if (!this.pagePicker || !this.urlInput) {
            return
        }

        const hideClass = 'hidden'

        if (external) {
            if (!this.pagePicker.classList.contains(hideClass)) {
                this.pagePicker.classList.add(hideClass)
            }
            this.urlInput.classList.remove(hideClass)
            return
        }

        if (!this.urlInput.classList.contains(hideClass)) {
            this.urlInput.classList.add(hideClass)
        }
        this.pagePicker.classList.remove(hideClass)
    }
}
