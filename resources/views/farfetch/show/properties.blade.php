<div class="d-flex flex-column products-show__info--properties">
	<div class="">
		<a href="{{ route('farfetch.designers.index',['designer' => $product->designer]) }}"
			 class="mdc-typography--headline6" style="text-transform: uppercase;">
			{{ $product->designer->description }}
		</a>

		<a href="{{ route('farfetch.categories.index',['category' => $product->category]) }}"
			 class="mdc-typography--headline6">
			{{ $product->category->description }}
		</a>
	</div>
	<div class="">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->shortDescription }}
		</span>

		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->colors }}
		</span>
	</div>

	<div class="mt-2">
		<span>{{$product->designerStyleId}}</span>
	</div>
</div>
