@extends('layouts.admin')

@section('title', 'Create Product')
@section('page-title', 'Products')
@section('page-subtitle', 'Create a new product')

@section('actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to list</a>
@endsection

@section('content')
    <section class="panel mb-6">
        <div class="section-title">
            <h2 class="text-lg font-semibold text-gray-900">Add Product</h2>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white shadow-sm rounded-lg p-6">
            @csrf
            @include('admin._product_form_fields', ['editingProduct' => null, 'categories' => $categories])

            <div class="flex items-center gap-3 mt-6">
                <button class="btn btn-primary" type="submit">Add Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </section>
@endsection
