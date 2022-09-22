<?php

require_once __DIR__ . '/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule = new Capsule;
$capsule->addConnection(
    [
        'driver' => 'pgsql',
        'host' => '192.168.72.128',
        'database' => 'bau',
        'username' => 'root',
        'password' => 'none',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
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

class User extends Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;
    protected $table = 'users';
    protected $primaryKey = 'email';
    protected $fillable = ['name', 'email'];
}

//User::all();

$faker = Faker\Factory::create();
for ($i = 1; $i <= 10000; $i++) {
    User::create(
        [
            'email' => $faker->email(),
            'name' => $faker->name()
        ]
    );
}

var_dump('Total time: ' . $time);