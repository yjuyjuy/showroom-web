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
		$this->call([
			BrandsTableSeeder::class,
			CategoriesTableSeeder::class,
			ColorsTableSeeder::class,
			// PricesTableSeeder::class,
			// ProductsTableSeeder::class,
			SeasonsTableSeeder::class,
			// VendorsTableSeeder::class,
			WebsitesTableSeeder::class,
		]);
		$user = new \App\User([
					'id' => 1,
					'name' => 'JIAYOU YUAN',
					'email' => 'yjuyjuy@gmail.com',
					'password' => Hash::make('123456789'),
					'username' => 'admin',
		]);
		$user->save();
		$vendor = \App\Vendor::firstOrNew(
			[ 'id' => 1, 'name' => '家', 'city' => '中山', ]
		);
		$user->vendor()->save($vendor);
	}
}
