(function () {
    if (typeof sessionStorage === 'undefined') {
        console.warn('Vignette ad failed to load. sessionStorage not supported by your browser.');
        return;
    }

    const skips = parseInt(document.querySelector('.adace-vignette').getAttribute('data-skips'), 10);
    const adsCampaign = document.querySelector('.adace-vignette').getAttribute('data-ads-campaign');

    if (isNaN(skips) || skips <= 0) {
        return;
    }

    let closePopup = function() {
        let wrapper = document.querySelector('.adace-vignette');

        if (!wrapper) {
            return;
        }

        wrapper.remove();

        // Store cookie to prevent loading the same popup when a user visits that page again.
        let vignette = sessionStorage.getItem('adace_vignette') || {};

        if (typeof vignette === 'string') {
            vignette = JSON.parse(vignette);
        }

        vignette[adsCampaign].closed.push(window.location.pathname);

        sessionStorage.setItem('adace_vignette', JSON.stringify(vignette));
    };

    let loadPopup = function () {
        let wrapper = document.querySelector('.adace-vignette');

        // Show popup.
        wrapper.classList.remove('adace-vignette-hidden');

        // Run countdown.
        let countdown = wrapper.querySelector('.adace-vignette-countdown-number');

        if (countdown) {
            let counterId = setInterval(function () {
                let counterValue = parseInt(countdown.textContent, 10);

                counterValue--;

                countdown.textContent = counterValue;

                if (counterValue <= 0) {
                    clearInterval(counterId);
                    closePopup();
                }
            }, 1000);
        }

        // Close button.
        wrapper.querySelector('.adace-vignette-close').addEventListener('click', (e) => {
            e.preventDefault();
        closePopup();
    });
    };

    let vignette = sessionStorage.getItem('adace_vignette') || {};

    if (typeof vignette === 'string') {
        vignette = JSON.parse(vignette);
    }

    if ( typeof vignette[adsCampaign] === 'undefined' ) {
        vignette[adsCampaign] = {
            'closed': [],
            'page': 1
        };
    }

    let campaignVignette = vignette[adsCampaign];

    // Check if the current page was loaded with the popup.
    //if (-1 !== campaignVignette.closed.indexOf(window.location.pathname)) {
    //    return;
    //}

    // Display.
    if ( 1 === parseInt(campaignVignette.page, 10) ) {
        loadPopup();
    }

    // Normalize and update.
    vignette[adsCampaign].page = vignette[adsCampaign].page % skips;
    vignette[adsCampaign].page++;

    sessionStorage.setItem('adace_vignette', JSON.stringify(vignette));
})();
