/**
 * Debounce function to prevent password checks before the user finishes typing.
 *
 * @param func
 * @param delay
 * @returns {(function(): void)|*}
 */
const debounce = ( func, delay = 500 ) => {
	let Timer
	return function() {
		const context = this
		const args = arguments
		clearTimeout(Timer);
		Timer = setTimeout(() =>
			func.apply(context, args), delay
		)
	}
}

/**
 * Submits a REST API request to check an entered password.
 *
 * @param newPass
 */
function checkPassword( newPass ) {
	const options = {};

	options.data = { password: newPass };
	options.type = 'GET';

	window.wp.ajax.send( 'sp-is-password-secure', options )
		.done( function( response ) {
			if ( true === response ) {
				securePasswordDetected();
			} else {
				insecurePasswordDetected();
			}
		} );
}

/**
 * Creates a WordPress admin notice for an insecure password.
 *
 * @returns {*}
 */
function insecurePasswordNotice() {
	const messageText = 'Please choose a different password. This password was found in a database of insecure passwords.';
	let insecureNotice;

	if ( document.body.classList.contains( 'login-action-rp' ) ) {
		insecureNotice = document.createElement( 'p' );
		insecureNotice.className = 'message nfd-sp-insecure-password-notice';
		insecureNotice.innerHTML = messageText;
	} else {
		insecureNotice = document.createElement( 'tr' );
		insecureNotice.className = 'form-field nfd-sp-insecure-password-notice';

		insecureNotice.appendChild( document.createElement( 'th' ) );
		insecureNotice.appendChild( document.createElement( 'td' ) );

		const notice = document.createElement( 'div' );
		notice.className = 'notice notice-error error';
		notice.innerHTML = '<p>' + messageText + '</p>';

		insecureNotice.getElementsByTagName( 'td' )[0].appendChild( notice );
	}

	return insecureNotice;
}

/**
 * Takes appropriate actions when an insecure password is detected.
 */
function insecurePasswordDetected() {
	hideWeakPasswordOverride();

	if ( 0 < document.getElementsByClassName('nfd-sp-insecure-password-notice').length ) {
		return;
	}

	const notice = insecurePasswordNotice();
	const body = document.body;

	if ( body.classList.contains( 'login' ) ) {
		document.getElementById( 'login' ).insertBefore( notice, document.getElementById( 'resetpassform' ) );
	} else if ( body.classList.contains( 'user-new-php' ) ) {
		document.getElementsByClassName( 'form-table' )[0].firstElementChild.insertBefore( notice, document.getElementsByClassName( 'pw-weak' )[0] );
	} else {
		// Editing a user.
		document.getElementById( 'password' ).parentNode.insertBefore( notice, document.getElementsByClassName( 'pw-weak' )[0] );
	}
}

/**
 * Takes appropriate actions when a secure password is entered.
 */
function securePasswordDetected() {
	const notices = document.getElementsByClassName( 'nfd-sp-insecure-password-notice' );

	if ( notices.length > 0 ) {
		Array.prototype.forEach.call( notices, function( element ) {
			element.remove();
		});
	}
}

/**
 * Handles a keyup event on the password field.
 *
 * @param event
 */
function passwordKeyup( event ) {
	checkPassword( event.target.value );
}

/**
 * Hides the confirm weak password override field.
 */
function hideWeakPasswordOverride() {
	// Hide the weak password confirmation. User can't save without using a secure password.
	document.getElementsByClassName( 'pw-weak' )[0].style.display = "none";
}

window.addEventListener('load', function () {
	const passwordField = document.getElementById('pass1');

	if (passwordField) {
		passwordField.addEventListener('keyup', debounce(passwordKeyup));
	}

	const generatePasswordButtons = document.getElementsByClassName('wp-generate-pw');

	if (generatePasswordButtons.length > 0) {
		Array.prototype.forEach.call(generatePasswordButtons, function (element) {
			/*
			 * When generate password buttons are clicked, it's safe to assume that the returned
			 * password is secure.
			 */
			element.addEventListener('click', function () {
				securePasswordDetected();
			});
		});
	}
});
