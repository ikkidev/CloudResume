<?php
/**
 * User Comments > My > Filters
 */

namespace Commentace;

$selected 	= '';

$url_var = 'filter_by';

// Check for a custom sort_order.
if ( ! empty( $_REQUEST[ $url_var ] ) ) {
    $selected = $_REQUEST[ $url_var ];
}
?>
<div class="subnav-filters filters no-ajax" id="subnav-filters">

    <div class="component-filters clearfix">

        <form action="" method="get" id="comments-filter-by">
            <label for="comments-status"><?php esc_html_e( 'Show:', 'cace' ); ?></label>

            <select id="comments-status" name="<?php echo esc_attr( $url_var ); ?>" onchange="this.form.submit();">
                <option value="all" <?php selected( $selected, '' ); ?>><?php esc_html_e( 'All', 'cace' ); ?></option>
                <option value="published" <?php selected( $selected, 'published' ); ?>><?php esc_html_e( 'Published', 'cace' ); ?></option>
                <option value="pending" <?php selected( $selected, 'pending' ); ?>><?php esc_html_e( 'Pending', 'cace' ); ?></option>
            </select>

            <noscript>
                <input id="submit" type="submit" name="form-submit" class="submit" value="<?php esc_attr_e( 'Go', 'cace' ); ?>" />
            </noscript>
        </form>

    </div>

</div>
