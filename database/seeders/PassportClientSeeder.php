<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert personal access client into oauth_clients table
        DB::table('oauth_clients')->insert([
            [
                'id' => 1,
                'user_id' => null,
                'name' => 'ProCook Invoice Management Personal Access Client',
                'secret' => '4crItGRxVt32Ln4w1voCbYQZM0mMGd9FWESKS7wu',
                'provider' => null,
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // Insert personal access client reference into oauth_personal_access_clients table
        DB::table('oauth_personal_access_clients')->insert([
            [
                'id' => 1,
                'client_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

    }
}
