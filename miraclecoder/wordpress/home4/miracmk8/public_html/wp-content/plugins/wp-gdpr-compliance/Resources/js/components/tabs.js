/**
 * Tabs
 */
export default class Tabs {
    constructor () {
        this.setProperties()
        this.init()
    }

    init () {
        this.createTabs()
    }

    setProperties () {
        this.tabs = document.querySelectorAll('[data-tabs]')
        this.hash = window.location.hash
        this.tabFocus = 0
    }

    closeTabs (tabs) {
        tabs.forEach((tab) => {
            if (tab.getAttribute('aria-selected') === 'true') {
                tab.setAttribute('aria-selected', 'false')
            }
        })
    }

    setActiveTab (tab) {
        tab.setAttribute('aria-selected', 'true')
    }

    closeTabPanels (tabPanels) {
        tabPanels.forEach((tabPanel) => {
            tabPanel.setAttribute('aria-hidden', 'true')
        })
    }

    setActiveTabPanel (tabPanel) {
        tabPanel.setAttribute('aria-hidden', 'false')
    }

    setHash (controls) {
        if (history.pushState) {
            history.pushState(null, null, `#${controls}`)
        } else {
            location.hash = `#${controls}`
        }
    }

    handleActiveTabOnLoad (tabsComponent) {
        if (!this.hash || !tabsComponent) {
            return
        }

        const allTabs = tabsComponent.querySelectorAll('[role="tab"]')
        const allTabPanels = tabsComponent.querySelectorAll('[role="tabpanel"]')
        const tab = tabsComponent.querySelector(`a[href="${this.hash}"][role="tab"]`)
        const tabPanel = tabsComponent.querySelector(`${this.hash}[role="tabpanel"]`)

        if (!allTabs || !allTabPanels || !tab || !tabPanel) {
            return
        }

        // Handle Active Tab
        this.closeTabs(allTabs)
        this.setActiveTab(tab)

        // Handle Active TabPanel
        this.closeTabPanels(allTabPanels)
        this.setActiveTabPanel(tabPanel)
    }

    /**
     * Enable arrow navigation between tabs in the tab list
     * @param allTabs
     * @param event
     */
    handleKeyCodeInput (allTabs, event) {
        if (!this.tabFocus) {
            this.tabFocus = 0
        }

        if (event.keyCode === 39 || event.keyCode === 37) {
            if (event.keyCode === 39) {
                // Move right
                this.tabFocus++
                // If we're at the end, go to the start
                if (this.tabFocus >= allTabs.length) {
                    this.tabFocus = 0
                }
            } else if (event.keyCode === 37) {
                // Move left
                this.tabFocus--
                // If we're at the start, move to the end
                if (this.tabFocus < 0) {
                    this.tabFocus = allTabs.length - 1
                }
            }

            allTabs[this.tabFocus].focus()
        }
    }

    createTabs () {
        if (!this.tabs) {
            return
        }

        this.tabs.forEach((tabsComponent) => {
            const allTabs = tabsComponent.querySelectorAll('[role="tab"]')
            const allTabPanels = tabsComponent.querySelectorAll('[role="tabpanel"]')
            const tabList = tabsComponent.querySelector('[role="tablist"]')

            if (!allTabs || !allTabPanels || !tabList) {
                return
            }

            allTabs.forEach((tab, i) => {
                // Add a click event handler to each tab
                tab.addEventListener('click', event => {
                    event.preventDefault()
                    const controls = tab.getAttribute('aria-controls')
                    const tabPanel = tabsComponent.querySelector(`#${controls}[role="tabpanel"]`)
                    this.tabFocus = i

                    // // Handle Active Tab
                    this.closeTabs(allTabs)
                    this.setActiveTab(tab)
                    this.setHash(controls)
                    //
                    // // Handle Active TabPanel
                    this.closeTabPanels(allTabPanels)
                    this.setActiveTabPanel(tabPanel)
                })
            })

            tabList.addEventListener('keydown', event => this.handleKeyCodeInput(allTabs, event))
            this.handleActiveTabOnLoad(tabsComponent)
        })
    }
}
