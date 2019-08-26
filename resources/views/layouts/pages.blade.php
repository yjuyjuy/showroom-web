<div class="d-flex w-100 justify-content-center">
		@if($page > 1)
		<a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['page' => $page - 1,])) }}" class="mdc-button">
			<span class="mdc-button__label">上一页</span>
		</a>
		@endif
		@if($page < $total_pages)
		<a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['page' => $page + 1,])) }}" class="mdc-button">
			<span class="mdc-button__label">下一页</span>
		</a>
		@endif
</div>
