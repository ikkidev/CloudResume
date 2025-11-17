<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Runtime\Runtime;

use function NewfoldLabs\WP\ModuleLoader\register;

if ( function_exists( 'add_action' ) )  {
	add_action(
		'newfold_container_set',
		function ( $container )  {
                        $runtime = new Runtime( $container );
                        $runtime->loadIntoPage( 'admin_enqueue_scripts' );
               }
        );
}

