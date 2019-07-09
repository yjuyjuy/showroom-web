<?php

use Illuminate\Database\Seeder;

class WebsitesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
			['id' => 1,'name' => 'off---white'],
			['id' => 2,'name' => 'farfetch'],
			['id' => 3,'name' => 'antonioli'],
			['id' => 4,'name' => 'Dopebxtch'],
			['id' => 5,'name' => 'ssense'],
			['id' => 6,'name' => 'endclothing'],
			['id' => 7,'name' => 'selfridges'],
			['id' => 8,'name' => 'matchesfashion'],
			['id' => 9,'name' => 'luisaviaroma'],
			['id' => 10,'name' => 'vrient'],
			['id' => 11,'name' => 'lindelepalais'],
			['id' => 12,'name' => 'revolve'],
		];
		foreach ($data as $row) {
			$website = new \App\Website([
				'id' => $row['id'],
				'name' => $row['name'],
				'url' => 'www.'.$row['name'].'.com',
			]);
			$website->save();
		}
	}
}
