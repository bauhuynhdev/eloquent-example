<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'pgsql',
    'host' => '192.168.72.128',
    'database' => 'bau',
    'username' => 'root',
    'password' => 'none',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
], 'pgsql');

$capsule->getDatabaseManager()->extend('mongodb', function($config, $name) {
    $config['name'] = $name;

    return new Jenssegers\Mongodb\Connection($config);
});

$capsule->addConnection([
    'driver' => 'mongodb',
    'host' => '192.168.72.128',
    'database' => 'bau',
    'username' => 'root',
    'password' => 'none',
    'options' => [
        'authSource' => 'admin',
        'replicaSet' => 'rs0',
        'directConnection' => true
    ]
], 'mongodb');

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

class User extends Jenssegers\Mongodb\Eloquent\Model {
    public $timestamps = false;

    // protected $table = 'users';

    protected $primaryKey = 'id';

    protected $collection = 'users';

    protected $connection = 'mongodb';

    protected $fillable = ['name', 'email'];
}

dd(User::all()->toArray());

// User::create([
//     'name' => 'John Doe',
//     'email' => 'bauhuynh2020@gmail.com'
// ]);