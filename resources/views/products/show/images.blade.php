
<carousel :images='@json($product->images->pluck('url')->toArray())'></carousel>
<div class="w-100 text-center">
	@can('update',\App\Image::class)
		<a href="{{route('images.edit',['product'=>$product])}}">管理图片</a>
	@elsecan('create', \App\Image::class)
			<input type="file" name="images" form="upload-image-form" onchange="axios_submit(this);" multiple>
			<form id="upload-image-form" class="" action="{{ route('images.store') }}" method="post" style="display:none;">
				@csrf
				<input type="text" name="product_id" value="{{ $product->id }}">
			</form>
	@endcan
</div>
