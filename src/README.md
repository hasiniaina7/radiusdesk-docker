# Building Containers
To build the containers I edited `supervisord.conf` in the original repo (find it [here](supervisord.conf)) under the 
docker folder and I changed [rdcore/cake4/rd_cake/config/app_local.php](supervisord.conf) here:
```
'Datasources' => [
    'default' => [
        'host' => 'rdmariadb',
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Mysql',
        'persistent' => false,
        'username' => 'rd',
        'password' => 'rd',
        'database' => 'rd',
        'encoding' => 'utf8mb4',
        'timezone' => 'UTC',
        'cacheMetadata' => true,
        'log' => false,
        'url' => env('DATABASE_URL', null),
    ],
],
```
In future this should be done in environment variables.