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

	<div class="form-group pl-2 pt-1 mb-0 collapse show" id="{{$key}}-group">
		@foreach($values as $value)
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}" onchange="submit()" {{ (old("{$key}") && in_array("{$value->id}",old("{$key}")))?' checked':'' }}>
			<label class="form-check-label" for="{{$key}}-{{$value->id}}">{{$value->name_cn??$value->name}}</label>
		</div>
		@endforeach
	</div>

</div>
@endforeach
