@extends('layouts.app')

@section('content')
    @include('header-white')
    <div class="container-fluid no-padding" id="addNewDeck">
        {!! Form::open(['action'=>['DeckEditController@postUpdate', $deck->id], 'id'=>'add-deck-form', 'class'=>'form-horizontal', 'role'=>'form', 'files' => true]) !!}
        <div class="header-add-deck">
            <div class="container">
                <div class="col-md-3{{ $errors->has('front_img') ? ' has-error' : '' }}">
                    <div id="front-deck-img-button" class="box-images" style="padding: 0px;">
                        <span style="display: none;" class="box-images-description">FRONT BOX IMG</span>
                        <img src="{{ $deck->getFrontImg('700x500') . '?' . time() }}" class="front-deck-img-prev img-responsive" style="display: inline;">
                    </div>
                    <p class="help-block">5mb size limit</p>
                    @if ($errors->has('front_img'))
                        <span class="help-block">
                            <strong>{{ $errors->first('front_img') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-3{{ $errors->has('back_img') ? ' has-error' : '' }}">
                    <div id="back-deck-img-button" class="box-images" style="padding: 0px;">
                        <span style="display: none;" class="box-images-description">BACK BOX IMG</span>
                        <img src="{{ $deck->getBackImg('700x500') . '?' . time() }}" class="back-deck-img-prev img-responsive" style="display: inline;">
                    </div>
                    <p class="help-block">5mb size limit</p>
                    @if ($errors->has('back_img'))
                        <span class="help-block">
                            <strong>{{ $errors->first('back_img') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-6{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::label('name', 'DECK NAME', ['class'=>'control-label']) !!}
                    {!! Form::text('name', (old('name') ? old('name') : $deck->name), ['class'=>'form-control']) !!}
                    <p class="help-block">Do not add edition type 'deck' or 'cards' to name</p>
                    @if ($errors->has('name'))
                        <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                    <p>Upload high quality images of front and backs of
                        the deck box. Avoid cellophane if possible to avoid
                        misrepresenting the deck design.</p>
                </div>

                <div class="form-group hide">
                    {!! Form::label('front_img', 'FRONT BOX IMG', ['class'=>'col-md-4 control-label']) !!}

                    <div class="col-md-6">
                        {!! Form::file('front_img') !!}
                    </div>
                </div>

                <div class="form-group hide">
                    {!! Form::label('back_img', 'BACK BOX IMG', ['class'=>'col-md-4 control-label']) !!}

                    <div class="col-md-6">
                        {!! Form::file('back_img') !!}
                    </div>
                </div>
            </div>
        </div>
        <div id="title-add-deck">
            <div class="tab-item container">UPLOAD / TAG</div>
        </div>
        <div class="main">
            <div class="container">
                <div class="col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                    {!! Form::label('description', 'ABOUT THIS DECK', ['class'=>'control-label']) !!}
                    {!! Form::textarea('description', (old('description') ? old('description') : $deck->description), ['class'=>'form-control', 'placeholder'=>'Insert description here']) !!}
                    @if ($errors->has('description'))
                        <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('company') ? ' has-error' : '' }}">
                    {!! Form::label('company', 'COMPANY', ['class'=>'control-label']) !!}
                    @if ($deck->company)
                        {!! Form::text('company', (old('company') ? old('company') : $deck->company->name), ['class'=>'form-control autocomplete-models']) !!}
                    @else
                        {!! Form::text('company', (old('company') ? old('company') : null), ['class'=>'form-control autocomplete-models']) !!}
                    @endif
                    <p class="help-block">Company that releases the deck</p>
                    @if ($errors->has('company'))
                        <span class="help-block">
                        <strong>{{ $errors->first('company') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('edition') ? ' has-error' : '' }}">
                    {!! Form::label('edition', 'EDITION', ['class'=>'control-label']) !!}
                    {!! Form::text('edition', (old('edition') ? old('edition') : $deck->edition), ['class'=>'form-control autocomplete-term']) !!}
                    <p class="help-block">Name edition or variation type</p>
                    @if ($errors->has('edition'))
                        <span class="help-block">
                        <strong>{{ $errors->first('edition') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('collection') ? ' has-error' : '' }}">
                    {!! Form::label('collection', 'CARD SIZE', ['class'=>'control-label']) !!}
                    @if ($deck->brand)
                        {!! Form::text('collection', (old('collection') ? old('collection') : $deck->brand->name), ['class'=>'form-control autocomplete-models']) !!}
                    @else
                        {!! Form::text('collection', (old('collection') ? old('collection') : null), ['class'=>'form-control autocomplete-models']) !!}
                    @endif

                    <p class="help-block">Poker, Bridge, Tarot, Mini, Other</p>
                    @if ($errors->has('collection'))
                        <span class="help-block">
                        <strong>{{ $errors->first('collection') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('release_year') ? ' has-error' : '' }}">
                    {!! Form::label('release_year', 'RELEASE YEAR', ['class'=>'control-label']) !!}
                    {{ Form::selectYear('release_year', (Carbon::now()->format('Y') - 150), (Carbon::now()->format('Y') + 10), (old('release_year') ? old('release_year') : $deck->release_year), ['class'=>'form-control']) }}
                    <p class="help-block">Match printed date on deck</p>
                    @if ($errors->has('release_year'))
                        <span class="help-block">
                        <strong>{{ $errors->first('release_year') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('prod_run') ? ' has-error' : '' }}">
                    {!! Form::label('prod_run', 'PRODUCTION RUN', ['class'=>'control-label']) !!}
                    {!! Form::text('prod_run', (old('prod_run') ? old('prod_run') : $deck->prod_run), ['class'=>'form-control autocomplete-term']) !!}
                    <p class="help-block">Number of Decks Produced. Do not use commas</p>
                    @if ($errors->has('prod_run'))
                        <span class="help-block">
                        <strong>{{ $errors->first('prod_run') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="container">
                <div class="col-md-6{{ $errors->has('printer') ? ' has-error' : '' }}">
                    {!! Form::label('printer', 'PRINTER', ['class'=>'control-label']) !!}
                    @if ($deck->manufacturer)
                        {!! Form::text('printer', (old('printer') ? old('printer') : $deck->manufacturer->name), ['class'=>'form-control autocomplete-models']) !!}
                    @else
                        {!! Form::text('printer', (old('printer') ? old('printer') : null), ['class'=>'form-control autocomplete-models']) !!}
                    @endif
                    <p class="help-block">Name of card printing company</p>
                    @if ($errors->has('printer'))
                        <span class="help-block">
                        <strong>{{ $errors->first('printer') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('artist') ? ' has-error' : '' }}">
                    {!! Form::label('artist', 'ARTIST', ['class'=>'control-label']) !!}
                    @if ($deck->artist)
                        {!! Form::text('artist', (old('artist') ? old('artist') : $deck->artist->name), ['class'=>'form-control autocomplete-models']) !!}
                    @else
                        {!! Form::text('artist', (old('artist') ? old('artist') : null), ['class'=>'form-control autocomplete-models']) !!}
                    @endif
                    <p class="help-block">Name of illustrator of the deck</p>
                    @if ($errors->has('artist'))
                        <span class="help-block">
                        <strong>{{ $errors->first('artist') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('card_stock') ? ' has-error' : '' }}">
                    {!! Form::label('card_stock', 'CARD STOCK', ['class'=>'control-label']) !!}
                    {!! Form::text('card_stock', (old('card_stock') ? old('card_stock') : $deck->stocklist), ['class'=>'form-control multiple autocomplete-term-multiple']) !!}
                    <p class="help-block">Name of stock used in production</p>
                    @if ($errors->has('card_stock'))
                        <span class="help-block">
                        <strong>{{ $errors->first('card_stock') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('finish') ? ' has-error' : '' }}">
                    {!! Form::label('finish', 'FINISH', ['class'=>'control-label']) !!}
                    {!! Form::text('finish', (old('finish') ? old('finish') : $deck->finish), ['class'=>'form-control autocomplete-term']) !!}
                    <p class="help-block">Name of finish (if applicable)</p>
                    @if ($errors->has('finish'))
                        <span class="help-block">
                        <strong>{{ $errors->first('finish') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('court') ? ' has-error' : '' }}">
                    {!! Form::label('court', 'COURT CARD CUSTOMIZATION', ['class'=>'control-label']) !!}
                    {!! Form::select('court', ['Standard'=>'Standard', 'Modified'=>'Modified', 'Custom'=>'Custom'], (old('court') ? old('court') : $deck->customization), ['class'=>'form-control', 'placeholder' => '-Select-']) !!}
                    <p class="help-block">Select: Standart, Modified, Custom</p>
                    @if ($errors->has('court'))
                        <span class="help-block">
                        <strong>{{ $errors->first('court') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('features') ? ' has-error' : '' }}">
                    {!! Form::label('features', 'BOX FEATURES', ['class'=>'control-label']) !!}
                    {!! Form::text('features', (old('features') ? old('features') : $deck->featurelist), ['class'=>'form-control multiple autocomplete-term-multiple']) !!}
                    <p class="help-block">Select all tags that apply (Color Foil, emboss, deboss, holoram, diecut)</p>
                    @if ($errors->has('features'))
                        <span class="help-block">
                        <strong>{{ $errors->first('features') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('colors') ? ' has-error' : '' }}">
                    {!! Form::label('colors', 'COLORS', ['class'=>'control-label']) !!}
                    {!! Form::text('colors', (old('colors') ? old('colors') : $deck->colorlist), ['class'=>'form-control multiple autocomplete-term-multiple']) !!}
                    <p class="help-block">Tag up to three main colors (White, block, bronze)</p>
                    @if ($errors->has('colors'))
                        <span class="help-block">
                        <strong>{{ $errors->first('colors') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('style') ? ' has-error' : '' }}">
                    {!! Form::label('style', 'STYLE', ['class'=>'control-label']) !!}
                    {!! Form::text('style', (old('style') ? old('style') : $deck->stylelist), ['class'=>'form-control multiple autocomplete-term-multiple']) !!}
                    <p class="help-block">Tag: Border, Bleed, Gilded, Marked</p>
                    @if ($errors->has('style'))
                        <span class="help-block">
                        <strong>{{ $errors->first('style') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('themes') ? ' has-error' : '' }}">
                    {!! Form::label('themes', 'THEMES', ['class'=>'control-label']) !!}
                    {!! Form::text('themes', (old('themes') ? old('themes') : $deck->themelist), ['class'=>'form-control multiple autocomplete-term-multiple']) !!}
                    <p class="help-block">Tag themes (nautical, war, animal, luxury, etc) Separate tags with a comma.</p>
                    @if ($errors->has('themes'))
                        <span class="help-block">
                            <strong>{{ $errors->first('themes') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="col-md-6{{ $errors->has('tags') ? ' has-error' : '' }}">
                    {!! Form::label('tags', 'ADDITIONAL TAGS', ['class'=>'control-label']) !!}
                    {!! Form::text('tags', (old('tags') ? old('tags') : $deck->taglist), ['class'=>'form-control multiple autocomplete-term-multiple']) !!}
                    <p class="help-block">Add any other appropriate keywords not applicable in other categories.</p>
                    @if ($errors->has('tags'))
                        <span class="help-block">
                        <strong>{{ $errors->first('tags') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="gallery col-md-12">
                    {!! Form::label('gallery', 'Gallery') !!}
                    <div class="row">
                        @if ($gallery_items)
                            @foreach($gallery_items as $gallery_item)
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <img src="{{ $gallery_item->getImage('700x500') }}" class="img-responsive card-img">
                                </div>
                            @endforeach
                        @endif
                        <div class="col-md-3">
                            <div id="gallery-img-button" class="box-images add-gallery" data-toggle="modal" data-target="#gallery-modal"></div>
                        </div>
                    </div>
                    @if ($gallery_items)
                        @foreach($gallery_items as $gallery_item)
                            <input type="hidden" name="gallery[]" value="{{ $gallery_item->id }}">
                        @endforeach
                    @else
                        <input id="gallery-item" type="hidden" name="gallery[0]" value="">
                    @endif
                </div>

                <div class="col-md-6">
                    <button id="gallery-create-item-button" type="submit" class="btn btn-primary">
                        <i class="fa fa-btn fa-user"></i>Save
                    </button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="modal fade" id="gallery-modal" tabindex="-1" role="dialog" aria-labelledby="galleryModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="galleryModalLabel">Create new Gallery</h4>
                </div>
                <div class="modal-body">
                    @include('deck.gallery-form')
                </div>
            </div>
        </div>
    </div>
@endsection
