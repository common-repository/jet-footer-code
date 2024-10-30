<?php
/*
Plugin Name: Jet Footer Code
URI: http://milordk.ru
Author: Jettochkin
Author URI: http://milordk.ru
Donate URI: http://milordk.ru/uslugi.html
Plugin URI: http://milordk.ru/r-lichnoe/opyt/cms/v-pomoshh-adminam-jet-footer-code.html
Description: Your code in the "basement" pages (the statistics counters? Banners? Links?). Ваш код в "подвале" страницы (счетчики статистики? баннеры? ссылки?)
Author: Jettochkin
Author URI: http://milordk.ru
Version: 1.4
*/
register_activation_hook( __FILE__, 'jet_footer_activation' );
function jet_footer_activation() {
	global $bp;
	$jet_footer[ 'footercode_enabled' ] = 0;
	$jet_footer[ 'footercode_code' ] = __( 'simple code', 'jet_footer' );
	add_option( 'jet_footer', $jet_footer, '', 'yes' );
	$blogversion = get_bloginfo( 'version' );
if (stripos($blogversion, 'MU') > 0) {
	$blogs_ids = get_blog_list( 0, 'all' );
	foreach ($blogs_ids as $blog) {
		add_blog_option( $blog['blog_id'], 'jet_footer', $jet_footer );
	}
} else {
		add_option( 'jet_footer', $jet_footer );
}
}

function jet_footer_load_textdomain() {
	$locale = apply_filters( 'wordpress_locale', get_locale() );
	$mofile = dirname( __File__ )   . "/langs/jet_footer-code-$locale.mo";

	if ( file_exists( $mofile ) )
		load_textdomain( 'jet_footer', $mofile );
}
add_action ( 'plugins_loaded', 'jet_footer_load_textdomain', 7 );

function jet_footer_check_menu() {
	$blogversion = get_bloginfo( 'version' );
if (stripos($blogversion, 'MU') > 0) {
	if ( !is_site_admin() )
		return false;
	} else {
	if ( !is_admin() )
		return false;	
	}
if (stripos($blogversion, 'MU') > 0) {
	add_options_page( 'Settings', __( 'Footer code', 'jet_footer' ), 'manage-options', 'jet_footer', 'jet_footer_admin' );	
	} else {
	add_options_page( 'Settings', __( 'Footer code', 'jet_footer' ), 8 , 'jet_footer', 'jet_footer_admin' );	
	}
	}
add_action( 'admin_menu', 'jet_footer_check_menu' );

function jet_footer_admin() {
	$jet_footer = get_option( 'jet_footer' );
	$hidden_field_name = 'hidden_field_name';
	
	if ( $_POST[ $hidden_field_name ] == 'Y' ) {
		// save all inputed data
		$jet_footer[ 'footercode_enabled' ] = 0;
		$jet_footer[ 'footercode_center' ] = 0;		
		$jet_footer[ 'footercode_index' ] = 0;
		if ( $_POST[ 'jet_footer_enabled' ] == 1 ) 
			$jet_footer[ 'footercode_enabled' ] = 1;
		if ( $_POST[ 'jet_footer_center' ] == 1 ) 
			$jet_footer[ 'footercode_center' ] = 1;
		if ( $_POST[ 'jet_footer_index' ] == 1 ) 
			$jet_footer[ 'footercode_index' ] = 1;				
		if ( $_POST[ 'jet_footer_text' ] != null ) {
			$jet_footer[ 'footercode_code' ] = stripslashes($_POST[ 'jet_footer_text' ]);
		}else{
			$jet_footer[ 'footercode_code' ] = '';
		}
if (stripos($blogversion, 'MU') > 0) {			
		$blogs_ids = get_blog_list( 0, 'all' );
		foreach ($blogs_ids as $blog) {
			update_blog_option( $blog['blog_id'], 'jet_footer', $jet_footer );
		}
	} else {
		update_option( 'jet_footer', $jet_footer );
	}
		echo "<div id='message' class='updated fade'><p>" . __( 'Options updated.', 'jet_footer' ) . "</p></div>";
	}
?>
<div class="wrap">
	<h2><?php _e('Jet Footer Code', 'jet_footer' ) ?></h2>

	<form action="<?php echo site_url() . '/wp-admin/admin.php?page=jet_footer' ?>" name="jet_footer_form" id="jet_footer_form" method="post">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />

		<h3><?php _e( "Let's make some changes:", 'jet_footer' ) ?></h3>
		<table class="form-table">
			<tr valign="top"><?php // JET_FOOTER_ENABLED ?>
				<th scope="row"><label for="jet_footer_enabled"><?php _e( 'Enabled Footer code', 'jet_footer' ) ?></label></th>
				<td>
					<input name="jet_footer_enabled" type="checkbox" id="jet_footer_enabled" value="1"<?php echo( '1' == $jet_footer[ 'footercode_enabled' ] ? ' checked="checked"' : '' ); ?> />
				</td>
			</tr>
			<tr valign="top"><?php // JET_FOOTER_CENTER ?>
				<th scope="row"><label for="jet_footer_center"><?php _e( 'Align to center', 'jet_footer' ) ?></label></th>
				<td>
					<input name="jet_footer_center" type="checkbox" id="jet_footer_center" value="1"<?php echo( '1' == $jet_footer[ 'footercode_center' ] ? ' checked="checked"' : '' ); ?> />
				</td>
			</tr>
			<tr valign="top"><?php // JET_FOOTER_INDEX ?>
				<th scope="row"><label for="jet_footer_index"><?php _e( 'Allow index code', 'jet_footer' ) ?></label></th>
				<td>
					<input name="jet_footer_index" type="checkbox" id="jet_footer_index" value="1"<?php echo( '1' == $jet_footer[ 'footercode_index' ] ? ' checked="checked"' : '' ); ?> />
				</td>
			</tr>			
			<tr valign="top"> <?php // JET_FOOTER_CODE ?>
				<th scope="row"><label for="jet_footer_text"><?php _e( 'Code for footer', 'jet_footer' ) ?></label></th>
				<td>
					<textarea name="jet_footer_text" type="textarea" id="jet_footer_text" style="width: 75%" value="<?php echo attribute_escape( $jet_footer[ 'footercode_code' ] ); ?>" rows="10" cols="45" /><?php echo attribute_escape( $jet_footer[ 'footercode_code' ] ); ?></textarea>
					<br />
				</td>
			</tr>
		</table>
	<p class="submit"><input type="submit" name="submit" value="<?php _e( 'Save Settings', 'jet_footer' ) ?>"/></p>
	</form>

	<p><br /><a href="http://milordk.ru/projects/wordpress-buddypress.html">Other plugins</a></p>
	<ul>
		<li><a href="http://jes.milordk.ru">Jet Event System for BuddyPress</a></li>
		<li><a href="http://milordk.ru/r-lichnoe/opyt/cms/jet-site-unit-could-poleznye-vidzhety-dlya-vashej-socialnoj-seti.html">Jet Site Unit Could</a></li>
	</ul>
	<p><a href="http://milordk.ru/projects/wordpress-buddypress.html">Themes</a></p>
	<ul>
		<li><a href="http://milordk.ru/r-lichnoe/opyt/cms/premium-tema-linoluna-dlya-wordpress.html">Premium Linoluna for WordPress</a></li>
		<li><a href="http://milordk.ru/r-lichnoe/opyt/cms/jet-green-theme-tema-dlya-buddypress-1-2.html">Jet Green Theme. Theme for BuddyPress 1.2</a></li>
		<li><a href="http://milordk.ru/r-lichnoe/opyt/cms/jet-lite-tema-dlya-wordpress-3-0.html">Jet Lite</a></li>
		<li><a href="http://milordk.ru/r-lichnoe/opyt/cms/jet-lite-yellow-%e2%80%93-tema-dlya-wordpress-3-0.html">Jet Lite Yellow</a></li>
		<li><a href="http://milordk.ru/r-lichnoe/opyt/cms/jet-lite-fresh-cut-day-tema-dlya-wordpress-3-0.html">Jet Lite Fresh Cut Day</a></li>
	</ul>
	<p><a href="http://milordk.ru/projects/wordpress-buddypress/podderzhka.html">Donate/поддержка</a></p>
</div>
<?php
}

if (stripos($blogversion, 'MU') > 0) {
add_action( 'wpmu_new_blog', 'jet_footer_new_blogs_options', 10, 2 );
} else {
add_action( 'admin_menu','jet_footer_new_blogs_options');
}

function jet_footer_new_blogs_options( $blog_id ) {
	$jet_footer = get_option( 'jet_footer' );
	$blogversion = get_bloginfo( 'version' );
if (stripos($blogversion, 'MU') > 0) {	
	add_blog_option( $blog_id, 'jet_footer', $jet_footer );
	} else {
	add_option( 'jet_footer', $jet_footer );	
	}
}

function jet_footer_footer () {
	global $bp;
	$data = get_option( 'jet_footer' );
	$indexcheck = $data[ 'footercode_index' ];
	if ( $indexcheck == 0 ) {
	echo '<noindex>';
	}	
	if ( $data[ 'footercode_enabled' ] == 1 ) {
		$key = $data[ 'footercode_code' ];		
	if ( $data[ 'footercode_center' ] == 1 ) {
	echo '<center>';
	}
		echo stripslashes($key);
	if ( $data[ 'footercode_center' ] == 1 ) {
	echo '</center>';
	}		
	}
	if ( $indexcheck == 0 ) {
	echo '</noindex>';
	}	
}

add_action('wp_footer','jet_footer_footer');

register_deactivation_hook( __FILE__, 'jet_footer_deactivation' );

function jet_footer_deactivation() {
	$blogversion = get_bloginfo( 'version' );
if (stripos($blogversion, 'MU') > 0) {
	$blogs_ids = get_blog_list( 0, 'all' );
	foreach ($blogs_ids as $blog) {
		delete_blog_option( $blog['blog_id'], 'jet_footer', $jet_footer );
	}
	} else {
		delete_option( 'jet_footer', $jet_footer );	
	}
}
?>