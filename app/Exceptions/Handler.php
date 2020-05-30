<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the throwable types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation throwables.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an throwable.
	 *
	 * @param  \Throwable  $throwable
	 * @return void
	 */
	public function report(Throwable $throwable)
	{
		parent::report($throwable);
	}

	/**
	 * Render an throwable into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Throwable  $throwable
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Throwable $throwable)
	{
		return parent::render($request, $throwable);
	}
}
