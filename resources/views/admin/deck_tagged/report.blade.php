@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Deck tagged ({{ $count }})</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <table id="decks-tagged-table" class="display table table-striped table-bordered table-hover" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Deck ID</th>
                    <th>Deck Name</th>
                    <th>Author ID</th>
                    <th>Author Email</th>
                    <th>Attribute</th>
                    <th>Tag</th>
                    <th>Tag added at</th>
                    <th>Total added by author</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Deck ID</th>
                    <th>Deck Name</th>
                    <th>Author ID</th>
                    <th>Author Email</th>
                    <th>Attribute</th>
                    <th>Tag</th>
                    <th>Tag added at</th>
                    <th>Total added by author</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection