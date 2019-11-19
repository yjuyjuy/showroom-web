@extends('layouts.app')

@section('title', '新增用户 - 管理员功能')

@section('content')
<div class="d-flex justify-content-center mt-4 mx-3">
	<div class="d-flex flex-column">
	@foreach(\App\User::latest()->limit(100)->get() as $user)
		<div class="my-2">
			<span class="mdc-typography--headline5">{{ $user->username }} - {{ $user->email }} - {{ $user->created_at }}</span>
		</div>
	@endforeach
	@include('layouts.pages')
</div>
@endsection
