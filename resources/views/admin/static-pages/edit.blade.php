@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Static Page #{{ $page->id }}</h1>

        <form method="POST" action="{{ route('admin.static-pages.update', $page->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Slug</label>
                <select name="slug" class="form-control" required>
                    <option value="privacy-policy" {{ $page->slug === 'privacy-policy' ? 'selected' : '' }}>Privacy Policy</option>
                    <option value="terms-and-conditions" {{ $page->slug === 'terms-and-conditions' ? 'selected' : '' }}>Terms and Conditions</option>
                    <option value="faq" {{ $page->slug === 'faq' ? 'selected' : '' }}>FAQ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $page->title }}" required>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="8" required>{{ $page->content }}</textarea>
            </div>

            <div class="form-group">
                <label>Active</label>
                <select name="is_active" class="form-control" required>
                    <option value="1" {{ $page->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ ! $page->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ $page->sort_order }}">
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection