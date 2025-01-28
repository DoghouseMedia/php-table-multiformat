<?php

// Test with different table border characters

require_once __DIR__ . '/../vendor/autoload.php';

use \PHPTable\Table;
use \PHPTable\Manipulator\Base;

include('data.php');

$tablemaker = new \PHPTable\Factory();
$table = $tablemaker->make('human-only');

$table->setChars(array(
    'top'          => '-',
    'top-mid'      => '+',
    'top-left'     => '+',
    'top-right'    => '+',
    'bottom'       => '-',
    'bottom-mid'   => '+',
    'bottom-left'  => '+',
    'bottom-right' => '+',
    'left'         => '|',
    'left-mid'     => '+',
    'mid'          => '-',
    'mid-mid'      => '+',
    'right'        => '|',
    'right-mid'    => '+',
    'middle'       => '| ',
));

$table->setTableColor('green');
$table->setHeaderColor('yellow');
$table->addField('First Name', 'firstName',    false,                               'cyan');
$table->addField('Last Name',  'lastName',     false,                               'cyan');
$table->addField('Hobbies',    'hobbies');
$table->addField('DOB',        'dobTime',      new \PHPTable\Manipulator\Base('datelong'));
$table->addField('Admin',      'isAdmin',      new \PHPTable\Manipulator\Base('yesno'),    'yellow');
$table->addField('Last Seen',  'lastSeenTime', new \PHPTable\Manipulator\Base('nicetime'), 'red');
$table->addField('Expires',    'expires',      new \PHPTable\Manipulator\Base('duetime'),  'white');
$table->injectData($data);
$table->display();

