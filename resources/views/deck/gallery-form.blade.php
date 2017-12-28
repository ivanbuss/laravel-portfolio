{!! Form::open(['action'=>['DeckGalleryController@postCreate'], 'id'=>'gallery-form', 'role'=>'form', 'files' => true]) !!}
    {!! Form::file('gallery_image') !!}
    {!! Form::select('gallery_tag', ['Box'=>'Box', 'Card'=>'Card', 'Photo'=>'Photo'], old('gallery_tag'), ['class'=>'form-control', 'placeholder' => '-Select-']) !!}
    {!! Form::select('gallery_tag_box', ['Front'=>'Front', 'Back'=>'Back', 'Interior'=>'Interior', 'Side'=>'Side'], old('gallery_tag_box'), ['class'=>'form-control', 'style'=>'display: none', 'placeholder' => '-Select-']) !!}
    {!! Form::select('gallery_tag_box_side', ['Left'=>'Left', 'Right'=>'Right'], old('gallery_tag_box_side'), ['class'=>'form-control', 'style'=>'display: none', 'placeholder' => '-Select-']) !!}
    {!! Form::select('gallery_tag_card', ['Back Design'=>'Back Design', 'Ace'=>'Ace', 'Court'=>'Court', 'Pip'=>'Pip', 'Joker'=>'Joker', 'Misc'=>'Misc'], old('gallery_tag_box_side'), ['class'=>'form-control', 'style'=>'display: none', 'placeholder' => '-Select-']) !!}
    {!! Form::select('gallery_tag_card_court', ['King'=>'King', 'Queen'=>'Queen', 'Jack'=>'Jack'], old('gallery_tag_box_side'), ['class'=>'form-control', 'style'=>'display: none', 'placeholder' => '-Select-']) !!}
    {!! Form::select('gallery_tag_card_pip', ['2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9'], old('gallery_tag_box_side'), ['class'=>'form-control', 'style'=>'display: none', 'placeholder' => '-Select-']) !!}
    {!! Form::select('gallery_tag_card_joker', ['A'=>'A', 'B'=>'B'], old('gallery_tag_box_side'), ['class'=>'form-control', 'style'=>'display: none', 'placeholder' => '-Select-']) !!}
    {!! Form::select('gallery_tag_card_type', ['Spade'=>'Spade', 'Heart'=>'Heart', 'Diamond'=>'Diamond', 'Club'=>'Club', 'Other'=>'Other'], old('gallery_tag_box_side'), ['class'=>'form-control', 'style'=>'display: none', 'placeholder' => '-Select-']) !!}

    <button type="submit" class="btn btn-primary">
        <i class="fa fa-btn fa-user"></i>Upload
    </button>
{!! Form::close() !!}