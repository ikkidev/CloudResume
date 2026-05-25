<?php

namespace NewfoldLabs\WP\Module\AI\SiteGen;

use NewfoldLabs\WP\Module\AI\Utils\PatternParser;
use NewfoldLabs\WP\Module\Data\HiiveConnection;
use NewfoldLabs\WP\Module\Data\SiteCapabilities;
use NewfoldLabs\WP\Module\AI\Patterns;
use NewfoldLabs\WP\Module\Installer\Services\PluginInstaller;
use NewfoldLabs\WP\Module\Installer\Data\Plugins;

/**
 * The class to generate different parts of the site gen object.
 */
class SiteGen {
	/**
	 * The required validations
	 *
	 * @var array
	 */
	private static $required_validations = array(
		'siteclassification'        => array(
			'site_description',
		),
		'targetaudience'            => array(
			'site_description',
		),
		'contenttones'              => array(
			'site_description',
		),
		'contentstructure'          => array(
			'site_description',
		),
		'colorpalette'              => array(
			'site_description',
		),
		'sitemap'                   => array(
			'site_description',
		),
		'pluginrecommendation'      => array(
			'site_description',
		),
		'fontpair'                  => array(
			'site_description',
		),
		'keywords'                  => array(
			'site_description',
		),
		'siteconfig'                => array(
			'site_description',
		),
		'siteclassificationmapping' => array(
			'site_description',
		),
	);

	/**
	 * Function to check capabilities
	 */
	private static function check_capabilities() {
		$capability      = new SiteCapabilities();
		$sitegen_enabled = $capability->get( 'hasAISiteGen' );
		return $sitegen_enabled;
	}

	/**
	 * Function to refine the site description, i.e. translate and summarize when required
	 *
	 * @param string $site_description The site description
	 */
	public static function get_refined_site_description( $site_description ) {
		$refined_description = self::get_sitegen_from_cache( 'refinedSiteDescription' );
		if ( $refined_description ) {
			return $refined_description;
		}

		$response = wp_remote_post(
			NFD_AI_BASE . 'refineSiteDescription',
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'timeout' => 60,
				'body'    => wp_json_encode(
					array(
						'hiivetoken' => HiiveConnection::get_auth_token(),
						'prompt'     => $site_description,
					)
				),
			)
		);

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			return $site_description;
		}

		$refined_description = json_decode( wp_remote_retrieve_body( $response ), true );
		self::cache_sitegen_response( 'refinedSiteDescription', $refined_description );
		return $refined_description;
	}

	/**
	 * Function to validate site info
	 *
	 * @param array  $site_info  The main input for forming the prompt
	 * @param string $identifier The identifier to be used for generating the required meta
	 */
	private static function validate_site_info( $site_info, $identifier ) {
		if ( array_key_exists( $identifier, self::$required_validations ) ) {
			$validations = self::$required_validations[ $identifier ];
			foreach ( $validations as $required_key ) {
				if ( ! array_key_exists( $required_key, $site_info ) ) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Function to get the site gen response from cache based on the identifier
	 *
	 * @param string $identifier The identifier to be used for generating the required meta
	 */
	private static function get_sitegen_from_cache( $identifier ) {
		return get_option( NFD_SITEGEN_OPTION . '-' . strtolower( $identifier ), null );
	}

	/**
	 * Function to cache the response from sitegen API
	 *
	 * @param string $identifier The identifier to be used for generating the required meta
	 * @param array  $response   The response from the sitegen API.
	 */
	private static function cache_sitegen_response( $identifier, $response ) {
		update_option( NFD_SITEGEN_OPTION . '-' . strtolower( $identifier ), $response );
	}

	/**
	 * Function to generate the prompt from the JSON input.
	 *
	 * @param array $site_info The JSON input for the sitegen call.
	 */
	private static function get_prompt_from_info( array $site_info ) {
		$details = array();
		foreach ( $site_info as $key => $value ) {
			$details[] = $key . ': ' . $value;
		}
		return implode( ', ', $details );
	}

	/**
	 * Get the patterns for a particular category.
	 *
	 * @param string $category The category to get patterns for.
	 * @param array  $site_classification site classification as determined by AI.
	 */
	private static function get_patterns_for_category( $category, $site_classification = array() ) {
		$primary_sitetype   = isset( $site_classification['primaryType'] ) ? $site_classification['primaryType'] : null;
		$secondary_sitetype = isset( $site_classification['slug'] ) ? $site_classification['slug'] : null;
		$args               = array(
			'category'       => $category,
			'primary_type'   => $primary_sitetype,
			'secondary_type' => $secondary_sitetype,
		);
		$api                = NFD_PATTERNS_BASE . 'patterns?' . http_build_query( $args );

		$response = wp_remote_get(
			$api,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'timeout' => 60,
			)
		);

		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return array(
				'error' => __( 'We are unable to process the request at this moment', 'wp-module-ai' ),
			);
		}

		$patterns           = json_decode( wp_remote_retrieve_body( $response ), true );
		$processed_patterns = array();

		foreach ( $patterns['data'] as $pattern ) {
			$processed_patterns[ $pattern['slug'] ] = $pattern;
		}

		return $processed_patterns;
	}

	/**
	 * Get the templates for a particular category.
	 *
	 * @param string $category The category to get templates for.
	 * @param array  $site_classification site classification as determined by AI.
	 */
	private static function get_templates_for_category( $category, $site_classification = array() ) {
		$primary_sitetype   = isset( $site_classification['primaryType'] ) ? $site_classification['primaryType'] : null;
		$secondary_sitetype = isset( $site_classification['slug'] ) ? $site_classification['slug'] : null;
		$args               = array(
			'category'       => $category,
			'primary_type'   => $primary_sitetype,
			'secondary_type' => $secondary_sitetype,
		);
		$api_url            = NFD_PATTERNS_BASE . 'templates?' . http_build_query( $args );

		$response = wp_remote_get(
			$api_url,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'timeout' => 60,
			)
		);

		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return array(
				'error' => __( 'We are unable to process the request at this moment', 'wp-module-ai' ),
			);
		}

		$templates           = json_decode( wp_remote_retrieve_body( $response ), true );
		$processed_templates = array();

		foreach ( $templates['data'] as $template ) {
			$processed_templates[ $template['slug'] ] = $template;
		}

		return $processed_templates;
	}

	/**
	 * Function to generate the site meta according to the arguments passed
	 *
	 * @param array $site_info  The Site Info object, will be validated for required params.
	 * @param array $site_classification  The Site Classification object.
	 */
	public static function generate_site_posts( $site_info, $site_classification ) {

		// Generate AI Post Title and Content
		$site_posts = wp_remote_post(
			NFD_AI_BASE . 'generateSiteMeta',
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'timeout' => 60,
				'body'    => wp_json_encode(
					array(
						'hiivetoken' => HiiveConnection::get_auth_token(),
						'prompt'     => self::get_prompt_from_info( $site_info ),
						'identifier' => 'generatesiteposts',
					)
				),
			)
		);

		$site_posts_response_code = wp_remote_retrieve_response_code( $site_posts );
		$site_posts               = json_decode( wp_remote_retrieve_body( $site_posts ), true );

		if ( 200 === $site_posts_response_code ) {
			// If the posts were not created or enough posts were not created
			if ( ! isset( $site_posts['posts'] ) || count( $site_posts['posts'] ) < 6 ) {
				return false;
			}
			// Save Post Content in wp_options
			self::cache_sitegen_response( 'site-posts', $site_posts );
		}

		$post_patterns       = self::get_patterns_for_category( 'text', $site_classification );
		$post_patterns_slugs = Patterns::get_custom_post_patterns();

		$post_dates = array( '3', '5', '10', '12', '17', '19' );
		foreach ( $site_posts['posts'] as $idx => $post_data ) {

			$post_content = '';
			if ( isset( $post_patterns_slugs[ $idx ] ) ) {
				foreach ( $post_patterns_slugs[ $idx ] as $pattern_slug ) {
					$post_content .= $post_patterns[ $pattern_slug ]['content'];
				}
			}

			$post = array(
				'post_status'  => 'publish',
				'post_title'   => $post_data['title'],
				'post_excerpt' => $post_data['content'],
				'post_content' => $post_content,
				'post_date'    => gmdate( 'Y-m-d H:i:s', strtotime( 'last sunday -' . $post_dates[ $idx ] . ' days' ) ),
			);
			\wp_insert_post( $post );
		}
	}

	/**
	 * Function to generate the site meta according to the arguments passed
	 *
	 * @param array   $site_info  The Site Info object, will be validated for required params.
	 * @param string  $identifier The identifier for generating the site meta
	 * @param string  $site_type  The type of site.
	 * @param string  $locale     The locale for site's content.
	 * @param boolean $skip_cache To skip returning the response from cache
	 */
	public static function generate_site_meta( $site_info, $identifier, $site_type, $locale, $skip_cache = false ) {
		if ( ! self::check_capabilities() ) {
			return array(
				'error' => __( 'You do not have the permissions to perform this action', 'wp-module-ai' ),
			);
		}

		if ( ! self::validate_site_info( $site_info, $identifier ) ) {
			return array(
				'error' => __( 'Required values not provided', 'wp-module-ai' ),
			);
		}

		if ( ! $skip_cache ) {
			$site_gen_cached = self::get_sitegen_from_cache( $identifier );
			if ( $site_gen_cached ) {
				return $site_gen_cached;
			}
		}

		$refined_description = self::get_refined_site_description( $site_info['site_description'] );

		$response = wp_remote_post(
			NFD_AI_BASE . 'generateSiteMeta',
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'timeout' => 60,
				'body'    => wp_json_encode(
					array(
						'hiivetoken' => HiiveConnection::get_auth_token(),
						'prompt'     => $refined_description,
						'identifier' => $identifier,
						'siteType'   => $site_type,
						'locale'     => $locale,
					)
				),
			)
		);

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			if ( 400 === $response_code ) {
				$error = json_decode( wp_remote_retrieve_body( $response ), true );
				return array(
					'error' => $error['payload']['reason'],
				);
			}
			try {
				$error = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( array_key_exists( 'payload', $error ) ) {
					return array(
						'error' => $error['payload'],
					);
				} else {
					return array(
						'error' => __( 'We are unable to process the request at this moment', 'wp-module-ai' ),
					);
				}
			} catch ( \Exception $exception ) {
				return array(
					'error' => __( 'We are unable to process the request at this moment', 'wp-module-ai' ),
				);
			}
		}

		$parsed_response = json_decode( wp_remote_retrieve_body( $response ), true );

		self::cache_sitegen_response( $identifier, $parsed_response );

		// Save the color palette and font pair to a separate option to be used later by the editor
		if ( 'colorpalette' === $identifier || 'fontpair' === $identifier ) {
			\update_option( 'nfd_module_onboarding_editor_' . $identifier, $parsed_response );
		}

		if ( 'siteclassification' === $identifier ) {
			// fetch site classification mapping for generating posts
			$site_classification_mapping = self::get_sitegen_from_cache( 'siteclassificationmapping' );
			if ( ! $site_classification_mapping ) {
				$site_classification_mapping = self::generate_site_meta(
					array(
						'site_description' => $site_info['site_description'],
					),
					'siteclassificationmapping',
					$site_type,
					$locale,
				);
			}

			$primary_type = $parsed_response['primaryType'] ?? null;
			$slug         = $parsed_response['slug'] ?? null;
			if (
				$primary_type &&
				$slug &&
				true === ( $site_classification_mapping['blog-posts-custom'][ $primary_type ][ $slug ] ?? false )
			) {
				self::generate_site_posts( $site_info, $parsed_response );
			}
		}

		// calling the action hook for the identifiers
		do_action( 'newfold/ai/sitemeta-' . strtolower( $identifier ) . ':generated', $parsed_response );

		try {
			return $parsed_response;
		} catch ( \Exception $exception ) {
			return array(
				'error' => __( 'We are unable to process the request at this moment', 'wp-module-ai' ),
			);
		}
	}

	/**
	 * Function to get the home page patterns. Randomly generates the patterns and substitutes with existing content.
	 * Set regenerate to get new combinations
	 *
	 * @param string  $site_description The site description (user prompt).
	 * @param string  $site_type        The type of site.
	 * @param array   $content_style    Generated from sitegen.
	 * @param array   $target_audience  Generated target audience.
	 * @param string  $locale           The locale for site's content.
	 * @param boolean $regenerate       If we need to regenerate.
	 */
	public static function get_home_pages( $site_description, $site_type, $content_style, $target_audience, $locale, $regenerate = false ) {
		if ( ! self::check_capabilities() ) {
			return array(
				'error' => __( 'You do not have the permissions to perform this action', 'wp-module-ai' ),
			);
		}

		// Check if we have the response in cache already
		if ( ! $regenerate ) {
			$generated_homepages = self::get_sitegen_from_cache( 'homepages' );
			if ( $generated_homepages ) {
				return $generated_homepages;
			}
		}

		$site_description = self::get_refined_site_description( $site_description );

		$generated_content_structures = self::get_sitegen_from_cache(
			'contentStructures'
		);
		$keywords                     = self::generate_site_meta(
			array(
				'site_description' => $site_description,
				'content_style'    => $content_style,
			),
			'keywords',
			$site_type,
			$locale
		);

		// Site classification: primary and secondary types
		$site_classification = self::get_sitegen_from_cache( 'siteclassification' );
		$primary_type        = 'other';
		$secondary_type      = 'other';
		if ( is_array( $site_classification ) ) {
			$primary_type   = $site_classification['primaryType'] ?? 'other';
			$secondary_type = $site_classification['slug'] ?? 'other';
		}

		if ( ! $generated_content_structures ) {
			$response      = wp_remote_post(
				NFD_CONTENT_GENERATION_BASE . 'page',
				array(
					'headers' => array(
						'Content-Type'  => 'application/json',
						'Authorization' => 'Bearer ' . HiiveConnection::get_auth_token(),
					),
					'timeout' => 60,
					'body'    => wp_json_encode(
						array(
							'prompt'        => array(
								'site_description' => $site_description,
								'keywords'         => wp_json_encode( $keywords ),
								'content_style'    => wp_json_encode( $content_style ),
								'target_audience'  => wp_json_encode( $target_audience ),
							),
							'page'          => 'home',
							'primaryType'   => $primary_type,
							'secondaryType' => $secondary_type,
							'locale'        => $locale,
						)
					),
				)
			);
			$response_code = wp_remote_retrieve_response_code( $response );
			if ( 200 !== $response_code ) {
				if ( 400 === $response_code ) {
					$error = json_decode( wp_remote_retrieve_body( $response ), true );
					return array(
						'error' => $error['payload']['reason'],
					);
				}
				try {
					$error = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( array_key_exists( 'payload', $error ) ) {
						return array(
							'error' => $error['payload'],
						);
					} else {
						return array(
							'error' => __( 'We are unable to process the request at this moment', 'wp-module-ai' ),
						);
					}
				} catch ( \Exception $exception ) {
					return array(
						'error' => __( 'We are unable to process the request at this moment', 'wp-module-ai' ),
					);
				}
			}
			$parsed_response              = json_decode( wp_remote_retrieve_body( $response ), true );
			$generated_content_structures = $parsed_response['contentStructures'];
			// Ensure all content structures should have hero
			foreach ( $generated_content_structures as $home_slug => $structure ) {
				if ( ! in_array( 'hero', $structure, true ) ) {
					array_splice( $structure, 1, 0, 'hero' );
					$generated_content_structures[ $home_slug ] = $structure;
				}
			}
			$generated_patterns  = $parsed_response['generatedPatterns'];
			$generated_homepages = $parsed_response['pages'];
			self::cache_sitegen_response( 'contentStructures', $generated_content_structures );
			self::cache_sitegen_response( 'generatedPatterns', $generated_patterns );
			self::cache_sitegen_response( 'homepages', $generated_homepages );
		}

		$generated_patterns = self::get_sitegen_from_cache( 'generatedPatterns' );

		// fetch site classification mapping
		$site_classification_mapping = self::get_sitegen_from_cache( 'siteclassificationmapping' );
		if ( ! $site_classification_mapping ) {
			$site_classification_mapping = self::generate_site_meta(
				array(
					'site_description' => $site_description,
				),
				'siteclassificationmapping',
				$site_type,
				$locale
			);
		}
		// check if custom hero patterns needs to be added
		$site_classification = self::get_sitegen_from_cache( 'siteclassification' );
		if ( Patterns::needs_custom_content_structure( 'hero-custom', $site_classification_mapping, $site_classification ) ) {
			// update content structures and generated patterns
			$custom_structure = Patterns::get_custom_content_structure( 'hero-custom' );
			foreach ( $generated_content_structures as $home_slug => $structure ) {
				$generated_content_structures[ $home_slug ] = $custom_structure;
			}
			$generated_patterns['hero-custom'] = array_pad( array(), 3, Patterns::get_custom_hero_pattern() );
		}

		// Check if a custom blog homepage structure is needed.
		if ( Patterns::needs_custom_content_structure( 'blog-custom', $site_classification_mapping, $site_classification ) ) {
			// Fetch the blog patterns.
			$blog_patterns = self::get_patterns_for_category( 'blog', $site_classification );
			if ( ! $blog_patterns['error'] ) {
				// Filter out unnecessary blog patterns.
				$blog_patterns_slugs    = Patterns::get_custom_patterns_slugs( 'blog-custom' );
				$blog_patterns_filtered = array_filter(
					$blog_patterns,
					function ( $key ) use ( $blog_patterns_slugs ) {
						return in_array( $key, $blog_patterns_slugs, true );
					},
					ARRAY_FILTER_USE_KEY
				);

				// Set the content structure of the generated homepages to match the custom blog content structure.
				$custom_structure = Patterns::get_custom_content_structure( 'blog-custom' );
				foreach ( $generated_content_structures as $home_slug => $structure ) {
					$generated_content_structures[ $home_slug ] = $custom_structure;
				}

				// Populate the blog-custom content structure patterns in the generated patterns list.
				$generated_patterns['blog-custom'] = array();

				foreach ( $blog_patterns_filtered as $slug => $pattern ) {
					array_push(
						$generated_patterns['blog-custom'],
						array(
							'replacedPattern' => $pattern['content'],
						)
					);
				}
			}
		}

		$random_homepages    = array_rand( $generated_content_structures, 3 );
		$generated_homepages = array();

		$dalle_used             = false;
		$categories_to_separate = array( 'header', 'footer' );

		// Maintain a map of hero patterns that have been chosen so as to not repeat them between homepages
		$used_home_pattern_slugs = array();

		// Choose random categories for the generated patterns and return
		foreach ( $random_homepages as $homepage_index => $slug ) {
			$generated_homepages[ $slug ]         = array();
			$homepage_patterns                    = array();
			$homepage_patterns['generatedImages'] = array();

			// Maintain a map of already used pattern slugs and don't reuse them if the pattern
			// is repeated in the content structure
			$used_pattern_slugs = array();

			foreach ( $generated_content_structures[ $slug ] as $pattern_category ) {
				if ( empty( $generated_patterns[ $pattern_category ] ) ) {
					continue;
				}

				// Get a random pattern for the category when regenerating otherwise pick in sequence
				// so that the 3 previews are as different as much as possible.
				$pattern_index  = array_rand( $generated_patterns[ $pattern_category ] );
				$random_pattern = $generated_patterns[ $pattern_category ][ $pattern_index ];

				if ( 'hero' === $pattern_category ) {
					if ( array_key_exists( $random_pattern['patternSlug'], $used_home_pattern_slugs ) ) {
						while ( array_key_exists( $random_pattern['patternSlug'], $used_home_pattern_slugs ) ) {
							$pattern_index  = array_rand( $generated_patterns[ $pattern_category ] );
							$random_pattern = $generated_patterns[ $pattern_category ][ $pattern_index ];
						}
					}
					$used_home_pattern_slugs[ $random_pattern['patternSlug'] ] = 1;
				}

				if ( array_key_exists( $random_pattern['patternSlug'], $used_pattern_slugs ) ) {
					while ( array_key_exists( $random_pattern['patternSlug'], $used_pattern_slugs ) ) {
						$pattern_index  = array_rand( $generated_patterns[ $pattern_category ] );
						$random_pattern = $generated_patterns[ $pattern_category ][ $pattern_index ];
					}
				}

				$used_pattern_slugs[ $random_pattern['patternSlug'] ] = 1;

				// Check if this is a hero pattern and we are at end of homepages without ever using dalle
				if ( ! $dalle_used && count( $random_homepages ) === ( $homepage_index + 1 ) && 'hero' === $pattern_category ) {
					// Chose the dalle hero only
					foreach ( $generated_patterns[ $pattern_category ] as $gen_hero ) {
						if ( ! empty( $gen_hero['dalleImages'] ) ) {
							$random_pattern = $gen_hero;
						}
					}
				}

				if ( in_array( $pattern_category, $categories_to_separate, true ) ) {
					$homepage_patterns[ $pattern_category ] = $random_pattern['replacedPattern'];
				} else {
					$homepage_patterns['content'] = $homepage_patterns['content'] . $random_pattern['replacedPattern'];
				}

				if ( ! empty( $random_pattern['dalleImages'] ) ) {
					$homepage_patterns['generatedImages'] = $random_pattern['dalleImages'];
					$dalle_used                           = true;
				}
			}
			$generated_homepages[ $slug ] = $homepage_patterns;
		}

		self::cache_sitegen_response( 'homepages', $generated_homepages );
		return $generated_homepages;
	}

	/**
	 * Function to get the content for a page
	 *
	 * @param string $site_description The site description (user prompt).
	 * @param string $site_type        The type of site. (eg: business, ecommerce, personal)
	 * @param string $page             The page slug
	 * @param string $locale           The site content's locale.
	 *
	 * @return string|null The page content or null if the page content was not created
	 */
	public static function get_static_page_content(
		string $site_description,
		string $site_type,
		string $page,
		string $locale
	) {
		$site_classification_mapping = self::get_sitegen_from_cache( 'siteclassificationmapping' );
		if ( ! $site_classification_mapping ) {
			$site_classification_mapping = self::generate_site_meta(
				array(
					'site_description' => $site_description,
				),
				'siteclassificationmapping',
				$site_type,
				$locale
			);
		}

		$site_classification = self::get_sitegen_from_cache( 'siteclassification' );
		if ( 'menu' === $page && Patterns::needs_custom_content_structure( 'menu-custom', $site_classification_mapping, $site_classification ) ) {
			$menu_patterns = self::get_patterns_for_category( $page, $site_classification );
			if ( ! $menu_patterns['error'] ) {
				$menu_patterns_slugs      = Patterns::get_custom_patterns_slugs( 'menu-custom' );
				$menu_patterns_filtered   = array_filter(
					$menu_patterns,
					function ( $key ) use ( $menu_patterns_slugs ) {
						return in_array( $key, $menu_patterns_slugs, true );
					},
					ARRAY_FILTER_USE_KEY
				);
				$random_menu_pattern_slug = array_rand( $menu_patterns_filtered );

				return $menu_patterns_filtered[ $random_menu_pattern_slug ]['content'];
			}
		}

		// if contact page then pick from contact page templates
		// also make sure the jetpack plugin is installed and active
		// then activate "contact-form" module since some templates use jetpack forms
		if ( 'contact' === $page ) {
			$contact_page_templates = self::get_templates_for_category( $page, $site_classification );
			// templates fetched successfully
			if ( ! isset( $contact_page_templates['error'] ) ) {
				$random_contact_page_template_slug = array_rand( $contact_page_templates, 1 );
				$contact_page_content              = $contact_page_templates[ $random_contact_page_template_slug ]['content'];

				// install and activate the Jetpack plugin and enable the "contact-form" module
				if ( PluginInstaller::install( 'jetpack', true ) && Plugins::toggle_jetpack_module( 'contact-form', true ) ) {
					// return contact page
					return $contact_page_content;
				}
			}
		}

		return null;
	}

	/**
	 * Function to generate the content for a page.
	 *
	 * @param array  $pages           The pages to generate.
	 * @param string $site_description The site description (user prompt).
	 * @param string $site_type       The type of site. (eg: business, ecommerce, personal).
	 * @param array  $content_style   The content style.
	 * @param array  $target_audience The target audience.
	 * @param string $locale          The site content's locale.
	 *
	 * @return array The pages content.
	 */
	private static function generate_pages_content( array $pages, string $site_description, string $site_type, $content_style, $target_audience, string $locale ): array {
		// Site classification: primary and secondary types
		$site_classification = self::get_sitegen_from_cache( 'siteclassification' );
		$primary_type        = 'other';
		$secondary_type      = 'other';
		if ( is_array( $site_classification ) ) {
			$primary_type   = $site_classification['primaryType'] ?? 'other';
			$secondary_type = $site_classification['slug'] ?? 'other';
		}

		$requests = array();
		foreach ( $pages as $page_slug => $page_data ) {
			$requests[ $page_slug ] = array(
				'url'     => NFD_CONTENT_GENERATION_BASE . 'page',
				'type'    => 'POST',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . HiiveConnection::get_auth_token(),
				),
				'data'    => wp_json_encode(
					array(
						'prompt'        => array(
							'site_description' => $site_description,
							'keywords'         => wp_json_encode( $page_data['keywords'] ),
							'content_style'    => wp_json_encode( $content_style ),
							'target_audience'  => wp_json_encode( $target_audience ),
						),
						'site_type'     => $site_type,
						'page'          => $page_slug,
						'primaryType'   => $primary_type,
						'secondaryType' => $secondary_type,
						'locale'        => $locale,
					)
				),
			);
		}

		// Generate pages in parallel
		$pages_content = array();
		\WpOrg\Requests\Requests::request_multiple(
			$requests,
			array(
				'timeout'  => 60,
				'complete' => function (
					$response,
					string $page_slug
				) use (
					&$pages_content,
					$pages
				) {
					if ( $response instanceof \WpOrg\Requests\Response && $response->success ) {
						// On success
						$parsed_response = json_decode( $response->body, true );
						$generated_page  = '';
						if ( ! array_key_exists( 'error', $parsed_response['content'] ) ) {
							foreach ( $parsed_response['content'] as $pattern_content ) {
								$generated_page .= $pattern_content['replacedPattern'];
							}
							$pages_content[ $page_slug ] = array(
								'order'   => $pages[ $page_slug ]['order'],
								'content' => $generated_page,
							);
						}
					} elseif ( $response instanceof \WpOrg\Requests\Response && ! $response->success ) {
						// On error Response
						$code    = 'status_code ' . $response->status_code;
						$message = $response->body;
						error_log( 'Response Error generating page content: ' . $code . ' - ' . $message );
					} elseif ( $response instanceof \WpOrg\Requests\Exception ) {
						// On exception
						error_log( 'Exception Error generating page content: ' . $response->getMessage() );
					}
				},
			)
		);

		return $pages_content;
	}

	/**
	 * Function to get the page patterns
	 *
	 * @param string  $site_description The site description (user prompt).
	 * @param string  $site_type        The type of site. (eg: business, ecommerce, personal)
	 * @param array   $content_style    Generated from sitegen.
	 * @param array   $target_audience  Generated target audience.
	 * @param array   $site_map         The site map
	 * @param string  $locale           The site content's locale.
	 * @param boolean $skip_cache       To skip or not to skip
	 *
	 * @return array The pages content
	 */
	public static function get_pages(
		$site_description,
		$site_type,
		$content_style,
		$target_audience,
		$site_map,
		$locale,
		$skip_cache = true
	) {
		if ( ! self::check_capabilities() ) {
			return array(
				'error' => __( 'You do not have the permissions to perform this action', 'wp-module-ai' ),
			);
		}

		$site_description = self::get_refined_site_description( $site_description );

		$identifier = 'generatePages';

		if ( ! $skip_cache ) {
			$site_gen_cached = self::get_sitegen_from_cache( $identifier );
			if ( $site_gen_cached ) {
				return $site_gen_cached;
			}
		}

		// Pages for AI generation
		$pages_for_ai_generation = array();
		// Pages content results
		$pages_content = array();

		foreach ( $site_map as $order => $menu_item ) {
			$page     = $menu_item['slug'];
			$path     = $menu_item['path'];
			$keywords = $menu_item['keywords'];

			// Skip home page
			if ( 'home' === strtolower( $page ) || '/' === $path ) {
				continue;
			}

			// Generate pages that don't require AI generated content
			if ( 'contact' === $page || 'menu' === $page ) {
				$response = self::get_static_page_content(
					$site_description,
					$site_type,
					$page,
					$locale
				);
				if ( null !== $response ) {
					$pages_content[ $page ] = array(
						'order'   => $order,
						'content' => $response,
					);
					continue;
				}
			}

			// Generate pages that require AI generated content
			$pages_for_ai_generation[ $page ] = array(
				'order'    => $order,
				'page'     => $page,
				'keywords' => $keywords,
			);
		}

		// Merge static and AI generated pages
		$pages_content = array_merge(
			$pages_content,
			self::generate_pages_content(
				$pages_for_ai_generation,
				$site_description,
				$site_type,
				$content_style,
				$target_audience,
				$locale
			)
		);

		// Reorder pages by their original order
		uasort(
			$pages_content,
			function ( $a, $b ) {
				return $a['order'] <=> $b['order'];
			}
		);

		// Get only the content of the pages
		$result = array_map(
			function ( $item ) {
				return $item['content'];
			},
			$pages_content
		);

		return $result;
	}
}
