@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">

		<div class="col-2 justify-content-center">
			@section('left-aside')
				<div class="p-2 text-center" style="border: black solid 1px">
					left-aside
				</div>
			@endsection
			@yield('left-aside')
		</div>

		<div class="col-8">
			@section('center')
				<div class="p-2 text-center" style="border: black solid 1px">
					center
				</div>
			@endsection
			@yield('center')
		</div>

		<div class="col-2 justify-content-center">
			@section('right-aside')
				<div class="p-2 text-center" style="border: black solid 1px">
					right-aside
				</div>
			@endsection
			@yield('right-aside')
		</div>

	</div>
</div>
@endsection
