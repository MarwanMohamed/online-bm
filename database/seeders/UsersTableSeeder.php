<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        if(!User::where('email','admin@test.com')->first())
        {
            $user = User::create( [
                'name' => 'admin' ,
                'email' => 'admin@test.com' ,
                'password' => Hash::make( 'password' ) ,
            ] );

            $user->assignRole('admin');
        }
    }
}
