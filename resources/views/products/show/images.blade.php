
<images-slider :images='@json($product->images->pluck('url')->toArray())'></images-slider>
<div class="d-flex justify-content-center">
	@can('update',\App\Image::class)<a href="{{route('images.edit',['product'=>$product])}}">管理图片</a>@endcan
</div>
