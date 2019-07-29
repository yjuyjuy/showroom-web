<images-slider :images='@json($product->images->pluck('url')->toArray())'></images-slider>
