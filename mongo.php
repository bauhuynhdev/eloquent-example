<?php

require_once __DIR__ . '/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule = new Capsule;
$capsule->getDatabaseManager()->extend(
    'mongodb',
    function ($config, $name) {
        $config['name'] = $name;
        return new Jenssegers\Mongodb\Connection($config);
    }
);
$capsule->addConnection(
    [
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
    ]
);
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$time = 0;
Capsule::listen(
    function ($query) use (&$time) {
//        var_dump($query->sql);
//        var_dump($query->time);
        $time += $query->time;
    }
);

class User extends Jenssegers\Mongodb\Eloquent\Model
{
    public $timestamps = false;
    protected $collection = 'users';
    protected $fillable = ['name', 'email'];
}

User::all();

//$faker = Faker\Factory::create();
//for ($i = 1; $i <= 200000; $i++) {
//    User::create(
//        [
//            'email' => $faker->email(),
//            'name' => $faker->name()
//        ]
//    );
//}

var_dump('Total time: ' . $time);