<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $guarded = [];

	public static $types = [
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

	public function scopeFront($query)
	{
		return $query->whereIn('type_id', [1,2,9,10,11])->orderBy('website_id', 'asc')->orderBy('type_id', 'asc');
	}
	public function scopeBack($query)
	{
		return $query->whereIn('type_id', [3,4,12])->orderBy('website_id', 'asc')->orderBy('type_id', 'asc');
	}
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function website()
	{
		return $this->belongsTo(Website::class);
	}
	public function type()
	{
		return $this->belongsTo(Type::class);
	}
	public function getUrlAttribute()
	{
		return asset('storage/'.$this->path);
	}
}
