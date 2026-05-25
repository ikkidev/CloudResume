<?php
/**
 * WordPress comments pagination template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php if ( get_comment_pages_count() > 1 ) : ?>
    <nav class="g1-comment-pagination">
        <p>
            <strong><?php echo esc_html_x( 'Pages', 'pagination', 'cace' ); ?></strong>
            <?php paginate_comments_links(); ?>
        </p>
    </nav>
<?php endif; ?>
