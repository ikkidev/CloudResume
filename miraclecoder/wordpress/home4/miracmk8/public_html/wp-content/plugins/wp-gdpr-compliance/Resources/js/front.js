import 'regenerator-runtime/runtime.js'

import init from './utils/init'
import animations from './components/animations'
import ConsentBar from './components/consent-bar'
import ConsentModal from './components/consent-modal'
import LoadConsents from './components/load-consents'
import FormAccessRequest from './components/form-access-request'
import FormDeleteRequest from './components/form-delete-request'

/**
 * When using FontAwesome via Javascript
 * import fontAwesome from './components/fontAwesome';
 */

// On Document ready
init(() => {
    const consents = typeof wpgdprcFront.consents !== 'undefined' ? wpgdprcFront.consents : []
    animations()

    /**
     * When using FontAwesome via Javascript
     * fontAwesome();
     */
    // Add other components here...
    if (Array.isArray(consents) && consents.length > 0) {
        new ConsentBar()
        new ConsentModal()
        new LoadConsents()
    }
    new FormAccessRequest()
    new FormDeleteRequest()
})
