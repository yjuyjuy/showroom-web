
<carousel :images='@json($product->images->pluck('url')->toArray())'></carousel>
<div class="w-100 text-center">
	<span>提示: 长按小图保存图片</span>
	@can('update',\App\Image::class)
		<a href="{{route('images.edit',['product'=>$product])}}">管理图片</a>
	@endcan
</div>
