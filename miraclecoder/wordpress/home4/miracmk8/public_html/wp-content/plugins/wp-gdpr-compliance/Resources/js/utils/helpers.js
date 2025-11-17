import qs from 'query-string'

/**
 * Check if IntersectionObserver is natively enabled.
 * @return {Boolean} True if IntersectionObserver is enabled, false otherwise.
 */
export const isIntersectionObserverEnabled = () => 'IntersectionObserver' in window

/**
 * Set up an IntersectionObserver.
 *
 * Accepts a selector or an array of selectors, and executes the callback
 * function for each selector when it enters the viewport.
 *
 * For more info, see: <https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API>
 *
 * @param {DOMString|Array} selectors HTML selector for the observer.
 * @param {Function} callback Function to run when an element enters the viewpoint.
 * @param {Array} threshold Threshold to trigger the IntersectionObserver.
 */
export const setupIntersectionObserver = (selectors, callback, threshold = [0]) => {
    const io = new IntersectionObserver(
        entries => {
            [...entries].forEach(({ isIntersecting, _, target }) => {
                if (isIntersecting) {
                    callback(target)
                    io.unobserve(target)
                }
            })
        },
        { threshold }
    )

    if (!selectors || !callback) {
        return
    }

    for (const selector of [].concat(selectors)) {
        for (const element of [...document.querySelectorAll(selector)]) {
            io.observe(element)
        }
    }
}

/**
 * Set up an AJAX call using Fetch.
 * @param {string} url
 * @param {Object} params Params to send to the Ajax call.
 * @param {string} method
 * @return {Promise}
 */
export const ajax = (url, params, method = 'POST') => {
    const body = qs.stringify(
        {
            ...params
        },
        { arrayFormat: 'index' }
    )
    const args = {
        method,
        credentials: 'same-origin',
        headers: new Headers({ 'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8' })
    }
    if (method === 'GET') {
        url += '?' + body
    } else {
        args.body = body
    }
    return fetch(url, args)
}

/**
 * Set up the data object for passing through the params to the ajax call
 * @param params
 * @returns {{data: string}}
 */
export const ajaxDataParams = (params) => {
    return {
        data: JSON.stringify({
            ...params
        })
    }
}

/**
 * Set max height for expandable elements
 * @param element
 * @param value
 */
export const setMaxHeight = (element, value) => {
    if (value === 'true') {
        if (element.style.maxHeight) {
            element.style.maxHeight = null
        } else {
            element.style.maxHeight = element.scrollHeight + 'px'
        }
    }

    if (value === 'false') {
        element.style.maxHeight = null
    }
}

/**
 * @param name
 * @returns {*}
 * @private
 */
export const _readCookie = (name) => {
    return document.cookie.split('; ').reduce((r, v) => {
        const parts = v.split('=')
        return parts[0] === name ? decodeURIComponent(parts[1]) : r
    }, null)
}

/**
 * @param name
 * @param data
 * @param days
 * @private
 */
export const _saveCookie = (name, data, days) => {
    const date = new Date()
    data = data || ''
    days = days || 365
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1e3)
    document.cookie = name + '=' + encodeURIComponent(data) + '; expires=' + date.toGMTString() + '; path=' + wpgdprcFront.path
}

/**
 * @param data
 * @returns {string}
 * @private
 */
export const _objectToParametersString = (data) => {
    return Object.keys(data).map(key => {
        let value = data[key]
        if (typeof value === 'object') {
            value = JSON.stringify(value)
        }
        return key + '=' + value
    }).join('&')
}

/**
 * @param checkboxes
 * @returns {Array}
 * @private
 */
export const _getValuesByCheckedBoxes = checkboxes => {
    const output = []
    if (!checkboxes.length) {
        return output
    }

    checkboxes.forEach(element => {
        const value = parseInt(element.value)
        if (element.checked && value > 0) {
            output.push(value)
        }
    })
    return output
}

export const trapFocus = (element) => {
    const focusableEls = element.querySelectorAll('a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])')
    const firstFocusableEl = focusableEls[0]
    const lastFocusableEl = focusableEls[focusableEls.length - 1]
    const KEYCODE_TAB = 9

    element.addEventListener('keydown', event => {
        const isTabPressed = (event.key === 'Tab' || event.keyCode === KEYCODE_TAB)
        if (!isTabPressed) {
            return
        }

        /* shift + tab */
        if (event.shiftKey) {
            if (document.activeElement === firstFocusableEl) {
                lastFocusableEl.focus()
                event.preventDefault()
            }
            return
        }

        /* tab */
        if (document.activeElement === lastFocusableEl) {
            firstFocusableEl.focus()
            event.preventDefault()
        }
    })
}

/**
 * Turns a string into node elements
 * @param html
 * @returns {ChildNode}
 */
export const htmlToElement = (html) => {
    const template = document.createElement('template')
    template.innerHTML = html.trim() // Never return a text node of whitespace as the result
    return template.content.firstChild
}

/**
 * Toggle a class on an element by add or remove status.
 * @param element
 * @param className
 * @param add
 */
export const toggleClass = (element, className, add) => {
    if (add) {
        element.classList.add(className)
    } else {
        element.classList.remove(className)
    }
}

/**
 * Split an array in 2 separate arrays based on a filter function.
 *
 * @example ``` javascript
 *  const [pass, fail] = partition(myArray, (e) => e > 5)
 * ```
 *
 * @param array
 * @param isValid callable
 * @returns {*}
 */
export const partition = (array, isValid) => {
    return array.reduce(([pass, fail], elem) => {
        return isValid(elem) ? [[...pass, elem], fail] : [pass, [...fail, elem]]
    }, [[], []])
}

/**
 * Set event listeners on multiple events at once.
 *
 * @param element
 * @param events
 * @param callback
 *
 * @example ``` javascript
 *  addEventListeners(myElement, ['click', 'mouseover'], (e) => { console.log('click or mouseover') })
 * ```
 */
export const addEventListeners = (element, events, callback) => {
    events.forEach(event => {
        element.addEventListener(event, callback)
    })
}
