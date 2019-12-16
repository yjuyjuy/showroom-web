<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SsenseBrand extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'ssense';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'brands';
  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'url_token';
  /**
   * Indicates if the IDs are auto-incrementing.
   *
   * @var bool
   */
  public $incrementing = false;
  /**
   * The "type" of the auto-incrementing ID.
   *
   * @var string
   */
  protected $keyType = 'string';
	/**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

	public function products()
	{
		return $this->hasMany(SsenseProduct::class, 'brand_name', 'name');
	}
}
