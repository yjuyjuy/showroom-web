@csrf

<div class="form-group row">
	<label for="brand" class="col-md-4 col-form-label text-md-right">品牌</label>

	<div class="col-md-6">
		<select id="brand" class="form-control @error('brand') is-invalid @enderror" name="brand" value="" autofocus>
			<option value="" hidden></option>
			@foreach(\App\Brand::all() as $brand)
			<option value="{{ $brand->id }}" {{ (old('brand') ?? ($product->brand->id ?? null)) == $brand->id ? 'selected':'' }}>{{ $brand->name }}</option>
			@endforeach
		</select>
		@error('brand')
		<span class="invalid-feedback" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
	</div>
</div>

<div class="form-group row">
	<label for="season" class="col-md-4 col-form-label text-md-right">季度</label>

	<div class="col-md-6">
		<select id="season" class="form-control @error('season') is-invalid @enderror" name="season" value="">
			<option value="" hidden></option>
			@foreach(\App\Season::all() as $season)
			<option value="{{ $season->id }}" {{ (old('season') ?? ($product->season->id ?? null)) == $season->id ? 'selected':'' }}>{{ $season->name }}</option>
			@endforeach
		</select>
		@error('season')
		<span class="invalid-feedback" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
	</div>
</div>

<div class="form-group row">
	<label for="name_cn" class="col-md-4 col-form-label text-md-right">款式名称</label>

	<div class="col-md-6">
		<input id="name_cn" type="text" class="form-control @error('name_cn') is-invalid @enderror" name="name_cn" value="{{ old('name_cn') ?? $product->name_cn }}">

		@error('name_cn')
		<span class="invalid-feedback" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
	</div>
</div>

<div class="form-group row">
	<label for="name" class="col-md-4 col-form-label text-md-right">英文名</label>

	<div class="col-md-6">
		<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $product->name }}">

		@error('name')
		<span class="invalid-feedback" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
	</div>
</div>

<div class="form-group row">
	<label for="category" class="col-md-4 col-form-label text-md-right">类别</label>

	<div class="col-md-6">
		<select id="category" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category') }}">
			<option value="" hidden></option>
			@foreach(\App\Category::all() as $category)
			<option value="{{ $category->id }}" {{ (old('category') ?? ($product->category->id ?? null)) == $category->id ? 'selected':'' }}>{{ $category->name_cn }}</option>
			@endforeach
		</select>
		@error('category')
		<span class="invalid-feedback" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
	</div>
</div>

<div class="form-group row">
	<label for="color" class="col-md-4 col-form-label text-md-right">颜色</label>

	<div class="col-md-6">
		<select id="color" class="form-control @error('color') is-invalid @enderror" name="color" value="{{ old('color') }}">
			<option value="" hidden></option>
			@foreach(\App\Color::all() as $color)
			<option value="{{ $color->id }}" {{ (old('color') ?? ($product->color->id ?? null)) == $color->id ? 'selected':'' }}>{{ $color->name_cn }}</option>
			@endforeach
		</select>
		@error('color')
		<span class="invalid-feedback" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
	</div>
</div>

<div class="form-group row">
	<label for="comment" class="col-md-4 col-form-label text-md-right">备注</label>

	<div class="col-md-6">
		<textarea id="comment" class="form-control @error('comment') is-invalid @enderror" name="comment">{{ old('comment') ?? $product->comment }}</textarea>
		@error('comment')
		<span class="invalid-feedback" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
	</div>
</div>
