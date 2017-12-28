@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Uploaded Decks ({{ $count }})</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <table id="decks-table" class="display table table-striped table-bordered table-hover" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th name="test">Deck ID</th>
                    <th>Deck Name</th>
                    <th>Author ID</th>
                    <th>Author Email</th>
                    <th>Created Data</th>
                    <th>Uploaded by author</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Deck ID</th>
                    <th>Deck Name</th>
                    <th>Author ID</th>
                    <th>Author Email</th>
                    <th>Created Data</th>
                    <th>Uploaded by author</th>
                </tr>
                </tfoot>
            </table>
            <a class="btn btn-primary" href="{{ action('DeckEditController@getAdd') }}">Add a new Deck</a>
        </div>
    </div>
@endsection