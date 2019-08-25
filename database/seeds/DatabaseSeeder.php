<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		\App\User::where('email', 'yjuyjuy@gmail.com')->update(['password' => Hash::make('Tptz9kKZ48rTefK'),]);
		\App\User::where('email', 'admin@dopebxtch.com')->update(['password' => Hash::make('iZ92pa9knDN3taT'),]);
		\App\User::where('email', 'user@dopebxtch.com')->update(['password' => Hash::make('9SckaJsgDHiznAh'),]);
		\App\User::where('email', 'vendor@dopebxtch.com')->update(['password' => Hash::make('Ku47dCJwrQKP7hv'),]);
		\App\User::where('email', 'reseller@dopebxtch.com')->update(['password' => Hash::make('5U2U5H8upHzAZrv'),]);

		// $admin->is_reseller = true;
		// $admin->save();
		// $vendor = \App\Vendor::where('name', 'Dopebxtch')->first();
		// $vendor->users()->save($admin);
		//
		// if($farfetch_vendor = \App\Vendor::where('name','Farfetch')->first()) {
		// 	$farfetch_vendor->delete();
		// }
		//
		// foreach(\App\Vendor::all() as $vendor){
		// 	if(!$vendor->retailer){
		// 		$retailer = new \App\Retailer(['name' => $vendor->name, 'id' => $vendor->id]);
		// 		$retailer->save();
		// 		$vendor->retailer()->associate($retailer);
		// 		$vendor->save();
		// 	}
		// }
		//
		// if(!\App\Retailer::where('name', 'Farfetch')->first()){
		// 	$retailer = new \App\Retailer();
		// 	$retailer->name = "Farfetch";
		// 	$retailer->homepage = "https://www.farfetch.com/";
		// 	$retailer->save();
		// }
		//
		// $admin->vendors()->sync(\App\Vendor::all()->pluck('id'));
		// $admin->following()->sync(\App\Retailer::all()->pluck('id'));
		// $admin->vendor->retailer->partner_vendors()->sync(\App\Vendor::where('id','<>',$admin->vendor->id)->get()->pluck('id'));
		// $admin->vendor->retailer->followers()->sync(\App\User::all()->pluck('id'));
	}
}
