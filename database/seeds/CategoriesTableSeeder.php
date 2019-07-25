<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
			['id' => '1101','name' => 't-shirt'],
			['id' => '1102','name' => 'long sleeve',],
			['id' => '1103','name' => 'crewneck',],
			['id' => '1104','name' => 'sweater',],
			['id' => '1105','name' => 'hoodie',],
			['id' => '1106','name' => 'zipped hoodie',],
			['id' => '1107','name' => 'jacket',],
			['id' => '1108','name' => 'shirt',],
			['id' => '1201','name' => 'shorts',],
			['id' => '1202','name' => 'pants',],
			['id' => '1203','name' => 'jeans',],
			['id' => '2101','name' => 'backpack',],
			['id' => '2102','name' => 'other bag',],
			['id' => '2201','name' => 'accessories',],
		];
		foreach ($data as $row) {
			$category = new \App\Category($row);
			$category->save();
		}
	}
}
