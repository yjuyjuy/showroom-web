
<carousel :images='@json($product->images->pluck('url')->toArray())'></carousel>
<div class="w-100 text-center">
	@can('update',\App\Image::class)
		<a href="{{route('images.edit',['product'=>$product])}}">管理图片</a>
	@endcan
</div>
