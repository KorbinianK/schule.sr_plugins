<?php
/*
Plugin Name: Schule.sr Adminpanel
Description: Adminpanel für Schule.sr
Author: Korbinian Kasberger
Version: 0.1
*/

    add_action( 'admin_menu', 'my_admin_menu' );

    function my_admin_menu() {
        add_menu_page( 'Schule.sr', 'Einstellungen', 'manage_options', 'sr-adminpanel/sr-adminpanel.php', 'sr_adminpanel', 'dashicons-tickets', 4  );
        add_submenu_page( 'sr-adminpanel/sr-adminpanel.php', 'My Sub Level Menu Example', 'Sub Level Menu', 'manage_options', 'sr-events/sr-events.php', 'myplguin_admin_sub_page' ); 
    }

function sr_adminpanel(){
	?>
	<div class="wrap">
		<h2>Welcome To My Plugin</h2>
	</div>
	<?php
}