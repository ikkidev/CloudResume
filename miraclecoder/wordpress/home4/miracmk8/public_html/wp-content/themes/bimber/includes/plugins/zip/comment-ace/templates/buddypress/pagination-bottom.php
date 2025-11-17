<?php
/**
 * Pagination for pages of posts
 */

namespace Commentace;
?>

<div id="pag-bottom" class="bp-pagination no-ajax">
	<div class="pag-count">
		<?php bp_comments_pagination_count(); ?>
	</div>

	<div class="bp-pagination-links">

		<?php bp_comments_pagination_links(); ?>

	</div>
</div>
