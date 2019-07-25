<?php

use Illuminate\Database\Seeder;

class SeasonsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
				['id' => '141','name' => '14ss',],
				['id' => '142','name' => '14fw',],
				['id' => '151','name' => '15ss',],
				['id' => '152','name' => '15fw',],
				['id' => '161','name' => '16ss',],
				['id' => '162','name' => '16fw',],
				['id' => '171','name' => '17ss',],
				['id' => '172','name' => '17fw',],
				['id' => '181','name' => '18ss',],
				['id' => '182','name' => '18fw',],
				['id' => '191','name' => '19ss',],
				['id' => '192','name' => '19fw',],
				['id' => '201','name' => '20ss',],
				['id' => '202','name' => '20fw',],
			];
		foreach ($data as $row) {
			$season = new \App\Season($row);
			$season->save();
		}
	}
}
