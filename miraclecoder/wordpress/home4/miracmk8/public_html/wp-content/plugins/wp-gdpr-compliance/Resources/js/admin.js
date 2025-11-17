import 'regenerator-runtime/runtime.js'

import init from './utils/init'
import animations from './components/animations'
import Message from './components/message'
import Tabs from './components/tabs'
import Expand from './components/expand'

// Utilities
import ExtendCodeMirror from './utils/codemirror'

// Admin scripts
import BannerItems from './admin/banner-item'
import ConsentBarForm from './admin/consent-bar-form'
import IntegrationsForm from './admin/integrations-form'
import PremiumModeForm from './admin/premium-form'
import PrivacyPolicyForm from './admin/privacy-policy-form'
import RequestOverview from './admin/request-overview'
import RequestUserForm from './admin/request-user-form'
import ResetConsent from './admin/reset-consent'
import SettingsForm from './admin/settings-form'
import Wizard from './admin/wizard'
import SignUpModal from './admin/sign-up-modal'
import ComparisonSlider from './admin/comparison-slider'
import ConsentBarText from './admin/consent-bar-text'
import FormModal from './admin/form-modal'

/**
 * When using FontAwesome via Javascript
 * import fontAwesome from './components/fontAwesome';
 */

// On Document ready
init(() => {
    animations()
    /**
     * When using FontAwesome via javascript
     * fontAwesome();
     */

    // Utilities here...
    const mirror = new ExtendCodeMirror()
    const premiumForm = new PremiumModeForm()

    // Add other components here...
    new Message()
    new Tabs()

    // Add Admin components here...
    const formElements = {
        inputBarText: document.querySelector('.wpgdprc-form__field.wpgdprc-form__field--explaintext input'),
        inputButtonAccept: document.querySelector('.wpgdprc-form__field.wpgdprc-form__field--buttontext input'),
        inputButtonSettings: document.querySelector('.wpgdprc-form__field.wpgdprc-form__field--moretext input'),
        inputModalTitle: document.querySelector('.wpgdprc-form__field.wpgdprc-form__field--modaltitle input'),
        inputModalText: document.querySelector('.wpgdprc-form__field.wpgdprc-form__field--modaltext textarea')
    }
    new ConsentBarForm(formElements)
    new ConsentBarText(formElements)
    new BannerItems()
    new Expand()
    new IntegrationsForm()
    new PrivacyPolicyForm()
    new RequestUserForm()
    new RequestOverview()
    new ResetConsent()
    new SettingsForm()
    new ComparisonSlider()

    new SignUpModal(premiumForm)
    new FormModal()
    new Wizard(mirror)
})
