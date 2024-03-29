<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: uppercase;">
			Balenciaga&nbsp;-&nbsp;
		</span>
		<a href="{{ route('balenciaga.departments.index',['department' => $product->department]) }}"
			 class="mdc-typography--headline6">
			 {{ __($product->department) }}
		</a>
	</div>

	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->name }}
		</span>
	</div>
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->description }}
		</span>
	</div>

	<div class="my-1">
		货号: {{ $product->designer_style_id }}
	</div>

	<div class="my-2"></div>

	<div class="my-1">
		{{ $product->composition }}
	</div>

	<div class="my-1">
		@foreach(explode("\n", $product->detail) as $row)
		@if(trim($row))
		<span>{{ $row }}</span><br>
		@endif
		@endforeach
	</div>
</div>
