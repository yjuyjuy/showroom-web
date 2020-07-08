@extends('layouts.app')

@section('title', '下载app')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		<div class="text-center my-3">
			<a href="{{ $links['ios'] }}" class="mdc-typography--headline5">iOS beta</a>
        </div>
	</div>
</div>
@endsection