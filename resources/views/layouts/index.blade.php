@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">

		<div class="col-1">
			<div class="flex-column">
				<div class="">
					<a data-toggle="collapse" href="#filters">筛选</a>
				</div>
				<div class="collapse" id="filters">

						@yield('additional-filters')

					@foreach($filters as $key => $values)
					<div class="pl-3 pt-2">
						<div class="">
							<a data-toggle="collapse" href="#{{$key}}-group">{{$key}}</a>
						</div>

						<div class="form-group pl-3 pt-2 collapse" id="{{$key}}-group">
							@foreach($values as $value)
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}1">
								<label class="form-check-label" for="{{$key}}-{{$value->id}}1">{{$value->name_cn??$value->name}}</label>
							</div>
							<!-- <div class="custom-control custom-checkbox">
							  <input type="checkbox" class="custom-control-input" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}2">
							  <label class="custom-control-label" for="{{$key}}-{{$value->id}}2">{{$value->name_cn??$value->name}}</label>
							</div> -->
							@endforeach
						</div>

					</div>
					@endforeach

				</div>
			</div>
	  </div>
		<div class="col-10">
			@yield('center')
		</div>

		<div class="col-1 d-flex justify-content-center text-right">
			<div class="flex-column">
				<div class="">
					<span>排序+</span>
				</div>

				<div class="form-group pr-3 pt-2">
					@foreach([1,2,3,4] as $j)
					<div class="form-check">
						<input class="form-check-input" type="radio" name="sort[]" value="default{{$j}}" id="default{{$j}}">
						<label class="form-check-label" for="default{{$j}}">默认排序</label>
					</div>
					@endforeach
				</div>
			</div>
	  </div>

	</div>
</div>
@endsection
