
<images-slider :images='@json($product->images->pluck('url')->toArray())'></images-slider>
<div class="w-100 text-center">
	@can('update',\App\Image::class)
		<a href="{{route('images.edit',['product'=>$product])}}"
			 onclick="event.preventDefault(); window.location.replace(this.href);">管理图片</a>
	@else
		<span>(长按小图保存图片)</span>
	@endcan
</div>
