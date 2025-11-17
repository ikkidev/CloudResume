{
	// Data module runtime data
	const runtimeData = window.newfoldDeactivationSurvey;
	// Dialog instance / will be initialized later
	let deactivationSurveyDialog;
	let deactivateLink;

	const renderDialog = () => {
		// Create dialog container
		const surveyDialog = document.createElement( 'div' );
		surveyDialog.id = 'nfd-deactivation-survey';
		surveyDialog.setAttribute(
			'aria-labelledby',
			'nfd-deactivation-survey-title'
		);
		surveyDialog.setAttribute( 'aria-hidden', 'true' );
		surveyDialog.innerHTML = getDialogHTML();

		// Append dialog container to DOM
		const wpAdmin = document.querySelector( 'body.wp-admin' );
		wpAdmin.appendChild( surveyDialog );

		// Disable body scroll
		document.body.classList.add( 'nfd-noscroll' );

		// Create dialog instance
		deactivationSurveyDialog = new A11yDialog( surveyDialog );
		deactivationSurveyDialog.show();

		// Destroy dialog on hide
		deactivationSurveyDialog.on( 'hide', destroyDialog );
	};

	const getSureCards = () => {
		return runtimeData.strings.sureCards
			.map( ( card ) => {
				if ( card.condition !== true && ! eval( card.condition ) ) {
					return '';
				}

				return `<div class="nfd-deactivation__card">
					<span class="nfd-deactivation__card-title">${ card.title }</span>
					<span class="nfd-deactivation__card-desc">${ card.desc }</span>
				</div>`;
			} )
			.join( '' );
	};

	const getSureContent = () => {
		const content = `
			<div class="nfd-deactivation__content nfd-deactivation-sure">
				<div class="nfd-deactivation__header">
					<h1 class="nfd-deactivation__header-title">${
						runtimeData.strings.sureTitle
					}</h1>
					<p class="nfd-deactivation__header-subtitle">${
						runtimeData.strings.sureDesc
					}</p>
				</div>
				<div class="nfd-deactivation__body">
					<div class="nfd-deactivation__cards">
						${ getSureCards() }
					</div>
				</div>
				<div class="nfd-deactivation__footer">
					<div class="nfd-deactivation__footer-actions">
						<div class="nfd-deactivation__helptext">
							<p>${ runtimeData.strings.sureHelp }</p>
						</div>
						<div class="nfd-deactivation__footer-buttons">
							<button type="button" nfd-deactivation-survey-destroy
								class="button button-secondary" 
								aria-label="${ runtimeData.strings.cancelAriaLabel }">
								${ runtimeData.strings.cancel }
							</button>
							<button type="button" nfd-deactivation-survey-next 
								class="button button-primary" 
								aria-label="${ runtimeData.strings.continueAriaLabel }">
								${ runtimeData.strings.continue }
							</button>
						</div>
					</div>
				</div>
				<div class="nfd-deactivation__step">1/2</div>
			</div>
		`;
		return content;
	};

	const getSurveyContent = () => {
		const content = `
		<form class="nfd-deactivation-form" aria-label="${ runtimeData.strings.formAriaLabel }">
			<div class="nfd-deactivation__content nfd-deactivation-survey nfd-hidden" aria-hidden="true">
				<div class="nfd-deactivation__header">
					<h1 id="nfd-deactivation-survey-title" class="nfd-hidden" aria-hidden="true">
						${ runtimeData.strings.surveyAriaTitle }
					</h1>
					<h2 class="nfd-deactivation__header-title">${ runtimeData.strings.surveyTitle }</h2>
					<p class="nfd-deactivation__header-subtitle">${ runtimeData.strings.surveyDesc }</p>
				</div>
				<div class="nfd-deactivation__body">
					<fieldset class="nfd-deactivation-fieldset">
						<label for="nfd-deactivation-survey__input" class="nfd-deactivation-label">${ runtimeData.strings.label }</label>
						<textarea id="nfd-deactivation-survey__input" class="nfd-deactivation-textarea" placeholder="${ runtimeData.strings.placeholder }"></textarea>
					</fieldset>
				</div>
				<div class="nfd-deactivation__footer">
					<div class="nfd-deactivation__footer-actions">
						<div>
							<button type="button" class="button button-secondary" nfd-deactivation-survey-destroy aria-label="${ runtimeData.strings.cancelAriaLabel }">${ runtimeData.strings.cancel }</button>
							<input type="submit" value="${ runtimeData.strings.submit }" nfd-deactivation-survey-submit class="button button-primary" aria-label="${ runtimeData.strings.submitAriaLabel }"/>
						</div>
					</div>
					<div>
						<button type="button" class="nfd-deactivation-survey-action" nfd-deactivation-survey-skip aria-label="${ runtimeData.strings.skipAriaLabel }">${ runtimeData.strings.skip }</button>
					</div>
				</div>
				<span class="nfd-deactivation-survey_loading nfd-hidden"></span>
				<div class="nfd-deactivation__step">2/2</div>
			</div>
		</form>
		`;
		return content;
	};

	const getDeactivatingContent = () => {
		const content = `
			<div class="nfd-deactivation__content nfd-deactivation-goodbye nfd-hidden" aria-hidden="true">
				<div class="nfd-deactivation__header">
					<p class="nfd-deactivation__header-subtitle">${ runtimeData.strings.deactivating }...</p>
				</div>
				<div class="nfd-deactivation__body">
					<div 
						class="spinner is-active"
						style="
							background-position: 20px 0;
							float: none;
							height: auto;
							padding: 10px 0 10px 50px;
							width: auto;
						"
					></div>
				</div>
				<div class="nfd-deactivation__footer">
				</div>
			</div>
		`;
		return content;
	};

	const getDialogHTML = () => {
		const content = `
			<div class="nfd-deactivation-survey__overlay" nfd-deactivation-survey-destroy></div>
				<div class="nfd-deactivation-survey__container" role="document" data-step="1">
					${ getSureContent() }
					${ getSurveyContent() }
					${ getDeactivatingContent() }
				</div>
			<div class="nfd-deactivation-survey__disabled nfd-hidden"></div>
		`;
		return content;
	};

	const destroyDialog = () => {
		// Destroy dialog instance
		deactivationSurveyDialog.destroy();
		deactivationSurveyDialog = null;

		// Remove dialog container from DOM if exists
		const dialog = document.getElementById( 'nfd-deactivation-survey' );
		if ( dialog ) {
			dialog.remove();
		}

		// Enable body scroll
		document.body.classList.remove( 'nfd-noscroll' );
	};

	const deactivatePlugin = () => {
		// Get deactivation link and redirect
		if ( deactivateLink ) {
			window.location.href = deactivateLink;
			const container = document.querySelector(
				'.nfd-deactivation-survey__container'
			);
			const survey = document.querySelector( '.nfd-deactivation-survey' );
			const goodbye = document.querySelector(
				'.nfd-deactivation-goodbye'
			);
			// update container with data setp 3
			container.setAttribute( 'data-step', '3' );

			// hide interstitial are you sure page
			survey.classList.add( 'nfd-hidden' );
			survey.setAttribute( 'aria-hidden', true );
			// display survey content
			goodbye.classList.remove( 'nfd-hidden' );
			goodbye.removeAttribute( 'aria-hidden' );
		} else {
			console.error( 'Error: Deactivation link not found.' );
		}
	};

	const isSubmitting = () => {
		// Disable actions while submitting
		const dialogDisabledOverlay = document.querySelector(
			'.nfd-deactivation-survey__disabled'
		);
		dialogDisabledOverlay.classList.remove( 'nfd-hidden' );
		const dialogLoading = document.querySelector(
			'.nfd-deactivation-survey_loading'
		);
		dialogLoading.classList.remove( 'nfd-hidden' );
		const actionsBtns = [
			...document.querySelectorAll( '.nfd-deactivation-survey-action' ),
			document.querySelector(
				'#nfd-deactivation-survey form input[type="submit"]'
			),
		];
		actionsBtns.forEach( ( btn ) => {
			btn.setAttribute( 'disabled', 'true' );
		} );

		// disbale ESC key while submitting
		deactivationSurveyDialog.on( 'show', () => {
			deactivationSurveyDialog.off( 'keydown' );
		} );
	};

	const submitSurvey = async ( skipped = false ) => {
		isSubmitting();

		// Send event
		return await sendSurveyEvent( skipped ).then( () => {
			deactivatePlugin();
		} );
	};

	/**
	 * Heler to send survey input data
	 *
	 * @param {boolean} skipped
	 * @return {Promise} Promise vis SendEvent
	 */
	const sendSurveyEvent = async ( skipped ) => {
		let surveyInput = skipped ? '(Skipped)' : '(No Input)';

		const input = document.getElementById(
			'nfd-deactivation-survey__input'
		).value;
		if ( input.length > 0 ) {
			surveyInput = input;
		}

		return sendEvent(
			'deactivation_survey_freeform',
			'survey_input',
			surveyInput
		);
	};

	/**
	 * Send Event through to GA4
	 *
	 * @param {string} action
	 * @param {string} key
	 * @param {string} value
	 * @return {Promise} fetch Promise to data event endpoint
	 */
	const sendEvent = async ( action, key, value ) => {
		// set up event data
		const eventData = {
			brand: runtimeData.brand,
			page: window.location.href,
			category: 'user_action',
			label_key: key,
			[ key ]: value,
		};

		// Attach abTestPluginHome flag value if exists
		if ( typeof getABTestPluginHome() === 'boolean' ) {
			eventData.abTestPluginHome = getABTestPluginHome();
		}

		return await fetch( runtimeData.eventsEndpoint, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': runtimeData.restApiNonce,
			},
			body: JSON.stringify( {
				action,
				data: eventData,
			} ),
		} );
	};

	const getABTestPluginHome = () => {
		const { NewfoldRuntime } = window;
		return NewfoldRuntime?.capabilities?.abTestPluginHome;
	};

	const showSurvey = () => {
		const container = document.querySelector(
			'.nfd-deactivation-survey__container'
		);
		const sure = document.querySelector( '.nfd-deactivation-sure' );
		const survey = document.querySelector( '.nfd-deactivation-survey' );
		const textArea = document.getElementById(
			'nfd-deactivation-survey__input'
		);
		// update container with data setp 2
		container.setAttribute( 'data-step', '2' );

		// hide interstitial are you sure page
		sure.classList.add( 'nfd-hidden' );
		sure.setAttribute( 'aria-hidden', true );
		// display survey content
		survey.classList.remove( 'nfd-hidden' );
		survey.removeAttribute( 'aria-hidden' );
		// focus on textarea
		textArea.focus();
	};

	// Attach events listeners
	window.addEventListener( 'DOMContentLoaded', () => {
		const wpAdmin = document.querySelector( 'body.wp-admin' );
		wpAdmin.addEventListener( 'click', ( e ) => {
			// Plugin deactivation listener
			if (
				e.target.id.includes( 'deactivate-' ) &&
				e.target.id.includes( window.NewfoldRuntime.plugin.brand )
			) {
				e.preventDefault();
				renderDialog();
				deactivateLink = e.target.href;
			}

			// Remove dialog listener
			if ( e.target.hasAttribute( 'nfd-deactivation-survey-destroy' ) ) {
				destroyDialog();
			}

			// Continue to survey
			if ( e.target.hasAttribute( 'nfd-deactivation-survey-next' ) ) {
				e.preventDefault();
				showSurvey();
			}

			// Submit listener
			if ( e.target.hasAttribute( 'nfd-deactivation-survey-submit' ) ) {
				e.preventDefault();
				submitSurvey();
			}

			// Skip listener
			if ( e.target.hasAttribute( 'nfd-deactivation-survey-skip' ) ) {
				e.preventDefault();
				submitSurvey( true );
			}
		} );
	} );
}
