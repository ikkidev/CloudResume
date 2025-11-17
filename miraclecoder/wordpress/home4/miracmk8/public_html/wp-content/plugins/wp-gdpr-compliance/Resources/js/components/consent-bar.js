import { _readCookie } from '../utils/helpers'
import { _setConsentCookie } from './consent-cookie'

/**
 * Front Component for consent bar
 */
export default class ConsentBar {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.cookieName = wpgdprcFront.cookieName
        this.consentBar = document.querySelector('.wpgdprc-consent-bar')
        this.buttonAccept = document.querySelector('.wpgdprc-consent-bar .wpgdprc-button--accept')
        this.isLoading = false
    }

    init () {
        if (!this.consentBar) {
            return
        }

        const consentCookie = _readCookie(this.cookieName)
        if (consentCookie !== null) {
            // Hide consent bar if cookie already set
            this.consentBar.style.display = 'none'
            return
        }

        // Move consent bar to the be the first element in the <body>
        const body = document.querySelector('body')
        body.prepend(this.consentBar)

        // Show bar
        this.consentBar.style.display = 'block'
        this.handleButtonAccept()
    }

    handleButtonAccept () {
        if (!this.buttonAccept) {
            return
        }

        this.buttonAccept.addEventListener('click', event => {
            event.preventDefault()
            if (this.isLoading) {
                return
            }
            this.isLoading = _setConsentCookie('all')
        })
    }
}
