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
		$user = new \App\User([
					'id' => 1,
					'email' => 'yjuyjuy@gmail.com',
					'password' => Hash::make('123456789'),
					'username' => 'Dope',
		]);
		$user->save();
		$this->call([
			BrandsTableSeeder::class,
			CategoriesTableSeeder::class,
			ColorsTableSeeder::class,
			SeasonsTableSeeder::class,
			VendorsTableSeeder::class,
			WebsitesTableSeeder::class,
			TypesTableSeeder::class,
			ProductsTableSeeder::class,
			PricesTableSeeder::class,
		]);
		$vendor = \App\Vendor::find(1);
		$user->vendor()->associate($vendor)->save();
	}
}
