<?php
/**
 * Class for importing demo image
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( ! class_exists( 'Bimber_Demo_Image_Importer' ) ) {
	class Bimber_Demo_Image_Importer extends WP_Import {

		public $process_image_nb;

		/**
		 * Parses the WXR file and prepares us for the task of processing parsed data
		 *
		 * @param array $import_data        Parsed data
		 */
		function import_start( $import_data ) {
			if ( is_wp_error( $import_data ) ) {
				echo '<p><strong>' . __( 'Sorry, there has been an error.', 'wordpress-importer' ) . '</strong><br />';
				echo esc_html( $import_data->get_error_message() ) . '</p>';
				$this->footer();
				die();
			}

			$this->version = $import_data['version'];
			$this->get_authors_from_import( $import_data );
			$this->posts = $import_data['posts'];
			$this->terms = $import_data['terms'];
			$this->categories = $import_data['categories'];
			$this->tags = $import_data['tags'];
			$this->base_url = esc_url( $import_data['base_url'] );

			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );

			do_action( 'import_start' );
		}

		/**
		 * The main controller for the actual import stage.
		 *
		 * @param array $import_data        Parsed data
		 */
		function import( $import_data ) {
			add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
			add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );
			add_filter( 'wp_image_editors',     array( $this, 'disable_imagick_editor' ), 99 );

			$this->import_start( $import_data );

			$this->get_author_mapping();

			wp_suspend_cache_invalidation( true );

			// We just have this call to import attachments.
			$this->process_posts();

			wp_suspend_cache_invalidation( false );

			// update incorrect/missing information in the DB
			$this->backfill_parents();
			$this->backfill_attachment_urls();
			$this->remap_featured_images();

			$this->import_end();
		}

		function get_images_count( $import_data ) {
			$count  = 0;

			foreach ( $import_data['posts'] as $post ) {
				$post = apply_filters( 'wp_import_post_data_raw', $post );

				if ( 'attachment' != $post['post_type'] ) {
					continue;
				}

				$count++;
			}

			return $count;
		}

		/**
		 * Create new posts based on import information
		 *
		 * Posts marked as having a parent which doesn't exist will become top level items.
		 * Doesn't create a new post if: the post type doesn't exist, the given post ID
		 * is already noted as imported or a post with the same title and date already exists.
		 * Note that new/updated terms, comments and meta are imported for the last of the above.
		 */
		function process_posts() {
			$this->posts = apply_filters( 'wp_import_posts', $this->posts );

			$curr_index = 0;

			foreach ( $this->posts as $post ) {
				$post = apply_filters( 'wp_import_post_data_raw', $post );

				if ( 'attachment' != $post['post_type'] ) {
					continue;
				}

				$curr_index++;

				if ( $curr_index !== $this->process_image_nb ) {
					continue;
				}

				if ( ! post_type_exists( $post['post_type'] ) ) {
					printf( __( 'Failed to import &#8220;%s&#8221;: Invalid post type %s', 'wordpress-importer' ),
						esc_html($post['post_title']), esc_html($post['post_type']) );
					echo '<br />';
					do_action( 'wp_import_post_exists', $post );
					continue;
				}

				if ( isset( $this->processed_posts[$post['post_id']] ) && ! empty( $post['post_id'] ) )
					continue;

				if ( $post['status'] == 'auto-draft' )
					continue;

				/*
				// Reduce number of generated image sizes.
				$attachment_name = $post['post_title'];

				// demo_0X image - generate just theme's sizes, without retina.
				if ( preg_match( '/^demo_\d+/', $attachment_name ) ) {
					global $_wp_additional_image_sizes;

					foreach( $_wp_additional_image_sizes as $image_size_id => $image_size_data ) {
						if ( 0 !== strpos( $image_size_id, 'bimber-' ) || preg_match( '/\-2x$/', $image_size_id ) ) {
							unset( $_wp_additional_image_sizes[ $image_size_id ] );
						}
					}
				// other - disable all sizes.
				} else {
					// Disable all image sizes.
					global $_wp_additional_image_sizes;
					$_wp_additional_image_sizes = array();
				}
				*/

				$post_type_object = get_post_type_object( $post['post_type'] );

				$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );

				/**
				* Filter ID of the existing post corresponding to post currently importing.
				*
				* Return 0 to force the post to be imported. Filter the ID to be something else
				* to override which existing post is mapped to the imported post.
				*
				* @see post_exists()
				* @since 0.6.2
				*
				* @param int   $post_exists  Post ID, or 0 if post did not exist.
				* @param array $post         The post array to be inserted.
				*/
				$post_exists = apply_filters( 'wp_import_existing_post', $post_exists, $post );

				if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
					printf( __('%s &#8220;%s&#8221; already exists.', 'wordpress-importer'), $post_type_object->labels->singular_name, esc_html($post['post_title']) );
					echo '<br />';
					$comment_post_ID = $post_id = $post_exists;
					$this->processed_posts[ intval( $post['post_id'] ) ] = intval( $post_exists );
				} else {
					$post_parent = (int) $post['post_parent'];
					if ( $post_parent ) {
						// if we already know the parent, map it to the new local ID
						if ( isset( $this->processed_posts[$post_parent] ) ) {
							$post_parent = $this->processed_posts[$post_parent];
						// otherwise record the parent for later
						} else {
							$this->post_orphans[intval($post['post_id'])] = $post_parent;
							$post_parent = 0;
						}
					}

					// map the post author
					$author = sanitize_user( $post['post_author'], true );
					if ( isset( $this->author_mapping[$author] ) )
						$author = $this->author_mapping[$author];
					else
						$author = (int) get_current_user_id();

					$postdata = array(
						'import_id' => $post['post_id'], 'post_author' => $author, 'post_date' => $post['post_date'],
						'post_date_gmt' => $post['post_date_gmt'], 'post_content' => $post['post_content'],
						'post_excerpt' => $post['post_excerpt'], 'post_title' => $post['post_title'],
						'post_status' => $post['status'], 'post_name' => $post['post_name'],
						'comment_status' => $post['comment_status'], 'ping_status' => $post['ping_status'],
						'guid' => $post['guid'], 'post_parent' => $post_parent, 'menu_order' => $post['menu_order'],
						'post_type' => $post['post_type'], 'post_password' => $post['post_password']
					);

					$original_post_ID = $post['post_id'];
					$postdata = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

					$postdata = wp_slash( $postdata );

					if ( 'attachment' == $postdata['post_type'] ) {
						$remote_url = ! empty($post['attachment_url']) ? $post['attachment_url'] : $post['guid'];

						// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
						// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
						$postdata['upload_date'] = $post['post_date'];
						if ( isset( $post['postmeta'] ) ) {
							foreach( $post['postmeta'] as $meta ) {
								if ( $meta['key'] == '_wp_attached_file' ) {
									if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches ) )
										$postdata['upload_date'] = $matches[0];
									break;
								}
							}
						}

						$comment_post_ID = $post_id = $this->process_attachment( $postdata, $remote_url );
					} else {
						$comment_post_ID = $post_id = wp_insert_post( $postdata, true );
						do_action( 'wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post );
					}

					if ( is_wp_error( $post_id ) ) {
						printf( __( 'Failed to import %s &#8220;%s&#8221;', 'wordpress-importer' ),
							$post_type_object->labels->singular_name, esc_html($post['post_title']) );
						if ( defined('IMPORT_DEBUG') && IMPORT_DEBUG )
							echo ': ' . $post_id->get_error_message();
						echo '<br />';
						continue;
					}

					if ( $post['is_sticky'] == 1 )
						stick_post( $post_id );
				}

				// map pre-import ID to local ID
				$this->processed_posts[intval($post['post_id'])] = (int) $post_id;

				if ( ! isset( $post['terms'] ) )
					$post['terms'] = array();

				$post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );

				// add categories, tags and other terms
				if ( ! empty( $post['terms'] ) ) {
					$terms_to_set = array();
					foreach ( $post['terms'] as $term ) {
						// back compat with WXR 1.0 map 'tag' to 'post_tag'
						$taxonomy = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
						$term_exists = term_exists( $term['slug'], $taxonomy );
						$term_id = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
						if ( ! $term_id ) {
							$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
							if ( ! is_wp_error( $t ) ) {
								$term_id = $t['term_id'];
								do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
							} else {
								printf( __( 'Failed to import %s %s', 'wordpress-importer' ), esc_html($taxonomy), esc_html($term['name']) );
								if ( defined('IMPORT_DEBUG') && IMPORT_DEBUG )
									echo ': ' . $t->get_error_message();
								echo '<br />';
								do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
								continue;
							}
						}
						$terms_to_set[$taxonomy][] = intval( $term_id );
					}

					foreach ( $terms_to_set as $tax => $ids ) {
						$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
						do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
					}
					unset( $post['terms'], $terms_to_set );
				}

				if ( ! isset( $post['comments'] ) )
					$post['comments'] = array();

				$post['comments'] = apply_filters( 'wp_import_post_comments', $post['comments'], $post_id, $post );

				// add/update comments
				if ( ! empty( $post['comments'] ) ) {
					$num_comments = 0;
					$inserted_comments = array();
					foreach ( $post['comments'] as $comment ) {
						$comment_id	= $comment['comment_id'];
						$newcomments[$comment_id]['comment_post_ID']      = $comment_post_ID;
						$newcomments[$comment_id]['comment_author']       = $comment['comment_author'];
						$newcomments[$comment_id]['comment_author_email'] = $comment['comment_author_email'];
						$newcomments[$comment_id]['comment_author_IP']    = $comment['comment_author_IP'];
						$newcomments[$comment_id]['comment_author_url']   = $comment['comment_author_url'];
						$newcomments[$comment_id]['comment_date']         = $comment['comment_date'];
						$newcomments[$comment_id]['comment_date_gmt']     = $comment['comment_date_gmt'];
						$newcomments[$comment_id]['comment_content']      = $comment['comment_content'];
						$newcomments[$comment_id]['comment_approved']     = $comment['comment_approved'];
						$newcomments[$comment_id]['comment_type']         = $comment['comment_type'];
						$newcomments[$comment_id]['comment_parent'] 	  = $comment['comment_parent'];
						$newcomments[$comment_id]['commentmeta']          = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
						if ( isset( $this->processed_authors[$comment['comment_user_id']] ) )
							$newcomments[$comment_id]['user_id'] = $this->processed_authors[$comment['comment_user_id']];
					}
					ksort( $newcomments );

					foreach ( $newcomments as $key => $comment ) {
						// if this is a new post we can skip the comment_exists() check
						if ( ! $post_exists || ! comment_exists( $comment['comment_author'], $comment['comment_date'] ) ) {
							if ( isset( $inserted_comments[$comment['comment_parent']] ) )
								$comment['comment_parent'] = $inserted_comments[$comment['comment_parent']];
							$comment = wp_slash( $comment );
							$comment = wp_filter_comment( $comment );
							$inserted_comments[$key] = wp_insert_comment( $comment );
							do_action( 'wp_import_insert_comment', $inserted_comments[$key], $comment, $comment_post_ID, $post );

							foreach( $comment['commentmeta'] as $meta ) {
								$value = maybe_unserialize( $meta['value'] );
								add_comment_meta( $inserted_comments[$key], $meta['key'], $value );
							}

							$num_comments++;
						}
					}
					unset( $newcomments, $inserted_comments, $post['comments'] );
				}

				if ( ! isset( $post['postmeta'] ) )
					$post['postmeta'] = array();

				$post['postmeta'] = apply_filters( 'wp_import_post_meta', $post['postmeta'], $post_id, $post );

				// add/update post meta
				if ( ! empty( $post['postmeta'] ) ) {
					foreach ( $post['postmeta'] as $meta ) {
						$key = apply_filters( 'import_post_meta_key', $meta['key'], $post_id, $post );
						$value = false;

						if ( '_edit_last' == $key ) {
							if ( isset( $this->processed_authors[intval($meta['value'])] ) )
								$value = $this->processed_authors[intval($meta['value'])];
							else
								$key = false;
						}

						if ( $key ) {
							// export gets meta straight from the DB so could have a serialized string
							if ( ! $value )
								$value = maybe_unserialize( $meta['value'] );

							add_post_meta( $post_id, $key, $value );
							do_action( 'import_post_meta', $post_id, $key, $value );

							// if the post has a featured image, take note of this in case of remap
							if ( '_thumbnail_id' == $key )
								$this->featured_images[$post_id] = (int) $value;
						}
					}
				}
			}

			unset( $this->posts );
		}

		function disable_imagick_editor( $editors ) {
			// We have to be sure that at least one editor will left.
			if ( count( $editors ) <= 1 ) {
				return $editors;
			}

			$found_index = array_search( 'WP_Image_Editor_Imagick', $editors );

			// Remove Imagick.
			if ( $found_index !== false ) {
				unset( $editors[ $found_index ] );
			}

			return $editors;
		}
	}
}
