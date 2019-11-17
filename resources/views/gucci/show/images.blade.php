<carousel :images='@json($product->images->pluck('url')->toArray())'></carousel>
