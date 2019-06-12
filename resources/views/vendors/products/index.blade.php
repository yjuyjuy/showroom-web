@extends('products.index')

@section('left-aside')
<div class="pl-2 pt-1">
	<div class="">
		<a class="" data-toggle="collapse" href="#availability-group" style="color:var(--blue);">选项</a>
	</div>

	<div class="form-group pl-2 pt-1 mb-0 collapse show" id="availability-group">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="show_available" value="show_available" id="show_available" onchange="submit()" {{ old("show_available")? 'checked':'' }}>
			<label class="form-check-label" for="show_available">只显示有货</label>
		</div>
		<!-- <div class="form-check">
			<input class="form-check-input" type="checkbox" name="show_unavailable" value="show_unavailable" id="show_unavailable" onchange="submit()"
			{{ old("show_unavailable")? 'checked':'' }}>
			<label class="form-check-label" for="show_unavailable">只显示无货</label>
		</div> -->

	</div>

</div>
@parent
@endsection
