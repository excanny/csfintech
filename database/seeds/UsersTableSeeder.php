<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role=Role::create(['name' => 'SUPER_ADMIN']);
        \Illuminate\Support\Facades\DB::table('users')->truncate();
        $user = \App\Model\User::create([
            'business_id' => 0,
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'superadmin@sagecloud.ng',
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10)
        ]);

//        $user->assignRole('SUPER_ADMIN');
        $user->assignRole($role);
//        $user->assignRole('MERCHANT');


//        factory(User::class, 10)->create();
    }
}
