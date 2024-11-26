@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Export and Import Data</h1>

    <!-- Export Data Form -->
    <form action="{{ route('admin.export') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary my-3">Export Data to Excel</button>
    </form>

    <!-- Import Data Form -->
    <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="excel_file">Upload Edited Excel File:</label>
            <input type="file" name="excel_file" class="form-control" accept=".xlsx, .xls">
        </div>
        <button type="submit" class="btn btn-success my-3">Import Data</button>
    </form>

    @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.insert-data') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Insert 50 Records</button>
    </form>

    <form action="{{ route('admin.removeAllData') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger mt-3">Remove All Data</button>
    </form>
</div>
@endsection