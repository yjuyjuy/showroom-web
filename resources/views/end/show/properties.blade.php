<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<a href="{{ route('end.brands.index',['brand' => $product->brand]) }}"
			 class="mdc-typography--headline6" style="text-transform: uppercase;">
			{{ $product->brand }}
		</a>
		<a href="{{ route('end.departments.index',['department' => $product->department]) }}"
			 class="mdc-typography--headline6">
			{{ __($product->department) }}
		</a>
	</div>
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->name }}
		</span>

		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->color }}
		</span>
	</div>
	<div class="my-1">
		<span>{{$product->sku}}</span>
	</div>

	<div class="my-2"></div>

	@if($product->sizing_blurb)
	<div class="my-1">
		<span>{{ $product->sizing_blurb }}</span>
	</div>
	@endif

	@if($product->description)
	<div class="my-1">
		{!! $product->description !!}
	</div>
	@endif
</div>
