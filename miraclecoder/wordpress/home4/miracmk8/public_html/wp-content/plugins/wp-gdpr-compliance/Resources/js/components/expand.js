/**
 * Admin Component for the expandable elements
 * Used for Settings > Integrations
 */
import { setMaxHeight } from '../utils/helpers'

export default class Expand {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.expands = document.querySelectorAll('[data-expand]')
        this.isExpandedClassName = 'is-expanded'
    }

    init () {
        this.createExpand()
    }

    setHeightExpand (element, value) {
        if (value === 'true') {
            element.style.maxHeight = element.scrollHeight + 'px'
        }
    }

    createExpand () {
        if (!this.expands) {
            return
        }

        this.expands.forEach(expand => {
            // Can control multiple triggers
            const triggers = expand.querySelectorAll('[aria-controls]')

            triggers.forEach(trigger => {
                const targetId = trigger ? trigger.getAttribute('aria-controls') : ''
                const target = targetId ? document.querySelector(`#${targetId}`) : ''
                const expanded = trigger ? trigger.getAttribute('aria-expanded') : ''

                if (!trigger || !target) {
                    return
                }

                // Set height on resize
                window.addEventListener('resize', () => {
                    if (expanded === 'true') {
                        this.setHeightExpand(target, 'true')
                    }
                })

                // On click trigger; expand the content
                trigger.addEventListener('click', event => {
                    event.preventDefault()
                    const expanded = trigger.getAttribute('aria-expanded')
                    let newExpanded = 'false'

                    if (expanded === 'false') {
                        newExpanded = 'true'
                    }

                    // Set height on click
                    setMaxHeight(target, newExpanded)

                    // Set newExpanded for all triggers
                    triggers.forEach(triggerX => {
                        triggerX.setAttribute('aria-expanded', newExpanded)
                    })

                    if (newExpanded === 'true') {
                        target.classList.add(this.isExpandedClassName)
                    } else {
                        target.classList.remove(this.isExpandedClassName)
                    }
                })
            })
        })
    }
}
