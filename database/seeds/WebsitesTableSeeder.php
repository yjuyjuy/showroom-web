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
			['id' => 1, 'name' => 'off---white', 'url' => 'www.off---white.com'],
			['id' => 2, 'name' => 'farfetch', 'url' => 'www.farfetch.com'],
			['id' => 3, 'name' => 'antonioli', 'url' => 'www.antonioli.eu'],
			['id' => 4, 'name' => 'Dopebxtch', 'url' => 'www.Dopebxtch.com'],
			['id' => 5, 'name' => 'ssense', 'url' => 'www.ssense.com'],
			['id' => 6, 'name' => 'endclothing', 'url' => 'www.endclothing.com'],
			['id' => 7, 'name' => 'selfridges', 'url' => 'www.selfridges.com'],
			['id' => 8, 'name' => 'matchesfashion', 'url' => 'www.matchesfashion.com'],
			['id' => 9, 'name' => 'luisaviaroma', 'url' => 'www.luisaviaroma.com'],
			['id' => 10,'name' => 'vrient', 'url' => 'www.vrient.com'],
			['id' => 11,'name' => 'lindelepalais', 'url' => 'www.lindelepalais.com'],
			['id' => 12,'name' => 'revolve', 'url' => 'www.revolve.com'],
		];
		foreach ($data as $row) {
			$website = new \App\Website($row);
			$website->save();
		}
	}
}
