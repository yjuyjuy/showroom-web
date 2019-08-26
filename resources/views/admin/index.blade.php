@extends('layouts.app')

@section('title', '手动执行程序 - admin')

@section('content')
<div class="d-flex justify-content-center w-100">
	<div class="d-flex flex-column text-center">
		@foreach($functions as $name => $description)
		<div class="my-4 mdc-typography--headline5"><a href="{{ route('admin.call', ['function' => $name,]) }}">{{ $description }}</a></div>
		@endforeach
	</div>
</div>
@endsection
