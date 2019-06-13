@extends('layouts.show')

@section('title',$product->brand->name.' '.$product->season->name.' '.$product->name_cn.' - TheShowroom')

@section('left-aside')
@include('products.properties')
@endsection


@section('center')
@include('products.images')
@endsection


@section('right-aside')
@include('products.size_price')
@endsection
