@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Migration</div>
                    <div class="panel-body">
                        {!! Form::open([
                                'action'=>['P52\MigrateController@migratePost'],
                                'class'=>'form-horizontal',
                                'role'=>'form',
                                'files' => true,
                         ]) !!}

                        {!! Form::submit('Migrate users', ['name' => 'migrate_users']) !!}
                        {!! Form::submit('Migrate artists', ['name' => 'migrate_artists']) !!}
                        {!! Form::submit('Migrate brands', ['name' => 'migrate_brands']) !!}
                        {!! Form::submit('Migrate manufacturers', ['name' => 'migrate_manufacturers']) !!}
                        {!! Form::submit('Migrate companies', ['name' => 'migrate_companies']) !!}
                        {!! Form::submit('Migrate decks', ['name' => 'migrate_decks']) !!}
                        {!! Form::submit('Migrate collections', ['name' => 'migrate_collections']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
