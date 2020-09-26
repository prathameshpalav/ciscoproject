<?php

use App\Router;
use Illuminate\Database\Seeder;

class RouterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $n = 10; // number of records you want to insert
        
        factory(App\Router::class, $n)->create();
    }
}
