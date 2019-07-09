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
			['id' => '1','name' => 'black','name_cn' => '黑色',],
			['id' => '2','name' => 'white','name_cn' => '白色',],
			['id' => '3','name' => 'red','name_cn' => '红色',],
			['id' => '4','name' => 'blue','name_cn' => '蓝色',],
			['id' => '5','name' => 'camo','name_cn' => '迷彩',],
			['id' => '6','name' => 'multicolor','name_cn' => '彩色',],
			['id' => '7','name' => 'yellow','name_cn' => '黄色',],
			['id' => '8','name' => 'purple','name_cn' => '紫色',],
			['id' => '9','name' => 'green','name_cn' => '绿色',],
			['id' => '10','name' => 'gray','name_cn' => '灰色',],
		];
		foreach ($data as $row) {
			$color = new \App\Color($row);
			$color->save();
		}
	}
}
