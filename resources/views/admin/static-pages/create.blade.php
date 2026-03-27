@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Create Static Page</h1>

        <form method="POST" action="{{ route('admin.static-pages.store') }}">
            @csrf
            <div class="form-group">
                <label>Slug</label>
                <select name="slug" class="form-control" required>
                    <option value="privacy-policy">Privacy Policy</option>
                    <option value="terms-and-conditions">Terms and Conditions</option>
                    <option value="faq">FAQ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="8" required></textarea>
            </div>

            <div class="form-group">
                <label>Active</label>
                <select name="is_active" class="form-control" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>

            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
@endsection