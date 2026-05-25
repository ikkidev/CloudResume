import { _saveCookie, ajax, ajaxDataParams } from '../utils/helpers'

export const _setConsentCookie = async (checked = 'all') => {
    const prefix = wpgdprcFront.prefix
    const ajaxUrl = wpgdprcFront.ajaxUrl
    const ajaxNonce = wpgdprcFront.ajaxNonce
    const ajaxArg = wpgdprcFront.ajaxArg
    const cookieName = wpgdprcFront.cookieName
    let isLoading = true

    // Do the call
    try {
        await ajax(ajaxUrl, {
            action: prefix + '_consent_cookie',
            [ajaxArg]: ajaxNonce,
            ...ajaxDataParams({
                checked: checked
            })
        }, 'POST').then(response => {
            return response.json()
        }).then(response => {
            isLoading = false
            const success = typeof response.success !== 'undefined' ? response.success : false
            if (!success) {
                console.dir(response)
                return
            }

            const message = typeof response.message !== 'undefined' ? response.message : false
            _saveCookie(cookieName, message)
            window.location.reload(true)
        })
    } catch (error) {
        console.error(error)
        isLoading = false
    }

    return isLoading
}
