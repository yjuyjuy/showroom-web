<a id="open-sort" class="text-primary open-overlay" href="#" @click="$refs.sort.classList.toggle('open');">Sort</a>
<div id="sort" ref="sort" class="form-group overlay">
	<a class="close-overlay" href="#" @click="$refs.sort.classList.remove('open');">&times;</a>
	@foreach($sortMethods as $method)
	<div class="form-check">
		<input class="form-check-input" type="radio" name="sort" value="{{ $method['name'] }}" id="{{ $method['name'] }}" onchange="submit()" {{ (old('sort')==$method['name']) || (!old('sort') && $method['name']=='default')?' checked':'' }}>
		<label class="form-check-label" for="{{ $method['name'] }}">{{ $method['name_cn'] }}</label>
	</div>
	@endforeach
</div>
