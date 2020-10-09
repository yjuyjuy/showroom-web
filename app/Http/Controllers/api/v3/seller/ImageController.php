<?php

namespace App\Http\Controllers\api\v3\seller;

use App\Image;
use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store()
    {
        umask(0);
        request()->validate([
            'product_id' => ['required', 'exists:products,id'],
            'image' => ['required_without:images', 'file', 'mimetypes:image/*', 'max:10000'],
            'images.*' => ['required_without:image', 'file', 'mimetypes:image/*', 'max:10000'],
            'order' => ['required_with:image', 'numeric', 'min:1'],
        ]);
        if (request('images')) {
            $order = Product::find(request('product_id'))->images()->max('order');
            foreach (request('images') as $uploadedFile) {
                $order += 1;
                $path = $uploadedFile->store('images/' . request('product_id'), 'public');
                Image::create([
                    'path' => $path,
                    'source' => $uploadedFile->getClientOriginalName(),
                    'product_id' => request('product_id'),
                    'order' => $order,
                ]);
            }
        } else {
            $path = request('image')->store('images/' . request('product_id'), 'public');
            Image::create([
                'path' => $path,
                'source' => request('image')->getClientOriginalName(),
                'product_id' => request('product_id'),
                'order' => request('order'),
            ]);
        }
        return ['success'];
    }
}
