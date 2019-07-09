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
			['id' => '1101','name' => 't-shirt','name_cn' => '短袖T恤',],
			['id' => '1102','name' => 'long sleeve','name_cn' => '长袖T恤',],
			['id' => '1103','name' => 'crewneck','name_cn' => '圆领卫衣',],
			['id' => '1104','name' => 'sweater','name_cn' => '毛衣',],
			['id' => '1105','name' => 'hoodie','name_cn' => '套头帽衫',],
			['id' => '1106','name' => 'zipped hoodie','name_cn' => '拉链帽衫',],
			['id' => '1107','name' => 'jacket','name_cn' => '外套夹克',],
			['id' => '1108','name' => 'shirt','name_cn' => '衬衫',],
			['id' => '1201','name' => 'shorts','name_cn' => '短裤',],
			['id' => '1202','name' => 'pants','name_cn' => '长裤',],
			['id' => '1203','name' => 'jeans','name_cn' => '牛仔裤',],
			['id' => '2101','name' => 'backpack','name_cn' => '双肩包',],
			['id' => '2102','name' => 'other bag','name_cn' => '其他包类',],
			['id' => '2201','name' => 'accessories','name_cn' => '配件',],
		];
		foreach ($data as $row) {
			$category = new \App\Category($row);
			$category->save();
		}
	}
}
