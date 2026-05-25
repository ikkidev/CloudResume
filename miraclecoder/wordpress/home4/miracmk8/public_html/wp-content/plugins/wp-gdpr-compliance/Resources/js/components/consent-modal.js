import MicroModal from 'micromodal'
import { trapFocus } from '../utils/helpers'
import { _setConsentCookie } from './consent-cookie'

/**
 * Front Component for consent modal
 */
export default class ConsentModal {
    constructor () {
        if (typeof MicroModal === 'undefined') {
            return
        }

        this.setProperties()
        this.init()
    }

    setProperties () {
        this.isLoading = false
        this.modalKey = 'wpgdprc-consent-modal'
        this.modalId = '#wpgdprc-consent-modal'
        this.consentModal = document.querySelector(this.modalId)
        this.navigationButtonClass = '.wpgdprc-consent-modal__navigation-button'
        this.navigationButtonActiveClass = '.wpgdprc-consent-modal__navigation-button--active'
        this.navigationButtonActiveKey = 'wpgdprc-consent-modal__navigation-button--active'
        this.descriptionClass = '.wpgdprc-consent-modal__description'
        this.descriptionActiveClass = '.wpgdprc-consent-modal__description--active'
        this.settingsLinks = document.querySelectorAll('.wpgdprc-consents-settings-link, .wpgdprc-processor-settings-link')
    }

    init () {
        if (!this.consentModal) {
            return
        }

        const modalTrigger = document.querySelector('[data-micromodal-trigger=' + this.modalKey + ']')
        trapFocus(this.consentModal)

        this.initModal(modalTrigger)
        this.triggerModal()
        this.tabSwitcher(this.consentModal)
        this.modalListener(this.consentModal)
    }

    initModal (modalTrigger) {
        MicroModal.init({
            disableScroll: true,
            disableFocus: true,
            onShow: () => {
                if (modalTrigger) {
                    modalTrigger.setAttribute('aria-expanded', 'true')
                }
            },
            onClose: consentModal => {
                const descriptions = consentModal.querySelectorAll(this.descriptionClass)
                const buttons = consentModal.querySelectorAll(this.navigationButtonClass)
                const checkboxes = consentModal.querySelectorAll('input[type="checkbox"]')

                let first = false
                descriptions.forEach(element => {
                    element.classList.remove(this.descriptionActiveClass)
                    if (!first) {
                        first = true
                        element.classList.add(this.descriptionActiveClass)
                    }
                })

                first = false
                buttons.forEach(element => {
                    element.classList.remove(this.navigationButtonActiveClass)
                    if (!first) {
                        first = true
                        element.classList.add(this.navigationButtonActiveClass)
                    }
                })

                checkboxes.forEach(element => {
                    element.checked = false
                })

                if (modalTrigger) {
                    modalTrigger.setAttribute('aria-expanded', 'false')
                }
            }
        })
    }

    triggerModal () {
        if (!this.settingsLinks) {
            return
        }

        this.settingsLinks.forEach(element => {
            element.addEventListener('click', event => {
                event.preventDefault()
                MicroModal.show(this.modalKey, {})
            })
        })
    }

    /**
     * @param modal
     */
    tabSwitcher (modal) {
        const buttons = modal.querySelectorAll('.wpgdprc-consent-modal__navigation button')
        if (!buttons) {
            return
        }

        const descriptions = modal.querySelectorAll('.wpgdprc-consent-modal__description')

        buttons.forEach(element => {
            element.addEventListener('click', event => {
                event.preventDefault()

                const target = modal.querySelector('.wpgdprc-consent-modal__description[data-target="' + element.getAttribute('data-target') + '"]')
                if (!target) {
                    return
                }

                // Remove active state from all buttons
                buttons.forEach(item => {
                    item.classList.remove(this.navigationButtonActiveKey)
                })

                // Add active state to current button
                element.classList.add(this.navigationButtonActiveKey)

                // // Hide all descriptions
                descriptions.forEach(item => {
                    item.style.display = 'none'
                })

                // Show current description
                target.style.display = 'flex'
            })
        })
    }

    /**
     * @param modal
     */
    modalListener (modal) {
        const button = modal.querySelector('.wpgdprc-button--secondary')
        if (button == null) {
            return
        }

        let isLoading = this.isLoading
        button.addEventListener('click', event => {
            event.preventDefault()
            if (isLoading) {
                return
            }

            const checkboxes = modal.querySelectorAll('input[type="checkbox"]')
            const checked = []

            if (checkboxes.length > 0) {
                for (let i = 0; i < checkboxes.length; i++) {
                    const checkbox = checkboxes[i]
                    const value = checkbox.value
                    if (checkbox.checked === true && !isNaN(value)) {
                        checked.push(parseInt(value))
                    }
                }
            }

            isLoading = _setConsentCookie(checked)
        })
        this.isLoading = isLoading
    }
}
