@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center w-100">
	<div class="d-flex flex-column">
		@foreach($logs as $log)

		@endforeach
	</div>
</div>
@endsection
