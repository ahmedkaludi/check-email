<?php
/*
* Plugin Name: 				Check & Log Email
* Description: 				Check & Log email allows you to test if your WordPress installation is sending emails correctly and logs every email.
* Author: 					MachoThemes
* Version: 					1.0.0
* Author URI: 				https://www.machothemes.com/
* License: 					GPLv3 or later
* License URI:         		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 	    	5.6
* Text Domain: 				check-email
* Domain Path: 				/languages
*
* Copyright 2015-2020 		Chris Taylor 		chris@stillbreathing.co.uk
* Copyright 2020 		    MachoThemes 		office@machothemes.com
*
* NOTE:
* Chris Taylor transferred ownership rights on: 2020-06-19 07:52:03 GMT when ownership was handed over to MachoThemes
* The MachoThemes ownership period started on: 2020-06-19 07:52:03 GMT
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License, version 3, as
* published by the Free Software Foundation.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
	function check_email_compatibility_notice() {
		?>
		<div class="error">
			<p>
				<?php
				printf(
					__( 'Check & Log Email requires at least PHP 5.6 to function properly. Please upgrade PHP.', 'check-email' )
				);
				?>
			</p>
		</div>
		<?php
	}

	add_action( 'admin_notices', 'check_email_compatibility_notice' );

	/**
	 * Deactivate Email Log.
	 */
	function check_email_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	add_action( 'admin_init', 'check_email_deactivate' );

	return;
}

/*
 * Continue if php version is > 5.6
 */

require_once 'class-check-email-review.php';
require_once 'check-email-log.php';

check_email_log( __FILE__ );