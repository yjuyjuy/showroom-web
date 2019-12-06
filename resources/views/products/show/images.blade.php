
<carousel :images='@json($product->images->pluck('url')->toArray())'></carousel>
<div class="d-flex justify-content-between">
	<div class="">
		<span>左翻街拍图</span>
	</div>
	@can('update',\App\Image::class)
	<div class="">
		<a href="{{route('images.edit',['product'=>$product])}}">管理图片</a>
	</div>
	@endcan
	@can('create', \App\Image::class)
	<div class="">
		<a href="#" onclick="event.preventDefault(); document.getElementById('images-input').click();">上传图片</a>
		<form id="upload-image-form" class="" action="{{ route('images.store') }}" method="post" style="display:none;">
			@csrf
			<input type="text" name="product_id" value="{{ $product->id }}">
			<input id="images-input" type="file" name="images" form="upload-image-form" onchange="axios_submit(this);" multiple>
		</form>
	</div>
	@endcan
	<div class="">
		<span>右翻模特图</span>
	</div>
</div>
