@extends('layouts.app')

@section('title', '收到的建议 - 管理员功能')

@section('content')
<div class="d-flex justify-content-center mt-4 mx-3">
	<div class="d-flex flex-column">
		@foreach($suggestions as $suggestion)
		<div class="my-4">
			<p class="my-2 mdc-typography--headline6">
				{{ $suggestion->content }}
			</p>
			<div class="text-right">
				<button type="button" class="mdc-button" onclick="window.axios.post('suggestions/archive/{{ $suggestion->id }}').then(response => window.location.reload()).catch(error => window.alert('action failed'));">
					<span class="mdc-button__label">归档</span>
				</button>
			</div>
		</div>
		@endforeach
	</div>
</div>
@endsection
