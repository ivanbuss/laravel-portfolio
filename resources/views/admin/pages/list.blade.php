@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Pages ({{ $count }})</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <table id="pages-table" class="display table table-striped table-bordered table-hover" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
            <a class="btn btn-primary" href="{{ action('Admin\PagesController@getCreatePage') }}">Create a New Page</a>
        </div>
    </div>
@endsection