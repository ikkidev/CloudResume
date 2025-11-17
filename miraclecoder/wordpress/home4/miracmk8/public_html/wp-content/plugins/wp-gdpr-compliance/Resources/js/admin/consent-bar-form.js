import { ajax, ajaxDataParams, addEventListeners } from '../utils/helpers'

/**
 * Admin Component for the consent bar form (Settings)
 */
export default class ConsentBarForm {
    constructor (formElements) {
        this.setProperties(formElements)
        this.init()
    }

    setProperties (formElements) {
        this.prefix = wpgdprcAdmin.pluginPrefix
        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.locale = wpgdprcAdmin.locale
        this.formAction = this.prefix + '_update_plugin_mode'
        this.toggleTile = document.querySelector('.wpgdprc-tile--consent-bar')

        Object.keys(formElements).forEach(key => {
            this[key] = formElements[key]
        })

        this.colorPickers = document.querySelectorAll('.wpgdprc-form__field--colorpicker')
        this.bar = document.querySelector('.wpgdprc-consent-bar__inner')
        this.barContent = document.querySelector('.wpgdprc-consent-bar__content')
        this.barText = document.querySelector('.wpgdprc-consent-bar__notice')
        this.buttonAccept = document.querySelector('.wpgdprc-consent-bar .wpgdprc-button--accept')
        this.buttonSettings = document.querySelector('.wpgdprc-consent-bar .wpgdprc-button--settings')

        this.selectFont = document.querySelector('.wpgdprc-form__field.wpgdprc-form__field--font select')
        this.consentBar = document.querySelector('.wpgdprc-consent-bar')
        this.barFont = ''
        this.barFontDefault = '\'Sofia Pro\', \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif'
        this.barTextColor = ''
        this.barBackgroundColor = ''
        this.buttonTextColor = ''
        this.buttonBackgroundColor = ''
        this.googleFontsClassName = '#wpgdprc-google-font-css'
        this.googleFontsLink = document.querySelector(this.googleFontsClassName)
        this.isLoading = false
        this.isLoadingClassName = 'is-loading'
        this.pageWrap = document.querySelector('.wrap.wpgdprc')
        this.header = this.pageWrap ? this.pageWrap.querySelector('.wpgdprc-header') : undefined
    }

    init () {
        this.initBar()

        this.handleColorPicker()
        this.handleInputBarContentText()
        this.handleInputButtonAcceptText()
        this.handleInputButtonSettingsText()
        this.handleSelectBarFont()
        this.handleStatusToggle()
    }

    initBar () {
        if (!this.consentBar) {
            return
        }

        this.consentBar.style.display = 'block'
    }

    setIsLoading (isLoading = true) {
        this.isLoading = isLoading

        if (isLoading) {
            document.body.classList.add(this.isLoadingClassName)
        } else {
            document.body.classList.remove(this.isLoadingClassName)
        }
    }

    setBarFont (value) {
        this.barFont = value
    }

    getBarFont () {
        return this.barFont
    }

    setBarTextColor (value) {
        this.barTextColor = value
    }

    getBarTextColor () {
        return this.barTextColor
    }

    setBarBackgroundColor (value) {
        this.barBackgroundColor = value
    }

    getBarBackgroundColor () {
        return this.barBackgroundColor
    }

    setButtonTextColor (value) {
        this.buttonTextColor = value
    }

    getButtonTextColor () {
        return this.buttonTextColor
    }

    setButtonBackgroundColor (value) {
        this.buttonBackgroundColor = value
    }

    getButtonBackgroundColor () {
        return this.buttonBackgroundColor
    }

    setGoogleFontsLink (font) {
        if (!font) {
            return
        }

        if (this.googleFontsLink) {
            this.googleFontsLink.href = this.getGoogleFontUrl(font)
            return
        }

        this.createGoogleFontLink(font)
        this.googleFontsLink = document.querySelector(this.googleFontsClassName)
    }

    getGoogleFontsLink () {
        return this.googleFontsLink
    }

    getGoogleFontUrl (font) {
        return `https://fonts.googleapis.com/css?family=${font.replace(' ', '+')}&display=swap`
    }

    createGoogleFontLink (font) {
        const link = document.createElement('link')
        link.id = this.googleFontsClassName
        link.rel = 'stylesheet'
        link.href = this.getGoogleFontUrl(font)
        document.getElementsByTagName('head')[0].appendChild(link)
    }

    applyColor (element, color) {
        if (!element || !color) {
            return
        }

        element.style.color = color
    }

    applyBackgroundColor (element, color) {
        if (!element || !color) {
            return
        }

        element.style.backgroundColor = color
    }

    applyBorderColor (element, color) {
        if (!element || !color) {
            return
        }

        element.style.borderColor = color
    }

    applyFont (element, font) {
        if (!element || !this.barFontDefault) {
            return
        }

        if (!font) {
            element.style.fontFamily = this.barFontDefault
            return
        }

        element.style.fontFamily = '"' + font + '", sans-serif'
        this.setGoogleFontsLink(font)
    }

    applyBarFont () {
        this.applyFont(this.bar, this.getBarFont())
    }

    applyBarTextColor () {
        this.applyColor(this.barContent, this.getBarTextColor())
        this.applyColor(this.buttonSettings, this.getBarTextColor())
    }

    applyBarBackgroundColor () {
        this.applyBackgroundColor(this.bar, this.getBarBackgroundColor())
    }

    applyButtonTextColor () {
        this.applyColor(this.buttonAccept, this.getButtonTextColor())
    }

    applyButtonBackgroundColor () {
        this.applyBackgroundColor(this.buttonAccept, this.getButtonBackgroundColor())
    }

    applyButtonBorderColor () {
        this.applyBorderColor(this.buttonAccept, this.getButtonBackgroundColor())
    }

    controlColorsByInput (inputColorName, inputTextName, value) {
        if (!inputColorName || !inputTextName || !value) {
            return
        }

        if (inputColorName === this.prefix + '_settings_consents_bar_color' || inputTextName === this.prefix + '_settings_consents_bar_color_text') {
            this.setBarBackgroundColor(value)
            this.applyBarBackgroundColor()
        }

        if (inputColorName === this.prefix + '_settings_consents_bar_text_color' || inputTextName === this.prefix + '_settings_consents_bar_text_color_text') {
            this.setBarTextColor(value)
            this.applyBarTextColor()
        }

        if (inputColorName === this.prefix + '_settings_consents_bar_button_color_primary' || inputTextName === this.prefix + '_settings_consents_bar_button_color_primary_text') {
            this.setButtonBackgroundColor(value)
            this.applyButtonBackgroundColor()
            this.applyButtonBorderColor()
        }

        if (inputColorName === this.prefix + '_settings_consents_bar_button_color_secondary' || inputTextName === this.prefix + '_settings_consents_bar_button_color_secondary_text') {
            this.setButtonTextColor(value)
            this.applyButtonTextColor()
        }
    }

    handleColorPicker () {
        if (!this.colorPickers) {
            return
        }

        this.colorPickers.forEach((colorPicker) => {
            const inputColor = colorPicker.querySelector('input[type="color"]')
            const inputText = colorPicker.querySelector('input[type="text"]')

            if (!inputColor || !inputText) {
                return
            }

            inputColor.addEventListener('change', event => {
                const value = event.target.value

                this.controlColorsByInput(inputColor.name, inputText.name, value)
                inputText.value = value
            })

            inputText.addEventListener('change', event => {
                const value = event.target.value

                this.controlColorsByInput(inputColor.name, inputText.name, value)
                inputColor.value = value
            })
        })
    }

    handleSelectBarFont () {
        if (!this.selectFont) {
            return
        }

        this.selectFont.addEventListener('change', event => {
            this.setBarFont(event.target.value)
            this.applyBarFont()
        })
    }

    handleInputBarContentText () {
        if (!this.inputBarText || !this.barText) {
            return
        }

        addEventListeners(this.inputBarText, ['keyup', 'change'], (event) => {
            this.barText.innerHTML = event.target.value
        })
    }

    handleInputButtonAcceptText () {
        if (!this.inputButtonAccept || !this.buttonAccept) {
            return
        }

        addEventListeners(this.inputButtonAccept, ['keyup', 'change'], (event) => {
            this.buttonAccept.innerHTML = event.target.value
        })
    }

    handleInputButtonSettingsText () {
        if (!this.inputButtonSettings || !this.buttonSettings) {
            return
        }

        addEventListeners(this.inputButtonSettings, ['keyup', 'change'], event => {
            this.buttonSettings.innerHTML = event.target.value
        })
    }

    handleStatusToggle () {
        if (!this.toggleTile) return

        const toggle = this.toggleTile.querySelector('[type="checkbox"]')
        toggle.addEventListener('change', event => {
            this.updateStatus(event.target.checked)
        })
    }

    async updateStatus (value) {
        this.setIsLoading()

        // Do the call
        try {
            await ajax(this.ajaxUrl, {
                action: this.formAction,
                [this.ajaxArg]: this.ajaxNonce,
                locale: this.locale,
                ...ajaxDataParams({
                    value: value
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

                // @TODO show feedback that setting is updated
                // @TODO Quick & dirty, will fix properly later
                this.toggleTile.querySelector('.wpgdprc-tile__check').classList.add('hide')
                this.toggleTile.querySelector('.wpgdprc-tile__footer').innerHTML = response.tile.footer
                this.toggleTile.querySelector('.wpgdprc-tile__text').outerHTML = response.tile.text
                this.toggleTile.classList.remove('wpgdprc-tile--green-light')

                const header = typeof response.header !== 'undefined' ? response.header : ''
                if (header && header.length) {
                    this.header.innerHTML = header
                }

                location.reload()

                console.dir(response)
            })
        } catch (error) {
            console.error(error)
            this.setIsLoading(false)
        }
    }
}
