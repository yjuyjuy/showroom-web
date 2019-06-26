<div id="images-slider" class="carousel slide" data-touch="true" data-ride="false">
	<div class="carousel-inner">
		@foreach($product->images as $image)
		<div class="carousel-item @if($loop->first) active @endif">
			<img src="/storage/images/{{ $image->filename }}" class="w-100" alt="...">
		</div>
		@endforeach
	</div>
	<div class="row mx-n1 justify-content-start mx-n2 carousel-indicators">
		@foreach($product->images as $i => $image)
		<a href="#" data-target="#images-slider" data-slide-to="{{$i}}" class="col-2 px-2 pb-2 @if($i===0) active @endif">
			<img src="/storage/images/{{ $image->filename }}" class="w-100" alt="...">
		</a>
		@endforeach
	</div>
	<a class="carousel-control-prev" href="#images-slider" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#images-slider" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
