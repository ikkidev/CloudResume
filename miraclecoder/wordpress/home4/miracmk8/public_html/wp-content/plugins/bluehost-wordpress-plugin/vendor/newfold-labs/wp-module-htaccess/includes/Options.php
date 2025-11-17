<?php
namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Stores all the WordPress Options used in the htaccess module.
 */
final class Options {
	/**
	 * Prefix for options in the module.
	 *
	 * @var string
	 */
	protected static $prefix = 'nfd_module_htaccess_';

	/**
	 * List of all the options.
	 *
	 * @var array
	 */
	protected static $options = array(
		'saved_state'     => 'saved_state',
		'needs_update'    => 'needs_update',
		'write_lock'      => 'write_lock',
		'early_fragments' => 'early_fragments',
	);

	/**
	 * Get option name for a given key.
	 *
	 * @param string  $option_key    The key for the Options::$options array.
	 * @param boolean $attach_prefix Attach the module prefix.
	 * @return string|false
	 */
	public static function get_option_name( $option_key, $attach_prefix = true ) {
		return isset( self::$options[ $option_key ] )
			? ( $attach_prefix
				? self::$prefix . self::$options[ $option_key ]
				: self::$options[ $option_key ]
			)
			: false;
	}

	/**
	 * Get the list of all options.
	 *
	 * @return array
	 */
	public static function get_all_options() {
		return self::$options;
	}
}
