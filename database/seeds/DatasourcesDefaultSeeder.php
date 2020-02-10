<?php

use Illuminate\Database\Seeder;
use App\DatasourceDefault;
use App\Database;

class DatasourcesDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Database::create([
            'username' => 'username',
            'password' => 'password',
            'port' => 3033,
            'host' => 'localhost',
            'driver' => 'mysql',
            'name' => 'ExampleDatabase'
        ]);


        DatasourceDefault::create([
            'table_associated' => 'files',
            'query' => '',
            'id_database' => 1,
        ]);

        DatasourceDefault::create([
            'table_associated' => 'tickets',
            'query' => '',
            'id_database' => 1,
        ]);
    }
}
