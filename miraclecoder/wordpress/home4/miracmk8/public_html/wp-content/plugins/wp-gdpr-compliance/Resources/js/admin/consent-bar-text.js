import defaultText from './data/defaults'

/**
 * Admin helper for settings the default texts loaded from the json file.
 */
export default class ConsentBarText {
    constructor (formElements) {
        this.setProperties(formElements)
        this.init()
    }

    setProperties (formElements) {
        this.formElements = formElements
        this.locale = wpgdprcAdmin.locale
        this.useRecommendedTextButton = document.querySelector('#use-ci-recommended-text')
        this.tabButton = document.querySelector('a#tab-consent')
        this.main = document.querySelector('#wpgdprc-main')
    }

    init () {
        if (!this.useRecommendedTextButton) {
            return
        }

        this.useRecommendedTextButton.addEventListener('click', () => {
            this.tabButton.dispatchEvent(new Event('click'))
            this.scroll()
            this.setDefaultTexts()
        })
    }

    fieldToTextLookUp (fieldName) {
        switch (fieldName) {
        case 'inputBarText':
            return 'banner_main_text'

        case 'inputButtonAccept':
            return 'accept_cookies_button'

        case 'inputButtonSettings':
            return 'banner_settings'

        case 'inputModalTitle':
            return 'banner_heading'

        case 'inputModalText':
            return 'cookie_policy_text'

        default:
            return ''
        }
    }

    scroll () {
        if (!this.main) {
            return
        }

        this.main.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        })
    }

    getLangId () {
        return this.locale.substring(0, 2).toUpperCase()
    }

    setDefaultTexts () {
        const langId = this.getLangId()
        let defaultTexts = defaultText[langId]

        // use EN as fallback
        if (!defaultTexts) {
            defaultTexts = defaultText.EN
        }

        Object.keys(this.formElements).forEach(key => {
            const text = defaultTexts[this.fieldToTextLookUp(key)]
            if (text) {
                this.formElements[key].value = text
                this.formElements[key].dispatchEvent(new Event('change'))
            }
        })
    }
}
