<?php

use Illuminate\Database\Seeder;

class ColorsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
			['id' => '1','name' => 'black',],
			['id' => '2','name' => 'white',],
			['id' => '3','name' => 'red',],
			['id' => '4','name' => 'blue',],
			['id' => '5','name' => 'camo',],
			['id' => '6','name' => 'multicolor',],
			['id' => '7','name' => 'yellow',],
			['id' => '8','name' => 'purple',],
			['id' => '9','name' => 'green',],
			['id' => '10','name' => 'gray',],
		];
		foreach ($data as $row) {
			$color = new \App\Color($row);
			$color->save();
		}
	}
}
