<?php
/**
 * Partners.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation;

use NewfoldLabs\WP\Module\Activation\Partners\Akismet;
use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Activation\Partners\CreativeMail;
use NewfoldLabs\WP\Module\Activation\Partners\Jetpack;
use NewfoldLabs\WP\Module\Activation\Partners\MonsterInsights;
use NewfoldLabs\WP\Module\Activation\Partners\OptinMonster;
use NewfoldLabs\WP\Module\Activation\Partners\WpForms;
use NewfoldLabs\WP\Module\Activation\Partners\Yoast;
use NewfoldLabs\WP\Module\Activation\Partners\WordPress;

/**
 * Partner class.
 */
class Partners {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param Container $container The plugin container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		$akismet          = new Akismet();
		$creative_mail    = new CreativeMail();
		$jetpack          = new Jetpack();
		$monster_insights = new MonsterInsights();
		$optin_monster    = new OptinMonster();
		$wp_forms         = new WpForms();
		$yoast            = new Yoast();
		$wordpress        = new WordPress();

		$akismet->init();
		$creative_mail->init();
		$jetpack->init();
		$monster_insights->init();
		$optin_monster->init();
		$wp_forms->init();
		$yoast->init();
		$wordpress->init();

		add_filter( 'plugins_loaded', array( $this, 'is_fresh_install' ) );
	}

	/**
	 * Check if it is a fresh installation.
	 *
	 * @hooked plugins_loaded
	 */
	public function is_fresh_install(): void {

		$container = $this->container;

		$is_fresh_install = $container->has( 'isFreshInstallation' ) && $container->get( 'isFreshInstallation' );

		$current_value = get_option( 'nfd_module_activation_fresh_install' );
		$desired_value = $is_fresh_install ? true : false;

		if ( $current_value !== $desired_value ) {
			update_option( 'nfd_module_activation_fresh_install', $desired_value );
		}
	}
}
