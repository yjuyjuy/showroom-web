@extends('products.index')

@section('title','所有商品 - '.auth()->user()->vendor->name.' - TheShowroom')

@section('left-aside')
@include('products.availability')
@include('products.filters')
@endsection
