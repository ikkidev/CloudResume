<?php

use WPGDPRC\Utils\Template;

/**
 * @var string $class
 * @var string $title
 * @var array $tabs
 */

?>
<aside class="wpgdprc-sidebar">
	<nav class="wpgdprc-sidebar__nav" aria-labelledby="sidebar-nav">
		<h2 id="sidebar-nav" class="screen-reader-text"><?php echo esc_html( $title ); ?></h2>
		<ul class="wpgdprc-sidebar__list">
			<?php
			foreach ( $tabs as $slug => $data ) :
				$title  = $data['title'];
				$class  = 'wpgdprc-sidebar__anchor--' . $data['slug'];
				$class .= $data['class'] ? ' ' . $data['class'] : '';
				?>
				<li class="wpgdprc-sidebar__item">
					<a href="<?php echo esc_url( $data['url'] ); ?>" class="wpgdprc-sidebar__anchor <?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $title ); ?>">
						<?php Template::renderIcon( $data['icon'] ); ?>
						<span class="wpgdprc-sidebar__anchor-text"><?php echo esc_html( $title ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
</aside>
