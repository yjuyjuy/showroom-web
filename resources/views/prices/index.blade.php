@extends('layouts.app')

@section('title', '价格表 - '.$vendor->name)

@section('content')
<div class="d-flex flex-column align-items-center">
	<div class="d-flex mx-3 align-items-start flex-wrap">
		<form id="filter-vendor-form" method="get" target="_self" class="d-none"></form>
		
		<div class="mdc-select mdc-select--autosubmit mb-4 optional-form-field" data-form="filter-vendor-form">
		<input type="hidden" name="brand" form="filter-vendor-form">
		<i class="mdc-select__dropdown-icon"></i>
		<div class="mdc-select__selected-text"></div>
		<div class="mdc-select__menu mdc-menu mdc-menu-surface">
			<ul class="mdc-list">
				<li class="mdc-list-item {{ empty(old('brand')) ? 'mdc-list-item--selected' : '' }}"
					data-value="">{{ __('all brands') }}</li>
				@foreach(\App\Brand::find($vendor->products()->pluck('brand_id')->unique()) as $brand)
					<li class="mdc-list-item {{ old('brand') == $brand->id ? 'mdc-list-item--selected' : '' }}" data-value="{{ $brand->id }}">{{ $brand->name }}</li>
				@endforeach
			</ul>
		</div>
		<span class="mdc-floating-label">{{ __('brand') }}</span>
		<div class="mdc-line-ripple"></div>
		</div>

		<div class="mx-2"></div>

		@if($user && $user->is_admin)
			<div class="mdc-select mdc-select--autosubmit mb-4 optional-form-field" data-form="filter-vendor-form">
			<input type="hidden" name="vendor" form="filter-vendor-form">
			<i class="mdc-select__dropdown-icon"></i>
			<div class="mdc-select__selected-text"></div>
			<div class="mdc-select__menu mdc-menu mdc-menu-surface">
				<ul class="mdc-list">
						@foreach(\App\Vendor::all() as $v)
				<li class="mdc-list-item {{ old('vendor') == $v->id ? 'mdc-list-item--selected' : '' }}" data-value="{{ $v->id }}">{{ $v->name }}</li>
				@endforeach
				</ul>
			</div>
			<span class="mdc-floating-label">{{ __('vendor') }}</span>
			<div class="mdc-line-ripple"></div>
			</div>
		@elseif(old('vendor'))
			<input type="hidden" name="vendor" form="filter-vendor-form" value="{{ old('vendor') }}">
		@endif
	</div>
	@foreach($products as $product)
	<div class="container mb-4 w-100">
		<div class="">
			<div class="d-flex">
				<div class="mx-2 w-50">
					<img class="lazy" data-src="{{$product->images[0]->small ?? secure_asset('storage/icons/ImagePlaceholder.svg')}}">
				</div>
				<div class="mx-2 w-50">
					<img class="lazy" data-src="{{$product->images->where('order', 3)->first()->small ?? $product->images[1]->small ?? secure_asset('storage/icons/ImagePlaceholder.svg')}}">
				</div>
			</div>
		</div>
		<div class="container__content d-flex flex-column justify-content-center align-items-center">
			<div class="price-grid p-4">
				<div class="price-grid__header d-flex flex-column">
					<span>{{ $product->brand->full_name }}</span>
					<a href="{{route('products.show',['product' => $product])}}" class="font-weight-bold">{{ $product->name_cn }}</a>
				</div>
				<div class="price-grid__row">
					<span class="price-grid__col">{{ __('size') }}</span>
					<span class="price-grid__col">{{ __('offer') }}</span>
					<span class="price-grid__col">{{ __('retail') }}</span>
					<span class="price-grid__col">{{ __('stock') }}</span>
				</div>
				@foreach($product->prices as $price)
					@foreach($price->data as $row)
						<div class="price-grid__row">
							<span class="price-grid__col">{{ $row['size'] }}</span>
							<span class="price-grid__col">&yen;{{$row['offer']}}</span>
							<span class="price-grid__col">&yen;{{$row['retail']}}</span>
							<div class="price-grid__col d-flex justify-content-between align-items-center" style="margin: -12px;">
								<button type="button" class="mdc-icon-button material-icons"
										@if($can_edit) onclick="axios.post('{{ route('prices.subtract', ['price' => $price, 'size' => $row['size']]) }}').then(response=>window.location.reload()).catch(error=>window.alert('action failed'))" @endif>remove</button>
								<span>{{ $row['stock'] ?? 999 }}</span>
								<button type="button" class="mdc-icon-button material-icons" 
										@if($can_edit) onclick="axios.post('{{ route('prices.add', ['price' => $price, 'size' => $row['size']]) }}').then(response=>window.location.reload()).catch(error=>window.alert('action failed'))" @endif>add</button>
							</div>
						</div>
					@endforeach
					<div class="price-grid__footer d-flex justify-content-end">
						<a href="{{route('prices.edit',['price'=>$price])}}" class="mdc-button">{{ __('edit') }}</a>
						<button type="button" class="mdc-button mdc-button--error" 
								@if($can_edit) onclick="axios.delete('/prices/{{$price->id}}').then(response => window.location.reload()).catch(error => window.alert('action failed'));" @endif>
							<span class="mdc-button__label">{{ __('delete') }}</span>
						</button>
					</div>
				@endforeach
			</div>
		</div>
	</div>
	@endforeach
</div>
@endsection
