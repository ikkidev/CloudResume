//Changes the text of the wordpress to wordpress content in import
const importerTitles = document.getElementsByClassName( 'importer-title' );
if ( importerTitles ) {
	[ ...importerTitles ].forEach( ( val, index ) => {
		if ( val?.outerText === 'WordPress' ) {
			document.getElementsByClassName( 'importer-title' )[
				index
			].innerText = migration.wordpress_title;
		}
	} );
}

class MigrationModal {
	constructor() {
		this.create();
		this.modal = document.getElementById( 'migration-progress-modal' );
		this.closeBtn = document.querySelector( '.nfd-migration-close' );
		this.closeBtn.addEventListener( 'click', this.hide.bind( this ) );
	}
	create() {
		// designs a modal for migration tool
		const node = document.createElement( 'div' );
		node.innerHTML = `<div class='migrate-screen'> 
		<div class='nfd-migration-loading'>
		<span class='nfd-migration-loader'></span>
		<span class='nfd-migration-error'>X</span>
		<h2 class='nfd-migration-title'>${ migration.migration_title }</h2></div> 
		<p class='nfd-migration-description'>${ migration.migration_description }</p> 
		<button class="nfd-migration-close">x</button>
		</div>`;

		node.style.position = 'absolute';
		node.style.top = '0';
		node.style.bottom = '0';
		node.style.right = '0';
		node.style.left = '0';
		node.style.backgroundColor = '#ffffff5e';
		node.style.display = 'none';
		node.style.alignItems = 'center';
		node.style.justifyContent = 'center';
		node.setAttribute( 'id', 'migration-progress-modal' );

		document.getElementById( 'wpbody-content' ).appendChild( node );
	}
	update( title = '', description = '' ) {
		title = '' === title ? migration.migration_title : title;
		description =
			'' === description ? migration.migration_description : description;
		document.querySelector( '.nfd-migration-title' ).innerText = title;
		document.querySelector( '.nfd-migration-description' ).innerHTML =
			description;
	}
	show( withClose = false, icon = 'loading' ) {
		this.closeBtn.style.display = withClose ? 'block' : 'none';
		if ( icon === 'error' ) {
			document.querySelector( '.nfd-migration-error' ).style.display =
				'block';
			document.querySelector( '.nfd-migration-loader' ).style.display =
				'none';
		} else {
			document.querySelector( '.nfd-migration-error' ).style.display =
				'none';
			document.querySelector( '.nfd-migration-loader' ).style.display =
				'block';
		}
		this.modal.style.display = 'flex';
	}
	hide() {
		this.modal.style.display = 'none';
	}
}

const MModal = new MigrationModal();

// load a pop up when user clicks on run importer for wordpress migration tool
document
	.querySelector( 'a[href*="import=site_migration_wordpress_importer"]' )
	?.addEventListener( 'click', function ( e ) {
		e.preventDefault();
		MModal.update();
		MModal.show();

		fetch(
			migration.restApiUrl +
				'/newfold-migration/v1/migrate/connect&_locale=user',
			{
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': migration.restApiNonce,
				},
			}
		)
			.then( ( response ) => response.json() )
			.then( ( res ) => {
				fetch(
					migration.restApiUrl +
						'/newfold-migration/v1/migrate/events&_locale=user',
					{
						credentials: 'same-origin',
						method: 'post',
						headers: {
							'Content-Type': 'application/json',
							'X-WP-Nonce': migration.restApiNonce,
						},
						body: JSON.stringify( {
							key: 'migration_initiated_tools',
							data: {
								page: window.location.href,
							},
						} ),
					}
				);
				if ( res?.success ) {
					MModal.hide();
					window.open( res?.data?.redirect_url, '_self' );
				} else {
					MModal.update( res?.code, res?.message );
					MModal.show( true, 'error' );
				}
			} )
			.catch( ( err ) => console.error( err ) );
	} );
