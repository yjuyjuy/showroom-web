<div class="row">
@foreach($product->images as $image)
<div class="col-6 pb-3">
	<img class="w-100" src="/storage/images/{{ $image->filename }}">
</div>
@endforeach
</div>
