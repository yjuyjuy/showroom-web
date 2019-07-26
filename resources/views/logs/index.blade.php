@extends('layouts.app')

@section('content')
	<div id="logs-index" class="d-flex flex-column px-2">
		@foreach($logs as $log)
		<div class="log-item">
			<a href="{{route('products.show',['product' => $log->product])}}" class="log-content"><span class="timestamp" data-timestamp="{{ $log->created_at->valueOf() }}"></span>&nbsp;{{ $log->toString() }}</a>
			<button class="mdc-icon-button material-icons log-delete-button"
							data-href="{{ route('logs.destroy',['log' => $log]) }}"
							onclick="axios.delete(this.dataset.href).then(response => {this.parentElement.parentElement.removeChild(this.parentElement);});">clear</button>
		</div>
		@endforeach
	</div>
@endsection
