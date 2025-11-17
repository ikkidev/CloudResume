/**
 * Admin Component for the request user form (Settings)
 */
export default class RequestUserForm {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.hideClass = 'hidden'
        this.switchField = document.querySelector('[name="wpgdprc_settings_enable_access_request"]')
        this.otherFieldWrappers = document.querySelectorAll('.activate_yes')
    }

    init () {
        if (!this.switchField) {
            return
        }

        this.toggleFields(this.switchField.checked)
        this.switchField.addEventListener('change', event => {
            this.toggleFields(event.target.checked)
        })
    }

    toggleFields (show) {
        if (!this.otherFieldWrappers) {
            return
        }

        if (show) {
            this.otherFieldWrappers.forEach(element => {
                element.classList.remove(this.hideClass)
            })
            return
        }

        this.otherFieldWrappers.forEach(element => {
            if (element.classList.contains(this.hideClass)) {
                return
            }
            element.classList.add(this.hideClass)
        })
    }
}
