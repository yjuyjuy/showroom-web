@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">

			<div class="col-2 vh-100 justify-content-center d-flex" style="position:absolute; top:0; left:0;">
			@section('left-aside')
			<div class="p-2 text-center" style="border: black solid 1px">
				left-aside
			</div>
			@endsection
			@yield('left-aside')
		</div>

		<div class="col-8 offset-2">
			@section('center')
			<div class="p-2 text-center" style="border: black solid 1px">
				center
			</div>
			@endsection
			@yield('center')
		</div>

		<div class="col-2 min-vh-100 justify-content-center align-content-center d-flex flex-column text-left" style="position:absolute; right:0;">

				@section('right-aside')
				<div class="py-4 my-4 col-10 col-md-8 border">
					right-aside
				</div>
				@endsection
				@yield('right-aside')

		</div>

	</div>
</div>
@endsection
