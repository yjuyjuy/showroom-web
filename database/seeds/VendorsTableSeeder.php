<?php

use Illuminate\Database\Seeder;

class VendorsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
			['id' => 1,'name' => '家','city' => '中山'],
			['id' => 3,'name' => 'dewin','city' => '天津'],
			['id' => 4,'name' => '阿斐','city' => '金华'],
			['id' => 5,'name' => 'off','city' => '长沙'],
			['id' => 9,'name' => 'adam','city' => '常州'],
			['id' => 12,'name' => 'Dade','city' => '天津'],
			['id' => 14,'name' => 'CT','city' => '台州'],
			['id' => 19,'name' => '淡点','city' => '香港'],
			['id' => 25,'name' => 'Dido','city' => '台州'],
			['id' => 26,'name' => 'FDD','city' => '天津'],
			['id' => 27,'name' => '中山吴亦凡','city' => '大连'],
			['id' => 28,'name' => '易阳','city' => '福州'],
			['id' => 29,'name' => 'whiteman','city' => '上海'],
			['id' => 30,'name' => 'edric','city' => '绍兴'],
			['id' => 32,'name' => 'bren','city' => '长沙'],
			['id' => 33,'name' => '八戒','city' => '绍兴'],
			['id' => 34,'name' => 'Cynic','city' => '香港'],
			['id' => 35,'name' => '丁','city' => '台州'],
			['id' => 36,'name' => '王伟杰','city' => '深圳'],
			['id' => 37,'name' => 'B005T','city' => '香港'],
			['id' => 38,'name' => 'winn66','city' => '厦门'],
			['id' => 39,'name' => '0110','city' => '北京'],
			['id' => 42,'name' => '圈圈','city' => '武汉'],
			['id' => 43,'name' => 'wizm','city' => '杭州'],
			['id' => 44,'name' => '任坦','city' => '长沙'],
		];
		foreach ($data as $row) {
			$user = \App\User::create([
				'username' => $row['name'],
				'email' => 'vendor'.$row['id'].'@test.com',
				'password' => Hash::make('123456789'),
			]);
			$vendor = new \App\Vendor([
				'id' => $row['id'],
				'user_id' => $user->id,
				'name' => $row['name'],
				'city' => $row['city'],
			]);
		}
	}
}
