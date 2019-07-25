<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
			['id' => 1,'name' => 'front','angle' => 'front'],
			['id' => 2,'name' => 'front-angled','angle' => 'front'],
			['id' => 3,'name' => 'back','angle' => 'back'],
			['id' => 4,'name' => 'back-angled','angle' => 'back'],
			['id' => 5,'name' => 'detail1','angle' => 'close-up'],
			['id' => 6,'name' => 'detail2','angle' => 'close-up'],
			['id' => 7,'name' => 'detail3','angle' => 'close-up'],
			['id' => 8,'name' => 'detail4','angle' => 'close-up'],
			['id' => 9,'name' => 'pose1','angle' => 'front'],
			['id' => 10,'name' => 'pose2','angle' => 'back'],
			['id' => 11,'name' => 'cover1','angle' => 'front'],
			['id' => 12,'name' => 'cover1','angle' => 'back'],
			['id' => 14,'name' => 'side','angle' => 'back'],
		];
		foreach ($data as $row) {
			$type = new \App\Type($row);
			$type->save();
		}
	}
}
