@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Decks uploaded by user {{ $user->email }} ({{ $count }})</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <table id="users-decks-table" rel="{{ $user->id }}" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Deck ID</th>
                    <th>Deck Name</th>
                    <th>Author ID</th>
                    <th>Author Email</th>
                    <th>Created Data</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Deck ID</th>
                    <th>Deck Name</th>
                    <th>Author ID</th>
                    <th>Author Email</th>
                    <th>Created Data</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection