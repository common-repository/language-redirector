<?php
/*
Plugin Name:       Language Redirector
Plugin URI:        https://wordpress.org/plugins/language-redirector/
Description:       Plugin that performs a country-based redirect
Version:           1.0.1
Author:            Ilario Tresoldi
Author URI:        http://www.webcreates.eu
Textdomain:        lr
Domain Path:       /language
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

/**
 * Language Redirector
 * Copyright (C) 2017 Ilario Tresoldi. All rights reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Contact the author at ilario.tresoldi@gmail.com
 */

/**
 * Start the plugin
 */
function lre_init() {
	$domain = 'lr';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	$path   = plugins_url('language-redirector/language/'.$domain.'-'.$locale.'.mo');
	$loaded = load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/language/' );
	if ( !$loaded )
	{
		$path   = plugins_url('language-redirector/language/'.$domain.'-en_US.mo');
		$loaded = load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/language/' );
	}

	register_setting('language-redirector', 'language_redirector_options', 'lre_language_redirector_options_sanitize');
	register_setting('language-redirector-url', 'language_redirector_options_url', 'lre_language_redirector_options_utl_sanitize');

	if ( !is_admin() ) {
		require_once( 'classes/class.lr.php' );
	}
}
add_action( 'plugins_loaded', 'lre_init' );

/**
 * Add in option menu
 */
function add_lr( $methods ) {
	add_menu_page('Language Redirector', 'Language Redirector', 'manage_options', 'language-redirector', 'lre_language_redirector_setup', plugins_url('language-redirector/images/translation.png'));
	add_submenu_page('language-redirector', 'Language Redirector Country Url', 'Language Redirector Country Url', 'manage_options', 'lre_language_redirector_setup_url', 'lre_language_redirector_setup_url');
}
add_filter( 'admin_menu', 'add_lr' );

function lre_language_redirector_options_sanitize($input){
    $input['lr_enable'] = $input['lr_enable'];
    return $input;
}

function lre_language_redirector_options_utl_sanitize($input){
    $input['lr_country1'] = sanitize_text_field($input['lr_country1']);
    $input['lr_url1'] = esc_url($input['lr_url1']);
    $input['lr_country2'] = sanitize_text_field($input['lr_country2']);
    $input['lr_url2'] = esc_url($input['lr_url2']);
    return $input;
}

function lre_language_redirector_setup()
{
?>
	<h1>Language Redirector Free Version - <?php _e( 'Setup', 'lr' ); ?></h1>
	<form method="post" action="options.php">
		<?php settings_fields( 'language-redirector' ); ?>
		<?php $language_redirector_options = get_option('language_redirector_options'); ?>
		<br>
		<b><?php _e( 'Plugin activation', 'lr' ); ?></b>
		<br>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Active', 'lr' ); ?>:</th>
				<td><input name="language_redirector_options[lr_enable]" type="checkbox" value="1" <?php checked($language_redirector_options['lr_enable']);?> /></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="senddata" class="button-primary" value="<?php _e( 'Save changes', 'lr' ) ?>" />
		</p>
	</form>
<?php
}

function lre_language_redirector_setup_url()
{
?>
	<h1>Language Redirector Free Version - <?php _e( 'Setup', 'lr' ); ?></h1>
	<b><?php _e( 'Country redirection', 'lr' ); ?></b>
	<br>
	<form method="post" action="options.php">
		<?php settings_fields( 'language-redirector-url' ); ?>
		<?php $language_redirector_options_url = get_option('language_redirector_options_url'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Country', 'lr' ); ?></th>
				<td><input name="language_redirector_options_url[lr_country1]" type="text" value="<?php echo $language_redirector_options_url['lr_country1']; ?>" /></td>
				<th scope="row"><?php _e( 'Redirect to', 'lr' ); ?></th>
				<td><input name="language_redirector_options_url[lr_url1]" type="text" value="<?php echo $language_redirector_options_url['lr_url1']; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Country', 'lr' ); ?></th>
				<td><input name="language_redirector_options_url[lr_country2]" type="text" value="<?php echo $language_redirector_options_url['lr_country2']; ?>" /></td>
				<th scope="row"><?php _e( 'Redirect to', 'lr' ); ?></th>
				<td><input name="language_redirector_options_url[lr_url2]" type="text" value="<?php echo $language_redirector_options_url['lr_url2']; ?>" /></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="senddata" class="button-primary" value="<?php _e( 'Save changes', 'lr' ) ?>" />
		</p>
	</form>
<?php
}
?>
