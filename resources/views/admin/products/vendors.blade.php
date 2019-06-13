<div class="pl-2 pt-1">

	<div class="">
		<a class="" data-toggle="collapse" href="#available-vendors-group" style="color:var(--blue);">货源</a>
	</div>

	<div class="form-group pl-2 pt-1 mb-0 collapse show" id="available-vendors-group">
		@foreach(App\Vendor::all() as $vendor)
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="vendor[]" value="{{$vendor->id}}" id="vendor-{{$vendor->id}}" onchange="submit()" {{ (old("vendor") && in_array("{$vendor->id}",old("vendor")))?' checked':'' }}>
			<label class="form-check-label" for="vendor-{{$vendor->id}}">{{ $vendor->name }}</label>
		</div>
		@endforeach
	</div>

</div>
