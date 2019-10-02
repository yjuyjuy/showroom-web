@extends('layouts.app')

@section('title', 'Departments - Farfetch')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		<div class="my-3 d-flex justify-content-center flex-wrap">
			@foreach($departments as $department)
				<a href="{{ route('end.departments.index', ['department' => $department,]) }}" class="mdc-typography--headline6 my-2 mx-3">{{ __($department) }}</a>
			@endforeach
		</div>
	</div>
</div>
@endsection
