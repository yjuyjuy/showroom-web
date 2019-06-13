@extends('vendors.products.index')

@section('title','所有商品 - 管理员 - TheShowroom')

@section('left-aside')
@include('products.availability')
@include('admin.products.vendors')
@include('products.filters')
@endsection
