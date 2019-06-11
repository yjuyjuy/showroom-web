@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<form>
		<div class="row">

			<div class="col-2 d-flex justify-content-end text-left">
				@section('left-aside')
				<div class="flex-column">
					<div class="">
						<a data-toggle="collapse" href="#filters" style="color:var(--blue);">筛选</a>
					</div>
					<div class="collapse show" id="filters">
						@foreach([
							"category" => App\Category::all(),
							"color" => App\Color::all(),
							"season" => App\Season::all(),
							"brand" => App\Brand::all(),
						] as $key => $values)
						<div class="pl-2 pt-1">
							<div class="">
								<a class="" data-toggle="collapse" href="#{{$key}}-group" style="color:var(--blue);">{{$key}}</a>
							</div>

							<div class="form-group pl-2 pt-1 collapse show" id="{{$key}}-group">
								@foreach($values as $value)
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}1" onchange="submit()" {{ (old("{$key}") && in_array("{$value->id}",old("{$key}")))?' checked':'' }}>
									<label class="form-check-label" for="{{$key}}-{{$value->id}}1">{{$value->name_cn??$value->name}}</label>
								</div>
								@endforeach
							</div>

						</div>
						@endforeach

					</div>
				</div>
				@show
		  </div>

			<div class="col-8">
				@section('center')
				<div class="container-fluid">
					<div class="row">

					@foreach($products as $product)
					<div class="product col-3 flex-column align-items-center" data-category="{{$product->category->id}}" data-color="{{$product->color->id}}" data-season="{{$product->season->id}}" data-brand="{{$product->brand->id}}">

						<div class="image">
							<a href="{{ route('products.show',['product' => $product->id ]) }}">
								<img class="w-100" src="/storage/images/{{ $product->images->first()->filename ?? '1101182005_1_6.jpg' }}">
							</a>
						</div>

						<div class="text mt-n4">
								<div class="flex-column text-center">

									<div class="brand text-left pl-3">
										<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none" style="color:var(--red);">{{ $product->brand->name }}</a>
									</div>

									<div class="name">
										<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none" style="color:var(--red);">{{ $product->name_cn }}</a>
									</div>

									<div class="price">
										<a href="{{ route('products.show',['product' => $product->id ]) }}" style="color:var(--red);">{{ $product->displayPrice() }}</a>
									</div>

								</div>
						</div>

					</div>
					@endforeach
				  </div>

				</div>
				@endsection
				@yield('center')
			</div>

			<div class="col-2 d-flex justify-content-start text-right">
				@section('right-aside')
				<div class="flex-column">
					<div class="">
						<a data-toggle="collapse" href="#sort-checkboxes" style="color:var(--blue);">排序</a>
					</div>

					<div class="form-group pr-3 pt-2 collapse show" id="sort-checkboxes">
						@foreach(App\Sortmethod::all() as $value)
						<div class="form-check">
							<input class="form-check-input" type="radio" name="sort" value="{{ $value->name }}" id="{{ $value->name }}" onchange="submit()"{{ (old('sort')==$value->name) || (!old('sort') && $value->name=='default')?' checked':'' }}>
							<label class="form-check-label" for="{{ $value->name }}">{{ $value->name_cn }}</label>
						</div>
						@endforeach
					</div>
				</div>
				@show
		  </div>

		</div>
	</form>
</div>
@endsection
