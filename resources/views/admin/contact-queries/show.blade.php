@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Contact Query #{{ $query->id }}</h1>

        <div class="mb-3"><strong>Name:</strong> {{ $query->name }}</div>
        <div class="mb-3"><strong>Email:</strong> {{ $query->email }}</div>
        <div class="mb-3"><strong>Subject:</strong> {{ $query->subject }}</div>
        <div class="mb-3"><strong>Message:</strong><p>{{ $query->message }}</p></div>
        <div class="mb-3"><strong>Status:</strong> {{ $query->is_resolved ? 'Resolved' : 'Open' }} </div>

        <form method="POST" action="{{ route('admin.contact-queries.updateStatus', $query->id) }}">
            @csrf
            <label>Change status</label>
            <select name="is_resolved" class="form-control">
                <option value="0" {{ ! $query->is_resolved ? 'selected' : '' }}>Open</option>
                <option value="1" {{ $query->is_resolved ? 'selected' : '' }}>Resolved</option>
            </select>
            <button type="submit" class="btn btn-success mt-2">Update Status</button>
        </form>

        <a href="{{ route('admin.contact-queries.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
@endsection