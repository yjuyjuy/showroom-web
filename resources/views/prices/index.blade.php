@extends('layouts.app')

@section('title',__('Price sheet').' - '.$vendor->name)

@section('content')
<div class="d-flex flex-column align-items-center">
	@if(auth()->user()->isSuperAdmin())
		<div class="mdc-select mdc-select--autosubmit mb-4 optional-form-field" data-form="change-vendor-form">
		  <input type="hidden" name="vendor" form="change-vendor-form">
		  <i class="mdc-select__dropdown-icon"></i>
		  <div class="mdc-select__selected-text"></div>
		  <div class="mdc-select__menu mdc-menu mdc-menu-surface">
		    <ul class="mdc-list">
					@foreach(\App\Vendor::all() as $v)
		      <li class="mdc-list-item" data-value="{{ $v->id }}">{{ $v->name }}</li>
		      @endforeach
		    </ul>
		  </div>
		  <span class="mdc-floating-label">{{ __('vendor') }}</span>
		  <div class="mdc-line-ripple"></div>
		</div>
		<form id="change-vendor-form" method="get" target="_self" class="d-none"></form>
	@endif

	@foreach($products as $product)
	<div class="images-content-container mb-4 w-100">
		<div class="images-container">
			<div class="d-flex">
				<div class="mx-2 w-50">
					<img src="{{$product->images[0]->url ?? asset('storage/icons/ImagePlaceholder.svg')}}">
				</div>
				<div class="mx-2 w-50">
					<img src="{{$product->images[1]->url ?? asset('storage/icons/ImagePlaceholder.svg')}}">
				</div>
			</div>
		</div>
		<div class="content-container d-flex flex-column justify-content-center align-items-center">
			<div class="price-grid p-4">
				<div class="price-grid__header d-flex flex-column">
					<span>{{ $product->brand->full_name }}</span>
					<a href="{{route('products.show',['product' => $product])}}" class="font-weight-bold">{{ $product->name_cn }}</a>
				</div>
				<div class="price-grid__row">
					<span class="price-grid__col">{{ __('size') }}</span>
					<span class="price-grid__col">{{ __('cost') }}</span>
					<span class="price-grid__col">{{ __('offer') }}</span>
					<span class="price-grid__col">{{ __('retail') }}</span>
				</div>
				@foreach($product->prices as $price)
					@foreach($price->data as $row)
						<div class="price-grid__row">
							<span class="price-grid__col">{{ $row['size'] }}</span>
							<span class="price-grid__col">&yen;{{$row['cost']}}</span>
							<span class="price-grid__col">&yen;{{$row['offer']}}</span>
							<span class="price-grid__col">&yen;{{$row['retail']}}</span>
						</div>
					@endforeach
					<div class="price-grid__footer d-flex justify-content-end">
						<a href="{{route('prices.edit',['price'=>$price])}}" class="mdc-button">{{ __('edit') }}</a>
						<a href="#" class="mdc-button mdc-button--error" @click.prevent="deletePrice({{$price->id}})">{{ __('delete') }}</a>
					</div>
				@endforeach
			</div>
		</div>
	</div>
	@endforeach
</div>
@endsection
