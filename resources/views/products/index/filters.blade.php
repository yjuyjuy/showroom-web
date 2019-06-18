<div class="form-check">
	<input class="form-check-input" type="checkbox" name="show_available_only" value="show_available_only" id="show_available_only" onchange="submit()" {{ old("show_available_only")? 'checked':'' }}>
	<label class="form-check-label" for="show_available_only">只显示有货</label>
</div>
@foreach(["category" => App\Category::all(),"color" => App\Color::all(),"season" => App\Season::all(),"brand" => App\Brand::all()] as $key => $values)
<a class="" data-toggle="collapse" href="#{{$key}}-group" style="color:var(--blue);">{{$key}}</a>
@foreach($values as $value)
<div class="form-check">
	<input class="form-check-input" type="checkbox" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}" onchange="submit()" {{ (old($key) && in_array($value->id, old($key)))?' checked':'' }}>
	<label class="form-check-label" for="{{$key}}-{{$value->id}}">{{$value->name}}</label>
</div>
@endforeach
@endforeach
