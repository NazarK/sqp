<?php


require_once 'uses/php-activerecord/ActiveRecord.php';

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory('models');
    $cfg->set_connections(array('development' => 'sqlite://'.SQLITE3_DB));
	$cfg->set_default_connection('development');

});



?>