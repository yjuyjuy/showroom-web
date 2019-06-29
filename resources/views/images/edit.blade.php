@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row p-n3">
		@foreach(\App\Type::all() as $type)
		@if($image = $images->firstWhere('type_id',$type->id))
		<div class="col-6 col-md-3 p-3">
			<image-item filename="{{$image->filename}}" id="{{$image->id}}"></image-item>
		</div>
		@else
		<div class="col-6 col-md-3 p-3">
			<div class="d-flex justify-content-center" style="border:solid 0.1em gray;width:100%;height:100%;">
				<a href="#" class="align-self-center" style="color:gray; font-size:50px; text-decoration:none;">{{$type->name}}</a>
			</div>
		</div>
		@endif
		@endforeach
		<div class="col-6 col-md-3 p-3">
			<div class="d-flex justify-content-center" style="border:solid 0.1em gray;width:100%;height:100%;">
				<a href="#" class="align-self-center" style="color:gray; font-size:50px; text-decoration:none;">+</a>
			</div>
		</div>

	</div>
</div>
@endsection
