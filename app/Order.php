<?php

namespace App;

use App\Jobs\PushNotification;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $guarded = [];
	public $incrementing = false;
	protected $keyType = 'string';
	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_direct' => 'boolean',
	];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
		'confirmed_at',
		'paid_at',
		'shipped_at',
		'delivered_at',
		'completed_at',
		'closed_at',
    ];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function seller()
	{
		return $this->morphTo();
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	

	public function getNotificationTitleAttribute()
	{
		switch ($this->status) {
			case 'created':
				return '您有一个新订单';
			case 'confirmed':
				return '卖家已确认有货';
			case 'paid':
				return '买家已支付';
			case 'shipped':
				return '卖家已发货';
			case 'delivered':
				return '已确认收货';
			case 'completed':
				return '交易完成';
			case 'closed':
				if ($this->reason == 'out of stock') {
					return '订单缺货';
				} else if ($this->reason == 'cancelled by customer') {
					return '交易关闭';
				} else {
					return '交易关闭';
				}
			default:
				return '';
		}
	}

	public function getNotificationBodyAttribute()
	{
		switch ($this->status) {
			case 'created':
				return $this->product->displayName()." 尺码{$this->size}, 请核对库存后确认是否有货";
			case 'confirmed':
				return $this->product->displayName()." 尺码{$this->size}, 请尽快支付";
			case 'paid':
				return $this->product->displayName()." 尺码{$this->size}, 请尽快发货";
			case 'shipped':
				return $this->product->displayName()." 尺码{$this->size}, 请打开包裹检查后再签收";
			case 'delivered':
				return $this->product->displayName()." 尺码{$this->size}";
			case 'completed':
				return $this->product->displayName()." 尺码{$this->size}";
			case 'closed':
				if ($this->reason == 'out of stock') {
					return '抱歉, 您购买的'.$this->product->displayName()." 尺码{$this->size}, 卖家库存已售罄, 交易关闭";
				} else if ($this->reason == 'cancelled by customer') {
					return '抱歉, 您卖出的'.$this->product->displayName()." 尺码{$this->size}, 买家取消订单";
				} else {
					return $this->product->displayName()." 尺码{$this->size}, 关闭原因: {$this->reason}";
				}
			default:
				return $this->product->displayName()." 尺码{$this->size}";
		}
	}

	public function notifyCustomer()
	{		
		foreach($this->user->devices as $device) {
			PushNotification::dispatch(
				$device->token,
				$this->notification_title,
				$this->notification_body, 
				['link' => 'https://www.notdopebxtch.com/user/orders']
			);
		}
	}

	public function notifySeller()
	{
		if ($this->seller instanceof Vendor) {
			foreach($this->seller->users as $user) {
				foreach($user->devices as $device) {
					PushNotification::dispatch(
						$device->token, 
						$this->notification_title,
						$this->notification_body, 
						['link' => 'https://www.notdopebxtch.com/user/orders']
					);
				}
			}
		} else if ($this->seller instanceof Retailer) {
			foreach ($this->seller->vendors as $vendor) {
				foreach($vendor->users as $user) {
					foreach($user->devices as $device) {
						PushNotification::dispatch(
							$device->token, 
							$this->notification_title,
							$this->notification_body, 
							['link' => 'https://www.notdopebxtch.com/user/orders']
						);
					}
				}
			}
		}
	}
}
