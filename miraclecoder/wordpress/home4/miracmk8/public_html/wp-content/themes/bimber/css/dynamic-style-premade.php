<?php
/**
 * Premade color schemes
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */
$bimber_stack = bimber_get_current_stack();
?>
.g1-dark { color: rgba(255, 255, 255, 0.8); }

.g1-dark h1,
.g1-dark h2,
.g1-dark h3,
.g1-dark h4,
.g1-dark h5,
.g1-dark h6,
.g1-dark .g1-mega,
.g1-dark .g1-alpha,
.g1-dark .g1-beta,
.g1-dark .g1-gamma,
.g1-dark .g1-delta,
.g1-dark .g1-epsilon,
.g1-dark .g1-zeta {
	color: #fff;
}

<?php if ( 'original-2018' == $bimber_stack ) : ?>
	.g1-dark .g1-giga-2nd,
	.g1-dark .g1-mega-2nd,
	.g1-dark .g1-alpha-2nd,
	.g1-dark .g1-beta-2nd,
	.g1-dark .g1-gamma-2nd,
	.g1-dark .g1-delta-2nd,
	.g1-dark .g1-epsilon-2nd,
	.g1-dark .g1-zeta-2nd {
		border-color: rgba(255, 255, 255, 0.1);
	}
<?php endif; ?>


<?php if ( 'bunchy' === $bimber_stack || 'music' == $bimber_stack ) : ?>
.g1-dark .g1-meta { color: rgba(255, 255, 255, 0.8); }
.g1-dark .g1-meta a { color: rgba(255, 255, 255, 1); }
.g1-dark .entry-categories { color: rgba(255, 255, 255, 1); }
.g1-dark .entry-categories a { color: rgba(255, 255, 255, 1); }
<?php elseif ( 'hardcore' === $bimber_stack ) : ?>
.g1-dark .g1-meta { color: rgba(255, 255, 255, 0.6); }
.g1-dark .g1-meta a { color: rgba(255, 255, 255, 0.8); }
<?php else : ?>
.g1-dark .g1-meta { color: rgba(255, 255, 255, 0.6); }
.g1-dark .g1-meta a { color: rgba(255, 255, 255, 0.8); }
<?php endif;?>
.g1-dark .g1-meta a:hover { color: rgba(255, 255, 255, 1); }

.g1-dark .archive-title:before {
	color: inherit;
}


.g1-dark [type=input],
.g1-dark [type=email],
.g1-dark select {
	border-color: rgba(255,255,255, 0.15);
}

.g1-dark [type=submit] {
	border-color: #fff;
	background-color: #fff;
	color: #1a1a1a;
}

.g1-dark .g1-button-solid {
	border-color: #fff;
	background-color: #fff;
	color: #1a1a1a;
}

.g1-dark .g1-button-simple,
.g1-dark .g1-filter-pill {
	border-color: #fff;
	color: #fff;
}

.g1-dark .g1-newsletter-avatar {
	background-color: #fff;
	color: #1a1a1a;
}



