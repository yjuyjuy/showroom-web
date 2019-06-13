@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">

		<div class="col-3 offset-2">
				@yield('left-aside')
		</div>

		<div class="col-5">
				@yield('right-aside')
		</div>

	</div>
</div>
@endsection
