import MicroModal from 'micromodal'

export default class SignUpModal {
    constructor (premiumForm) {
        this.setProperties(premiumForm)
        this.init()
    }

    setProperties (premiumForm) {
        this.premiumForm = premiumForm
        this.modalId = 'wpgdprc-sign-up-modal'
        this.options = {
            openClass: 'is-open',
            disableScroll: true,
            disableFocus: true,
            openTrigger: 'data-signup-open',
            closeTrigger: 'data-signup-close',
            onShow: () => { document.body.style.overflowY = 'hidden' },
            onClose: () => { document.body.style.overflowY = 'auto' }
        }

        this.privateButton = document.querySelector('button[data-signup-private]')
        this.businessButton = document.querySelector('button[data-signup-business]')

        this.chosseTypePage = document.querySelector('.wpgdprc-sign-up-modal__choose-type')
        this.signUpPage = document.querySelector('.wpgdprc-sign-up-modal__sign-up')
        this.backButton = document.querySelector('.wpgdprc-modal__back')

        this.signUpButtons = document.querySelectorAll('.wpgdprc-sign-up-button')
        this.ctaButtons = document.querySelectorAll('.wpgdprc-cta-button')

        this.chooseTypeTitle = document.querySelector('.choose-type-title')
        this.signUpTitle = document.querySelector('.sign-up-title')

        this.prefix = wpgdprcAdmin.pluginPrefix
        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.locale = wpgdprcAdmin.locale
        this.showSignUpModal = wpgdprcAdmin.showSignUpModal
    }

    init () {
        if (!document.querySelector(`#${this.modalId}`)) {
            return
        }

        MicroModal.init(this.options)
        this.handleButtonActions()

        if (this.showSignUpModal) {
            MicroModal.show(this.modalId, this.options)
        }
    }

    gotoSignUpPage () {
        this.chosseTypePage.style.display = 'none'
        this.signUpPage.style.display = 'block'
        this.backButton.style.display = 'block'
        this.signUpTitle.style.display = 'block'
        this.chooseTypeTitle.style.display = 'none'
    }

    gotoChoseTypePage () {
        this.chosseTypePage.style.display = 'block'
        this.signUpPage.style.display = 'none'
        this.backButton.style.display = 'none'
        this.signUpTitle.style.display = 'none'
        this.chooseTypeTitle.style.display = 'block'
    }

    handleButtonActions () {
        this.ctaButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault()
                this.gotoSignUpPage()
                MicroModal.show(this.modalId, this.options)
            })
        })

        this.privateButton.addEventListener('click', () => {
            MicroModal.close(this.modalId, this.options)
            this.updatePremium('private')
        })

        this.businessButton.addEventListener('click', () => {
            this.gotoSignUpPage()
            this.updatePremium('business')
        })

        this.backButton.addEventListener('click', () => {
            this.gotoChoseTypePage()
        })

        this.signUpButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault()
                this.gotoSignUpPage()
                this.backButton.style.display = 'none'
                MicroModal.show(this.modalId, this.options)
            })
        })
    }

    updatePremium (userType) {
        this.premiumForm.updatePremium(false, userType)
    }
}
