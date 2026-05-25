/**
 * Admin Component for the banner items with delete button
 */
export default class BannerItems {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.bannerItems = document.querySelectorAll('.wpgdprc-banner-item')
        this.willDelete = 'will-delete'
    }

    init () {
        this.handleContent()
    }

    handleContent () {
        if (!this.bannerItems) {
            return
        }

        this.bannerItems.forEach(bannerItem => {
            const contentDefault = bannerItem.querySelector('.wpgdprc-banner-item__content--default')
            const contentDelete = bannerItem.querySelector('.wpgdprc-banner-item__content--delete')
            const buttonDelete = bannerItem.querySelector('.wpgdprc-banner-item__content--default button[data-delete]')
            const buttonCancel = bannerItem.querySelector('.wpgdprc-banner-item__content--delete button[data-cancel]')

            if (!buttonDelete || !buttonCancel) {
                return
            }

            buttonDelete.addEventListener('click', event => {
                event.preventDefault()

                bannerItem.classList.add(this.willDelete)
                contentDefault.setAttribute('aria-hidden', 'true')
                contentDelete.setAttribute('aria-hidden', 'false')
            })

            buttonCancel.addEventListener('click', event => {
                event.preventDefault()

                bannerItem.classList.remove(this.willDelete)
                contentDefault.setAttribute('aria-hidden', 'false')
                contentDelete.setAttribute('aria-hidden', 'true')
            })
        })
    }
}
