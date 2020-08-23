
<carousel :images='@json($product->images->pluck('url')->toArray())'></carousel>
<div class="d-flex justify-content-center">
	@if(auth()->user() && auth()->user()->is_admin)
	<div class="">
		<a href="{{route('images.edit',['product'=>$product])}}">管理图片</a>
	</div>
	@endif
</div>
