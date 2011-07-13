<?php
/*
Plugin Name: SMW-Import
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Imports informations from a SMW into wordpress
Version: 0.1
Author: Christoph Herbst
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/
/*  Copyright 2011  Christoph Herbst  (email : chris.p.herbst@googlemail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('smwimport.php');
// Hook for adding admin menus
add_action('admin_menu', 'smwimport_add_pages');
add_action('smwimport_import_all_event', 'smwimport_import_all' );
register_activation_hook( __FILE__, 'smwimport_on_activation' );


function smwimport_on_activation() {
	// check existing data sources
	$datasources = get_option('smwimport_data_sources',array());
	if ( empty($datasources) ){
		// add default data source
		$datasources[] = dirname(__FILE__) . '/example_data.json';
		update_option( 'smwimport_data_sources', $datasources );
	}
}

function smwimport_import_all(){
	smwimport::import_all();
}

// action function for above hook
function smwimport_add_pages() {
    $title = 'SMW Import';
    $slug = 'smwimport';
    // Add a new submenu under Tools:
    add_management_page( __($title,'menu-smwimport'), __($title,'menu-smwimport'), 'manage_options',$slug, 'smwimport_tools_page');

  // Add a new submenu under Settings:
    add_options_page(__($title,'menu-smwimport'), __($title,'menu-smwimport'), 'manage_options', $slug, 'smwimport_settings_page');

}


// mt_tools_page() displays the page content for the Test Tools submenu
function smwimport_tools_page() {
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    echo "<h2>" . __( 'SMW Import', 'menu-smwimport' ) . "</h2>";
    $hidden_field_name = 'smwimport_submit_hidden';

// See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
	$class = "updated";
	if ( $_POST['Import'] ){
		$ret = smwimport::import_all();

		if ( is_wp_error($ret) ){
			$message = $ret->get_error_message();
			$class = "error";
		}else $message = 'Successfully imported.'."</br>".$ret;
	}else if ( $_POST['Delete'] ){
		$ret = smwimport::delete_all_imported();
		if ( is_wp_error($ret) ){
			$message = $ret->get_error_message();
			$class = "error";
		}else $message = 'successfully deleted all imported posts.';
	}
        // Put the result  message on the screen
?>
<div id="message" class="<?php echo $class ?>"><p><strong><?php _e($message, 'menu-smwimport' ); ?></strong></p></div>
<?php

    }


    // tools form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p class="submit">
<input type="submit" name="Import" class="button-primary" value="<?php esc_attr_e('Import from SMW') ?>" />
<input type="submit" name="Delete" class="button-secondary" value="<?php esc_attr_e('Delete all imported') ?>" />
</p>

</form>
</div>

<?php

}


// smw_import_page() displays the page content for the Test tools submenu
function smwimport_settings_page() {
    global $events_option_name, $news_option_name, $press_option_name, $images_page_option_name;
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $hidden_field_name = 'smwimport_submit_hidden';

    $attachment_url = get_option('smwimport_attachment_url');
    // Read in existing option value from database
    $datasources_opt = get_option('smwimport_data_sources',array());

    $login_url = get_option('smwimport_login_url');
    $username = get_option('smwimport_username');
    $password = get_option('smwimport_password');
    $import_tests = (boolean)get_option('smwimport_import_tests');

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value

	if ( $_POST['Submit'] ){
		foreach( $datasources_opt as $key => $opt )
    		    $datasources_opt[$key] = $_POST[ 'smwimport_data_source'.$key ];

		$attachment_url = $_POST['smwimport_attachment_url'];
		$login_url = $_POST['smwimport_login_url'];
		$username = $_POST['smwimport_username'];
		$password = $_POST['smwimport_password'];
		$import_tests = (boolean)$_POST['smwimport_import_tests'];
		// Save the posted value in the database
		update_option( 'smwimport_data_sources', $datasources_opt );

		update_option( 'smwimport_attachment_url', $attachment_url );
		update_option( 'smwimport_login_url', $login_url );
		update_option( 'smwimport_username', $username );
		update_option( 'smwimport_password', $password );
		update_option( 'smwimport_import_tests', $import_tests );
		$message = __('settings saved.', 'menu-smwimport' );
	}else if ( $_POST['NewSource'] ){
		// add new data source
		$datasources_opt[] = '';
		update_option('smwimport_data_sources',$datasources_opt);
		$message = __('New data source added.', 'menu-smwimport' );
	}else if ( $_POST['RemoveSource'] ){
		// remove last data source
		unset($datasources_opt[count($datasources_opt)-1]);
		update_option('smwimport_data_sources',$datasources_opt);
		$message = __('Data source removed.', 'menu-smwimport' );
	}
        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php echo($message);  ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'SMW Import Settings', 'menu-smwimport' ) . "</h2>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<?php foreach ( $datasources_opt as $key => $opt ){ ?>
<p><?php echo(($key+1).'.'); _e("Data source:", 'menu-smwimport' ); ?> 
<input type="text" name="smwimport_data_source<?php echo $key; ?>" value="<?php echo $opt; ?>" size="80">
</p>
<?php } ?>
<p><?php _e("Url to download attachments:", 'menu-smwimport' ); ?> 
<input type="text" name="smwimport_attachment_url" value="<?php echo $attachment_url; ?>" size="80">
</p>

<p><?php _e("Url to login page (for form-based auth):", 'menu-smwimport' ); ?> 
<input type="text" name="smwimport_login_url" value="<?php echo $login_url; ?>" size="80">
</p>

<p><?php _e("SMW username:", 'menu-smwimport' ); ?> 
<input type="text" name="smwimport_username" value="<?php echo $username; ?>" size="40">
</p>

<p><?php _e("SMW password:", 'menu-smwimport' ); ?> 
<input type="password" name="smwimport_password" value="<?php echo $password; ?>" size="40">
</p>

<p><?php _e("Import test data from smwimport-test.php:", 'menu-smwimport' ); ?> 
<input type="checkbox" name="smwimport_import_tests" <?php if ($import_tests) echo 'checked="checked"'; ?>>
</p>

<hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
<input type="submit" name="NewSource" class="button-secondary" value="<?php esc_attr_e('Add new data source') ?>" />
<?php if ( count($datasources_opt) > 0 ){ ?>
<input type="submit" name="RemoveSource" class="button-secondary" value="<?php esc_attr_e('Remove last data source') ?>" />
<?php } ?>
</p>

</form>
</div>

<?php
 
}


?>
