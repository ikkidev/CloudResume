import { isIntersectionObserverEnabled, setupIntersectionObserver } from '../utils/helpers'

const animationsSelector = 'data-animation'

/**
 * Get the timeout length of the item (animation duration + delay).
 * @param {HTMLElement} item
 * @returns {number} Time in ms.
 */
const getItemTimeout = item => {
    const style = getComputedStyle(item)
    const duration = parseFloat(style.animationDuration)
    const delay = parseFloat(style.animationDelay)

    return (duration + delay) * 1000
}

/**
 * Make an item visible.
 * @param {HTMLElement} item
 */
const makeItemVisible = item => {
    item.setAttribute(`${animationsSelector}-appearing`, '')

    setTimeout(() => {
        item.removeAttribute(`${animationsSelector}`)
        item.removeAttribute(`${animationsSelector}-appearing`)
        item.setAttribute(`${animationsSelector}-complete`, '')
    }, getItemTimeout(item))
}

export default () => {
    // If we don't have IntersectionObserver, show them all.
    if (!isIntersectionObserverEnabled()) {
        const items = document.querySelectorAll(`[${animationsSelector}]`)

        for (const item of [...items]) {
            makeItemVisible(item)
        }

        return
    }

    setupIntersectionObserver(
        `[${animationsSelector}]`,
        target => {
            makeItemVisible(target)
        },
        [0]
    )
}
