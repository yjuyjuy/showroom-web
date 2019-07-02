@extends('layouts.app')

@section('content')
<div class="container" style="position:relative;">
	<div class="" style="position:absolute; top:0; left:15px; z-index:1;">
		<a href="#website-list" data-toggle="collapse">more websites</a>
		<div id="website-list" class="collapse pb-2" style="background-color:#282c34;">
			@foreach($websites->whereNotIn('id',$images->keys()) as $website)
			<div class="form-check">
				<input type="checkbox" class="form-check-input" value="{{$website->id}}" onchange="
					let x = document.querySelector('#website' + this.value);
					(this.checked)? x.classList.add('show'): x.classList.remove('show');
					" id="website-list-{{$website->id}}">
				<label class="form-check-label" for="website-list-{{$website->id}}">{{$website->name}}</label>
			</div>
			@endforeach
		</div>
	</div>
	@foreach($images as $website_id => $website_images)
	<div id="website{{$website_id}}" class="row">
		<div class="col-12 h3 text-center">{{$websites->firstWhere('id',$website_id)->name}}</div>
		@foreach($types as $type)
		<div class="col-6 col-md-3 pb-3">
			@if($image = $website_images->firstWhere('type_id',$type->id))
			<image-item filename="{{$image->filename}}" id="{{$image->id}}"></image-item>
			@else
			<empty-image product-id="{{$product->id}}" website-id="{{$website_id}}" type-id="{{$type->id}}"></empty-image>
			@endif
			<span>{{$type->name}}</span>
		</div>
		@endforeach
	</div>
	@endforeach

	@foreach($websites->whereNotIn('id',$images->keys()) as $website)
	<div id="website{{$website->id}}" class="row website-empty">
		<div class="col-12 h3 text-center">{{$website->name}}</div>
		@foreach($types as $type)
		<div class="col-6 col-md-3 pb-3">
			<empty-image product-id="{{$product->id}}" website-id="{{$website->id}}" type-id="{{$type->id}}"></empty-image>
			<span>{{$type->name}}</span>
		</div>
		@endforeach
	</div>
	@endforeach
</div>
@endsection
