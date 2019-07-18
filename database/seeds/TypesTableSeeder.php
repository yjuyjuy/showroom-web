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
			['id' => 1,'name' => 'front','name_cn' => '正面','angle' => 'front'],
			['id' => 2,'name' => 'front-angled','name_cn' => '正侧面','angle' => 'front'],
			['id' => 3,'name' => 'back','name_cn' => '背面','angle' => 'back'],
			['id' => 4,'name' => 'back-angled','name_cn' => '背侧面','angle' => 'back'],
			['id' => 5,'name' => 'detail1','name_cn' => '细节1','angle' => 'close-up'],
			['id' => 6,'name' => 'detail2','name_cn' => '细节2','angle' => 'close-up'],
			['id' => 7,'name' => 'detail3','name_cn' => '细节3','angle' => 'close-up'],
			['id' => 8,'name' => 'detail4','name_cn' => '细节4','angle' => 'close-up'],
			['id' => 9,'name' => 'pose1','name_cn' => '全身1','angle' => 'front'],
			['id' => 10,'name' => 'pose2','name_cn' => '全身2','angle' => 'back'],
			['id' => 11,'name' => 'cover1','name_cn' => '正面平铺','angle' => 'front'],
			['id' => 12,'name' => 'cover1','name_cn' => '反面平铺','angle' => 'back'],
			['id' => 14,'name' => 'side','name_cn' => '侧面','angle' => 'back'],
		];
		foreach ($data as $row) {
			$type = new \App\Type($row);
			$type->save();
		}
	}
}
