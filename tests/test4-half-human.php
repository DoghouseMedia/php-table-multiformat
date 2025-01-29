<?php

// Default options test

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTable\Table;
use PHPTable\Manipulator\Base;

include('data.php');

$tablemaker = new \PHPTable\Factory();
$table = $tablemaker->make('half-human');
$table->setTableColor('blue');
$table->setHeaderColor('cyan');
$table->addField('First Name', 'firstName',    false,                               'white');
$table->addField('Last Name',  'lastName',     false,                               'white');
$table->addField('Hobbies',    'hobbies');
$table->addField('DOB',        'dobTime',      new \PHPTable\Manipulator\Base('datelong'));
$table->addField('Admin',      'isAdmin',      new \PHPTable\Manipulator\Base('yesno'),    'yellow');
$table->addField('Last Seen',  'lastSeenTime', new \PHPTable\Manipulator\Base('nicetime'), 'red');
$table->addField('Expires',    'expires',      new \PHPTable\Manipulator\Base('duetime'),  'green');
$table->injectData($data);
$table->display();

