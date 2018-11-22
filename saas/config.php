<?php declare(strict_types=1);
/**
 * config.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



//--- service settings
const SYS = array(
	'name'                  => 'ST',
	'domain'                => 'localhost.test',
	'protocol'              => 'http',
	'dev_mode'              => true,
	'date_default_timezone' => 'UTC',   # Timezone default php
	'session_timeout_sec'   => 7200     # Session/cache expire
);



//--- sys paths
const SYS_PATH = array(
	'root'           => __DIR__.'/',
	'models'         => __DIR__.'/models/sys/',
	'domain_objects' => __DIR__.'/models/sys/domain_objects/',
	'data_mappers'   => __DIR__.'/models/sys/data_mappers/',
	'services'       => __DIR__.'/models/sys/services/',
	'views'          => __DIR__.'/views/sys/',
	'controllers'    => __DIR__.'/controllers/sys/',
	'lib'            => __DIR__.'/lib/',
	'tpl'            => __DIR__.'/views/sys/templates/'
);


//--- sys urls
const SYS_URL = array(
	'root' => '/',
	'css'  => '/css/sys/',
	'img'  => '/img/sys/',
	'js'   => '/js/sys/'
);
