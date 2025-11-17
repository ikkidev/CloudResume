import MicroModal from 'micromodal'
import { ajax } from '../utils/helpers'

export default class FormModal {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcAdmin.pluginPrefix
        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.formAction = this.prefix + '_form_submitted'

        this.modalId = 'wpgdprc-form-modal'
        this.options = {
            openClass: 'is-open',
            disableScroll: true,
            disableFocus: true,
            openTrigger: 'data-form-open',
            closeTrigger: 'data-form-close',
            onShow: () => { document.body.style.overflowY = 'hidden' },
            onClose: () => { document.body.style.overflowY = 'auto' }
        }

        this.showFormModal = wpgdprcAdmin.showFormModal
    }

    init () {
        if (!document.querySelector(`#${this.modalId}`)) {
            return
        }

        MicroModal.init(this.options)
        if (this.showFormModal || window.location.hash === `#${this.modalId}`) {
            this.show()
        }

        this.AddSubmitListener()

        addEventListener('hashchange', () => {
            if (window.location.hash === `#${this.modalId}`) {
                this.show()
            }
        })
    }

    show () {
        if (window.location.hash === `#${this.modalId}`) {
            window.location.hash = ''
        }
        MicroModal.show(this.modalId, this.options)
    }

    AddSubmitListener () {
        window.addEventListener('message', event => {
            if (event.data.type === 'hsFormCallback' && event.data?.eventName === 'onFormSubmit') {
                this.handleRequest()
            }
        })
    }

    handleRequest () {
        this.sendSubmitRequest()
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    MicroModal.close(this.modalId)
                    location.reload()
                }
            })
    }

    async sendSubmitRequest () {
        return await ajax(this.ajaxUrl, {
            action: this.formAction,
            [this.ajaxArg]: this.ajaxNonce
        })
    }
}
