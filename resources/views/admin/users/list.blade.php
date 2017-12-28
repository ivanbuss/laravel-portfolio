@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Users ({{ $count }})</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <table id="users-table" class="display table table-striped table-bordered table-hover" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Last Access</th>
                    <th>Joined</th>
                    <th>Age</th>
                    <th>Country</th>
                    <th>Uploaded</th>
                    <th>Tagged</th>
                    <th>Rated</th>
                    <th>Collect</th>
                    <th>Wish</th>
                    <th>Trade</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Last Access</th>
                    <th>Joined</th>
                    <th>Age</th>
                    <th>Country</th>
                    <th>Uploaded</th>
                    <th>Tagged</th>
                    <th>Rated</th>
                    <th>Collect</th>
                    <th>Wish</th>
                    <th>Trade</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection