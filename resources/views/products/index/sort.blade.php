<a id="open-sort" class="text-primary open-overlay" href="#" @click="$refs.sort.classList.toggle('open');">Sort</a>
<div id="sort" ref="sort" class="form-group overlay">
	<a class="close-overlay" href="#" @click="$refs.sort.classList.remove('open');">&times;</a>
	@foreach(\App\Sortmethod::all() as $value)
	<div class="form-check">
		<input class="form-check-input" type="radio" name="sort" value="{{ $value->name }}" id="{{ $value->name }}" onchange="submit()" {{ (old('sort')==$value->name) || (!old('sort') && $value->name=='default')?' checked':'' }}>
		<label class="form-check-label" for="{{ $value->name }}">{{ $value->name_cn }}</label>
	</div>
	@endforeach
</div>
