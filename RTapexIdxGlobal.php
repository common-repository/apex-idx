<?php
/**
 * @package ApexIdx
 * @version 3.1.3
 */
/*
Plugin Name: ApexIdx
Plugin URI: http://realtytech.com
Description: This plugin is used to show the listing of a Broker.
Author: RealtyTech Inc
Version: 3.1.3
Author URI: http://realtytech.com
*/

define( 'RTAI_VERSION', '3.1.3');
define( 'RTAI_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define( 'RTAI_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define( 'RTAI_DELETE_LIMIT', 100000 );
define( 'RTAI_PLUGIN_FILE_LOC', plugin_basename( __FILE__ ));

register_activation_hook( __FILE__, array( 'RTapexIdxBase', 'RTapexActivation' ) );
register_uninstall_hook(__FILE__, array( 'RTapexIdxBase', 'RTapexRemove' ));

require_once ( RTAI_PLUGIN_DIR . 'RTapexIdxBaseApi.php' );
require_once( RTAI_PLUGIN_DIR . 'class.RTapexIdxBase.php' );

add_action( 'init', array( 'RTapexIdxBase', 'RTapexInit' ) );
