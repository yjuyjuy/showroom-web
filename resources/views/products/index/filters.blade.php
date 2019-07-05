<a id="open-filter" class="text-primary open-overlay" href="#" @click="$refs.filter.style.width='100%';">Filter</a>
<div id="filter" ref="filter" class="overlay">
	<a class="close-overlay" href="#" @click="$refs.filter.style.width='';">&times;</a>
	<div class="form-check">
		<input class="form-check-input" type="checkbox" name="show_available_only" value="show_available_only" id="show_available_only" onchange="submit()" {{ old("show_available_only")? 'checked':'' }}>
		<label class="form-check-label" for="show_available_only">只显示有货</label>
	</div>
	<div class="w-100"></div>
		@if(($user = auth()->user()) && $user->vendor)
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="show_vendor_only" value="{{$user->vendor->id}}" id="show_vendor_only" onchange="submit()" {{ old("show_vendor_only")? 'checked':'' }}>
			<label class="form-check-label" for="show_vendor_only">我的库存</label>
		</div>
		<div class="w-100"></div>

			@if($user->isSuperAdmin())
			<a class="" data-toggle="collapse" href="#vendor-group" style="color:var(--blue);">货源</a>
			<div class="collapse" id="vendor-group">
				@foreach(\App\Vendor::all() as $vendor)
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="vendor[]" value="{{$vendor->id}}" id="vendor-{{$vendor->id}}" onchange="submit()" {{ (old('vendor') && in_array($vendor->id, old('vendor')))?' checked':'' }}>
					<label class="form-check-label" for="vendor-{{$vendor->id}}">{{$vendor->name}}</label>
				</div>
				@endforeach
			</div>
			<div class=""></div>
			@endif
		@endif

	@foreach(["category" => App\Category::all(),"color" => App\Color::all(),"season" => App\Season::all(),"brand" => App\Brand::all()] as $key => $values)
	<a class="" data-toggle="collapse" href="#{{$key}}-group" style="color:var(--blue);">{{$key}}</a>
	<div class="collapse show" id="{{$key}}-group">
		@foreach($values as $value)
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}" onchange="submit()" {{ (old($key) && in_array($value->id, old($key)))?' checked':'' }}>
			<label class="form-check-label" for="{{$key}}-{{$value->id}}">{{$value->name}}</label>
		</div>
		@endforeach
	</div>
	<div class=""></div>
	@endforeach
</div>
