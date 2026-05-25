import postscribe from './../utils/postscribe/postscribe'
import { _readCookie, partition } from '../utils/helpers'

/**
 * Front Component for loading consents
 */
export default class LoadConsents {
    constructor () {
        if (typeof postscribe === 'undefined') {
            return
        }

        const consents = typeof wpgdprcFront.consents !== 'undefined' ? wpgdprcFront.consents : []
        const [required, optional] = partition(consents, (consent) => consent.required)

        // ALWAYS load all the required consents.
        required.forEach(consent => this.loadConsent(consent))

        const consentCookie = _readCookie(wpgdprcFront.cookieName)
        if (consentCookie === undefined || consentCookie === null) {
            return
        }

        const cookieValue = this._parseConsentCookie(consentCookie)

        optional.forEach((consent) => {
            if (cookieValue === true || (typeof cookieValue[consent.ID] !== 'undefined' && cookieValue[consent.ID] !== 'declined')) {
                this.loadConsent(consent)
            }
        })
    }

    /**
     * Parse the cookie into usable values.
     * @param cookieValue
     * @private
     */
    _parseConsentCookie (cookieValue) {
        if (cookieValue === 'accept') {
            return true
        }

        const entries = cookieValue.split(',')
        return Object.fromEntries(entries.map(entry => entry.split('_')))
    }

    /**
     * @param placement
     * @returns {HTMLHeadElement | Element | string | HTMLElement}
     * @private
     */
    getTargetByPlacement (placement) {
        let output

        switch (placement) {
        case 'head':
            output = document.head
            break
        case 'body' :
            output = document.querySelector('#wpgdprc-consent-body')
            if (output === null) {
                const bodyElement = document.createElement('div')
                bodyElement.id = 'wpgdprc-consent-body'
                document.body.prepend(bodyElement)
                output = '#' + bodyElement.id
            }
            break
        case 'footer' :
            output = document.body
            break
        }
        return output
    }

    /**
     * @param consent
     */
    loadConsent (consent) {
        const target = this.getTargetByPlacement(consent.placement)
        if (target !== null) {
            postscribe(target, consent.content)
        }
    }
}
