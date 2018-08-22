<?php
/**
 * @package PostgreSQL_For_Wordpress
 * @version $Id$
 * @author	Hawk__, www.hawkix.net
 */

/**
* This file does all the initialisation tasks
*/

// Logs are put in the pg4wp directory
define( 'PG4WP_LOG', PG4WP_ROOT.'/logs/');
// Check if the logs directory is needed and exists or create it if possible
if( (PG4WP_DEBUG || PG4WP_LOG_ERRORS) &&
	!file_exists( PG4WP_LOG) &&
	is_writable(dirname( PG4WP_LOG)))
	mkdir( PG4WP_LOG);

// Load the driver defined in 'db.php'

$replaces = array(
    'split('	=> 'explode(',
    'strpos($lastq,\'ID\')' => 'strpos($lastq,\'"ID"\')',
    '<?php'		=> '',
);
eval(str_replace(array_keys($replaces), array_values($replaces), file_get_contents(PG4WP_ROOT.'/driver_'.DB_DRIVER.'.php')));

// This loads up the wpdb class applying appropriate changes to it
$replaces = array(
//	'define( '	=> '// define( ',
//    'OBJECT'  => 'OBJECT_PG4WP',
//    '// define( \'OBJECT_PG4WP\', \'OBJECT_PG4WP\' );'	=> 'if (!defined(\'OBJECT_PG4WP\')) define( \'OBJECT_PG4WP\', \'OBJECT\' );',
//    'OBJECT_PG4WP_K'	=> 'OBJECT_K',
//    '// define( \'OBJECT_K\', \'OBJECT_K\' );'  => 'if (!defined(\'OBJECT_K\')) define( \'OBJECT_K\', \'OBJECT_K\' );',
//    '// define( \'ARRAY_A\', \'ARRAY_A\' );'	=> 'if (!defined(\'ARRAY_A\')) define( \'ARRAY_A\', \'ARRAY_A\' );',
//    '// define( \'ARRAY_N\', \'ARRAY_N\' );'	=> 'if (!defined(\'ARRAY_N\')) define( \'ARRAY_N\', \'ARRAY_N\' );',
    '$this->set_sql_mode();'	=> '//$this->set_sql_mode();',
    'if ( ! empty( $this->dbh ) && mysql_ping( $this->dbh ) ) {' => 'if ( ! empty( $this->dbh ) ) {',
    '$table = \'`\' . implode( \'`.`\', $table_parts ) . \'`\';' => '$table = \'\\\'\' . implode( \'`.`\', $table_parts ) . \'\\\'\';',
    '"SHOW FULL COLUMNS FROM $table"' => '\'SELECT * FROM information_schema.columns WHERE table_catalog = \\\'\'.DB_NAME.\'\\\' AND table_name = \'.$table',
    '$column->Field' => '$column->column_name',
    '->Type' => '->data_type',
    'is_resource( $this->dbh )' => 'true',
    'class wpdb'	=> 'class wpdb2',
	'new wpdb'	=> 'new wpdb2',
	'mysql_'	=> 'wpsql_',
	'public function query( $query ) {' => "public function query( \$query ) {\n        \$query = preg_replace('/meta_value\s<\s(.*)/', 'meta_value < \'\$1\'', \$query);",
	'public function get_results( $query = null, $output = OBJECT ) {' => "public function get_results( \$query = null, \$output = OBJECT ) {\n        if (strcmp(\$query, 'SHOW VARIABLES LIKE \\'sql_mode\\'') === 0) return '';",
	'<?php'		=> '',
	'?>'		=> '',
);
eval(str_replace(array_keys($replaces), array_values($replaces), file_get_contents(ABSPATH.WPINC.'/wp-db.php')));

// Create wpdb object if not already done
if (! isset($wpdb) || ! $wpdb instanceof wpdb2)
	$wpdb = new wpdb2( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
