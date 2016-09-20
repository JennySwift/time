<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

	public function run()
	{
		User::truncate();

		$jenny = User::create([
			'name' => 'Jenny',
			'email' => 'cheezyspaghetti@gmail.com',
			'password' => bcrypt('abcdefg')
		]);

        $john = User::create([
            'name' => 'John',
            'email' => 'cheezyspaghetti@optusnet.com.au',
            'password' => bcrypt('abcdefg')
        ]);
	}

}