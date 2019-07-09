<?php

use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
			['id' => '1','name' => 'Off-White','full_name' => 'OFF-WHITE C/O VIRGIL ABLOH',],
			['id' => '2','name' => 'Thom Browne','full_name' => 'Thom Browne',],
		];
		foreach ($data as $row) {
			$brand = new \App\Brand($row);
			$brand->save();
		}
	}
}
