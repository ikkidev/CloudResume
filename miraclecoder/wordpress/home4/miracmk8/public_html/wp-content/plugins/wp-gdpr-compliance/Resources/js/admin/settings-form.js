import { ajax } from '../utils/helpers'

export default class SettingsForm {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.prefix = wpgdprcAdmin.pluginPrefix
        this.ajaxUrl = wpgdprcAdmin.ajaxUrl
        this.ajaxNonce = wpgdprcAdmin.ajaxNonce
        this.ajaxArg = wpgdprcAdmin.ajaxArg
        this.settingTabs = document.querySelector('.wpgdprc-tabs__list')
        this.pageSelects = document.querySelectorAll('.page-selector')
        this.isHiddenClassName = 'is-hidden'
        this.isLoading = false
        this.isLoadingClassName = 'is-loading'
    }

    init () {
        this.updateWindowHistory()
        this.toggleEditLinks()
    }

    /**
     * Updates window history with proper section URL
     */
    updateWindowHistory () {
        if (!this.settingTabs) {
            return
        }

        const links = this.settingTabs.querySelectorAll('a[role="tab"]')
        links.forEach(el => {
            el.addEventListener('click', event => {
                const url = new URL(event.target.getAttribute('href'))
                url.searchParams.delete('settings-updated')
                window.history.replaceState({}, document.title, url.toString())
            })
        })
    }

    /**
     * Toggles "Edit page" link elements for page select fields
     */
    toggleEditLinks () {
        if (!this.pageSelects) {
            return
        }

        this.pageSelects.forEach(el => {
            const wrapper = el.parentNode
            const editEl = wrapper.querySelector('.wpgdprc-link--edit')
            if (!editEl) {
                return
            }

            el.addEventListener('change', event => {
                this.setPostEditLink(editEl, Number(event.target.value))
            })
        })
    }

    setIsLoading (isLoading = true) {
        this.isLoading = isLoading

        if (isLoading) {
            document.body.classList.add(this.isLoadingClassName)
        } else {
            document.body.classList.remove(this.isLoadingClassName)
        }
    }

    /**
     * Sets page/post edit url
     * @param link
     * @param post
     */
    async setPostEditLink (link, post = 0) {
        this.setIsLoading()

        if (post === 0) {
            link.classList.add(this.isHiddenClassName)
            return
        }

        // Do the call
        try {
            await ajax(this.ajaxUrl, {
                action: this.prefix + '_post_edit_link',
                [this.ajaxArg]: this.ajaxNonce,
                post: post
            }, 'POST').then(response => {
                return response.json()
            }).then(response => {
                this.setIsLoading(false)
                const success = typeof response.success !== 'undefined' ? response.success : false
                if (!success) {
                    console.dir(response)
                    return
                }

                const message = typeof response.message !== 'undefined' ? new URL(response.message).toString() : ''
                link.setAttribute('href', message)

                if (!message.length) {
                    link.classList.add(this.isHiddenClassName)
                    return
                }

                link.classList.remove(this.isHiddenClassName)
            })
        } catch (error) {
            console.error(error)
            this.setIsLoading(false)
        }
    }
}
