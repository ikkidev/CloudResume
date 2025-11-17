import 'regenerator-runtime/runtime.js'
import { ajax, htmlToElement, toggleClass } from '../utils/helpers'
import PrivacyPolicyForm from './privacy-policy-form'
import ExtendCodeMirror from '../utils/codemirror'

export default class Wizard {
    constructor (mirror) {
        this.setProperties()

        this.codeMirror = mirror

        const self = this
        window.addEventListener('load', () => {
            self.init()
            this.PrivacyPolicyForm = new PrivacyPolicyForm()
        })
    }

    setProperties () {
        this.activeClass = 'active'
        this.hideClass = 'hide'

        this.root = document.querySelector('#wp-gdpr-fts')
        this.stepContainer = document.querySelector('#step-container')
        this.buttonConatiner = document.querySelector('#step-to-buttons')

        this.nextButtons = document.querySelectorAll('button[data-step="next"]')
        this.prevButtons = document.querySelectorAll('button[data-step="prev"]')
        this.doneButtons = document.querySelectorAll('a[data-step="done"]')

        this.userTypeRadios = document.querySelectorAll('input[type="radio"]')
        this.signup = document.querySelector('#signup')
        this.signUpLink = document.querySelector('#signuplink')
        this.forType = document.querySelectorAll('[data-for="personal"], [data-for="business"]')

        this.doneBar = document.querySelector('.wizard--bar--done')
        this.wizardBar = document.querySelector('.wizard--bar')

        // Elements have to be created first.
        this.stepButtons = []

        this.activeStep = 0
        this.numberOfsteps = 0
        this.activeStepElement = null
    }

    /**
     * check if everything is where it is supposed to be and start the slider.
     */
    init () {
        if (!this.root) {
            // Stopped because root element does not exist in the current dom.
            return
        }

        if (!window.wpgdprcAdmin) {
            console.dir('Stopped because localization data was not found.')
            return
        }

        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.locale = wpgdprcAdmin.locale

        this.initSteps()
        this.initStepButtons()
        this.initNext()
        this.initPrev()
        this.initBar()
        this.checkHash()
        this.userType()
        window.addEventListener('popstate', () => {
            this.checkHash()
        })
    }

    /**
     * Initialize all the steps and generate the step to buttons.
     */
    initSteps () {
        const steps = this.stepContainer.querySelectorAll('.step')
        this.numberOfsteps = steps.length - 1
        steps.forEach((el, i) => {
            el.dataset.stepId = i

            this.buttonConatiner.appendChild(htmlToElement(`
                <button class="btn" data-step-to="${i}">
                    ${el.dataset.title || ''}
                </button>
             `))
        })

        this.stepButtons = document.querySelectorAll('button[data-step-to]')
    }

    /**
     * Click actions for the step to buttons.
     */
    initStepButtons () {
        this.stepButtons.forEach(pageLink => {
            pageLink.addEventListener('click', event => {
                event.preventDefault()

                const link = event.currentTarget
                const stepId = link.dataset.stepTo

                if (!Number.isInteger(parseInt(stepId, 10))) {
                    console.dir(`Could not go to step ${stepId} because that is not a valid id.`)
                    return
                }

                this.stepTo(stepId)
            })
        })
    }

    /**
     * Init next buttons.
     */
    initNext () {
        const self = this

        self.nextButtons.forEach((nextButton) => {
            nextButton.addEventListener('click', async event => {
                event.preventDefault()

                // Handle form submission
                const canContinue = await this.handleForms()
                if (!canContinue) {
                    return
                }

                const goto = parseInt(self.activeStep, 10) + 1
                if (goto > self.numberOfsteps) {
                    console.dir(`Could not go to id ${goto} there are only ${self.numberOfsteps} steps`)
                    return
                }

                self.stepTo(goto)
            })
        })
    }

    /**
     * Init the prev buttons. No form check needed.
     */
    initPrev () {
        this.prevButtons.forEach((prevButton) => {
            prevButton.addEventListener('click', event => {
                event.preventDefault()

                const goto = parseInt(this.activeStep, 10) - 1

                if (goto < 0) {
                    console.dir(`Could not goto step ${goto} because here are not that many pages.`)
                    return
                }

                this.stepTo(goto)
            })
        })
    }

    /**
     * User type switch.
     */
    userType () {
        this.userTypeRadios.forEach((radio) => {
            radio.addEventListener('change', () => {
                this.signup.classList.remove('hide')

                this.setNextButtonDisabled(radio.value !== 'personal')

                this.forType.forEach((element) => {
                    if (radio.value === element.dataset.for) {
                        element.classList.remove('hide')
                    } else {
                        element.classList.add('hide')
                    }
                })
            })
        })

        this.signUpLink.addEventListener('click', () => {
            this.setNextButtonDisabled(false)
        })
    }

    /**
     * Look for form and try to submit them
     * @returns {boolean|*}
     */
    async handleForms () {
        const form = this.getActiveStepElement().querySelector('form')
        const formWrapper = this.getActiveStepElement().querySelector('.step__form-wrapper')

        if (!form || !formWrapper) {
            return true
        }

        const action = formWrapper.dataset.action
        if (!action) {
            return true
        }

        if (!form.reportValidity()) {
            return false
        }

        this.codeMirror.saveMirrors()

        return await this.submitForm(form, action)
    }

    /**
     * Submit form via ajax instead of doc request.
     * @param form
     * @param action
     * @returns {Promise<boolean>}
     */
    async submitForm (form, action) {
        const data = jQuery(form).serializeArray()
        // eslint-disable-next-line no-param-reassign
            .reduce((a, x) => {
                a[x.name] = x.value
                return a
            }, {})

        this.setLoading(true)
        return await ajax(this.ajaxUrl, {
            [this.ajaxArg]: this.ajaxNonce,
            locale: this.locale,
            action,
            ...data
        }, 'POST').then(response => {
            return response.json()
        }).then(response => {
            this.setLoading(false)
            const success = typeof response.success !== 'undefined' ? response.success : false
            if (!success) {
                throw new Error()
            }

            this.setActiveFormContent(response.form)
            this.resetElements()
            return true
        }).catch(error => {
            console.error(error)
            alert('Something went wrong please try again later.')
            this.setLoading(false)
            return false
        })
    }

    /**
     * Replace the active form
     * @param content
     */
    setActiveFormContent (content) {
        const formWrapper = this.getActiveStepElement().querySelector('.step__form-wrapper')
        formWrapper.textContent = ''
        formWrapper.appendChild(htmlToElement(content))
    }

    /**
     * Reset all form elements which need some js handles to function properly.
     */
    resetElements () {
        this.codeMirror = new ExtendCodeMirror()
        this.PrivacyPolicyForm = new PrivacyPolicyForm()
    }

    /**
     * Change all elements to reflect the new loading state.
     * @param status
     * @returns {*}
     */
    setLoading (status) {
        const spinners = Array.from(this.nextButtons).map(button => button.querySelector('.spinner'))
        spinners.forEach((spinner) => {
            toggleClass(spinner, this.hideClass, !status)
        })

        if (status === false) {
            return this.updateButtons()
        }

        const buttons = [].concat(this.nextButtons, this.prevButtons, this.stepButtons)
        buttons.forEach((button) => {
            button.disabled = status
        })
    }

    /**
     * @returns {null|*}
     */
    getActiveStepElement () {
        return this.activeStepElement
    }

    /**
     * Try to goto the hash in the url if it is an valid page id.
     */
    checkHash () {
        if (!window.location.hash) {
            this.stepTo(0)
            return
        }

        let hashId = window.location.hash
        hashId = hashId.replace('#', '')

        hashId = parseInt(hashId, 10)
        if (!Number.isInteger(hashId)) {
            hashId = 0
        }

        this.stepTo(hashId, true)
    }

    /**
     * Step to the id.
     *
     * When it is via a history back event dont update the hash because that has already been done.
     *
     * @param id
     * @param ignoreHash
     */
    stepTo (id, ignoreHash = false) {
        // step to next page.
        const step = document.querySelector(`[data-step-id='${id}']`)
        if (!step) {
            console.dir(`Step ${id} does not exist.`)
            return
        }

        this.setActiveStep(step)
        this.updateButtons(parseInt(id, 10))
        this.stepToAction(step)

        this.codeMirror.refreshMirrors()

        // add id to the url for page reloads.
        if (!ignoreHash) {
            window.history.pushState({ id }, '', '#' + id)
        }

        this.activeStep = id
        this.setDone()
    }

    /**
     * Check the action on step to.
     * @param step
     */
    stepToAction (step) {
        if (step.dataset.action === 'disable') {
            this.setNextButtonDisabled(true)
        }
    }

    /**
     * Set disabled status for all next buttons.
     * @param status
     */
    setNextButtonDisabled (status) {
        this.nextButtons.forEach((button) => {
            button.disabled = status
        })
    }

    /**
     * Set the active hash to the current step.
     * @param element
     */
    setActiveStep (element) {
        const activeSteps = document.querySelectorAll(`[data-step-id].${this.activeClass}`)
        activeSteps.forEach(el => {
            el.classList.remove(this.activeClass)
        })

        element.classList.add(this.activeClass)
        this.activeStepElement = element
    }

    /**
     * Update all the buttons statuses and display props.
     * @param id
     */
    updateButtons (id = -1) {
        if (id === -1) {
            id = this.activeStep
        }

        const disabled = document.querySelectorAll('button[data-step-id][disabled],button[data-step][disabled],button[data-step].hide,button[data-step-to][disabled]')
        disabled.forEach(element => {
            element.disabled = false
            element.classList.remove(this.hideClass)
        })

        const active = document.querySelectorAll(`button[data-step-to].${this.activeClass}`)
        active.forEach(element => {
            element.classList.remove(this.activeClass)
        })

        this.doneButtons.forEach(element => {
            element.classList.add(this.hideClass)
        })

        if (id === 0) {
            this.prevButtons.forEach(element => {
                element.disabled = true
            })
        }

        if (id === this.numberOfsteps) {
            this.nextButtons.forEach(element => {
                element.classList.add(this.hideClass)
            })

            this.doneButtons.forEach(element => {
                element.classList.remove(this.hideClass)
            })
        }

        const current = document.querySelectorAll(`button[data-step-to="${id}"]`)
        current.forEach(element => {
            element.classList.add(this.activeClass)
        })

        const afterButtons = document.querySelectorAll('button.active[data-step-to] ~ button[data-step-to]')
        afterButtons.forEach(element => {
            element.disabled = true
        })
    }

    /**
     * Set the loading bar percentage
     */
    setDone () {
        this.doneBar.style.width = `${(this.activeStep / this.numberOfsteps) * 100}%`
    }

    /**
     * Set the with of the bar based on the number of steps.
     */
    initBar () {
        this.wizardBar.style.width = `${this.numberOfsteps === 4 ? 80 : 75}%`
    }
}
