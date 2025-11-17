/**
 * Admin Component for the request overview
 */
export default class RequestOverview {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.overview = document.querySelector('.wpgdprc-tabs--requests')
        this.tabTitle = document.querySelector('.wpgdprc-tabs--requests .wpgdprc-tabs__title')
        this.activeLink = document.querySelector('.wpgdprc-tabs--requests [aria-controls="active-requests"]')
        this.expiredLink = document.querySelector('.wpgdprc-tabs--requests [aria-controls="expired-requests"]')
    }

    init () {
        this.handleTabSwitch()
    }

    handleTabSwitch () {
        if (!this.overview || !this.tabTitle || !this.activeLink || !this.expiredLink) {
            return
        }

        this.resetTabindex(this.activeLink)
        this.resetTabindex(this.expiredLink)

        if (this.expiredLink.getAttribute('aria-selected') === 'true') {
            this.tabTitle.innerText = this.expiredLink.innerText
        }

        this.activeLink.addEventListener('click', event => {
            this.tabTitle.innerText = event.target.innerText
            this.resetTabindex(this.expiredLink)
        })

        this.expiredLink.addEventListener('click', event => {
            this.tabTitle.innerText = event.target.innerText
            this.resetTabindex(this.activeLink)
        })
    }

    resetTabindex (element) {
        if (!element) {
            return
        }

        element.setAttribute('tabindex', '0')
    }
}
