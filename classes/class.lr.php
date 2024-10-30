<?php
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

function lre_getUserIP() {
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    }
    else {
        $ip = $remote;
    }
    return $ip;
}

add_action('template_redirect', function() {
	$tabella = $wpdb->prefix."lr_redirector";
	$language_redirector_options = get_option('language_redirector_options');
	$language_redirector_options_url = get_option('language_redirector_options_url');
	
	if ( $language_redirector_options['lr_enable'] ) {
		$ip = lre_getUserIP();
		$link = "http://freegeoip.net/json/".$ip;
		$response = wp_remote_get($link);
		$data = wp_remote_retrieve_body( $response );
		$details = json_decode($data);

		$url = "";
		if (strtolower($details->country_name) == strtolower($language_redirector_options_url['lr_country1'])) {
			$countryname = $language_redirector_options_url['lr_country1'];
			$url = $language_redirector_options_url['lr_url1'];
		}
		if (strtolower($details->country_name) == strtolower($language_redirector_options_url['lr_country2'])) {
			$countryname = $language_redirector_options_url['lr_country2'];
			$url = $language_redirector_options_url['lr_url2'];
		}
		if ($url != "") {
			wp_redirect( $url );
			exit();
		}
	}
})
?>
