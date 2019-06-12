@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<form action="{{ url()->current() }}">
		<div class="row">

			<div class="col-2 d-flex justify-content-end text-left">
				<div class="flex-column">
					@yield('left-aside')
				</div>
		  </div>

			<div class="col-8">
				<div class="row">
				@yield('center')
				</div>
			</div>

			<div class="col-2 d-flex justify-content-start text-right">
				<div class="flex-column">
					@yield('right-aside')
				</div>
		  </div>

		</div>
	</form>
</div>
@endsection
