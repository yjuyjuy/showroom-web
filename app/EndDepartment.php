<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EndDepartment extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'end';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'departments';
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
	  * The primary key associated with the table.
	  *
	  * @var string
	  */
	protected $primaryKey = 'name';

	public function products()
	{
		return $this->hasMany(EndProduct::class, 'department_name', 'name');
	}
}
