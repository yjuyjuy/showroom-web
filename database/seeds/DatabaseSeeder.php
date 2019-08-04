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
		$user = \App\User::find(1);
		$user->password = Hash::make('Tptz9kKZ48rTefK');
		$user->save();
		$user = \App\User::where('email','admin@dopebxtch.com')->first();
		$user->password = Hash::make('iZ92pa9knDN3taT');
		$user->save();
	}
}
