<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
                    $this->call(RolesTableSeeder::class);
                    $this->call(PermissionsTableSeeder::class);
                    $this->call(ProductsTableSeeder::class);
                    $this->call(ServiceCodesTableSeeder::class);
                    $this->call(DataPlansTableSeeder::class);
                    $this->call(BillersTableSeeder::class);
                    $this->call(ProvidersTableSeeder::class);
                    $this->call(CapricornDataPlansTableSeeder::class);
                    $this->call(KycTableSeeder::class);
                    $this->call(UsersTableSeeder::class);
    }
}
