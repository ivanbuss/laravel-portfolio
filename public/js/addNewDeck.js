$(function() {

  function split( val ) {
    return val.split( /,\s*/ );
  }

  function extractLast( term ) {
    return split( term ).pop();
  }

  $('#launch_date').datetimepicker();

  $( ".autocomplete-term" ).autocomplete({
    source: function( request, response ) {
      var id = this.element.attr("id");
      $.ajax({
        url: "/ajax/term/" + id + "/single",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2
  });

  $( ".autocomplete-models" ).autocomplete({
    source: function( request, response ) {
      var key = this.element.attr("name");
      $.ajax({
        url: "/ajax/autocomplete/"+key,
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2
  });

  $( "#card_stock" ).autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: "/ajax/term/card_stock/multiple",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2,
    select: function( event, ui ) {
      var terms = split( this.value );
      terms.pop();
      terms.push( ui.item.value );
      terms.push( "" );
      this.value = terms.join( ", " );
      return false;
    }
  });

  $( "#features" ).autocomplete({
    source: function( request, response ) {
      //var id = this.element.attr("id");
      //console.log(id);
      $.ajax({
        url: "/ajax/term/features/multiple",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2,
    select: function( event, ui ) {
      var terms = split( this.value );
      terms.pop();
      terms.push( ui.item.value );
      terms.push( "" );
      this.value = terms.join( ", " );
      return false;
    }
  });

  $( "#colors" ).autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: "/ajax/term/colors/multiple",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2,
    select: function( event, ui ) {
      var terms = split( this.value );
      terms.pop();
      terms.push( ui.item.value );
      terms.push( "" );
      this.value = terms.join( ", " );
      return false;
    }
  });

  $( "#style" ).autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: "/ajax/term/styles/multiple",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2,
    select: function( event, ui ) {
      var terms = split( this.value );
      terms.pop();
      terms.push( ui.item.value );
      terms.push( "" );
      this.value = terms.join( ", " );
      return false;
    }
  });

  $( "#themes" ).autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: "/ajax/term/themes/multiple",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2,
    select: function( event, ui ) {
      var terms = split( this.value );
      terms.pop();
      terms.push( ui.item.value );
      terms.push( "" );
      this.value = terms.join( ", " );
      return false;
    }
  });

  $( "#tags" ).autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: "/ajax/term/tags/multiple",
        dataType: "json",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
      })
    },
    minLength: 2,
    select: function( event, ui ) {
      var terms = split( this.value );
      terms.pop();
      terms.push( ui.item.value );
      terms.push( "" );
      this.value = terms.join( ", " );
      return false;
    }
  });

  /*front-deck*/
  var imgSrc;

  $('#front-deck-img-button').click(function(){
    $('#front_img').click();
  });

  $('#back-deck-img-button').click(function(){
    $('#back_img').click();
  });

  $('#front_img, #back_img').bind('change', function() {
    $field = $(this).attr('name');
    var formData = new FormData();
    formData.append('_token', $('input[name="_token"]').val())
    formData.append($field, this.files[0]);
    $.ajax({
      url: '/deck/files/upload/' + $field,
      data: formData,
      dataType: 'json',
      async: false,
      type: 'post',
      processData: false,
      contentType: false,
      success: function(response){
        var imgSrc = response.file;
        if(response.field == "front_img"){
          console.log("sfdfawe");
          $("#front-deck-img-button")
            .css("padding", "0")
            .find('.box-images-description').hide().parent()
            .find(".front-deck-img-prev")
            .show()
            .attr("src", imgSrc);
        } else {
          $("#back-deck-img-button")
            .css("padding", "0")
            .find('.box-images-description').hide().parent()
            .find(".back-deck-img-prev")
            .show()
            .attr("src", imgSrc);
        }
      }
    });
  });

  $('#gallery-modal').on('show.bs.modal', function (event) {
    var modal = $(this);
    $.ajax({
      url: '/deck/gallery/add',
      dataType: 'json',
      type: 'get',
      success: function(response){
        if (response.success) {
          modal.find('.modal-body').html(response.form);
          bind_gallery_form();
        }
      }
    })

  });

  bind_reset_tags();


  $('form.collapse-edit-tags').bind('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: 'json',
      type: 'post',
      success: function(response){
        if (response.success) {
          $('.field-value.field-'+response.tag_name).html(response.value);
          $('form.collapse-edit-tags input#'+response.tag_name).val('');
          bind_reset_tags();
        }
      }
    });
  });

  function bind_reset_tags() {
    $('a.cancel-tags').each(function() {
      if (!$(this).hasClass('element-processed')) {
        $(this).bind('click', function(e) {
          e.preventDefault();
          $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            data: {attribute:$(this).attr('rel'), _token:$('meta[name="csrf-token"]').attr('content')},
            type: 'post',
            success: function(response){
              $('.field-value.field-'+response.tag_name).html(response.value);
              $('a.cancel-tags[rel="'+response.tag_name+'"]').remove();
            }
          });
        });
        $(this).addClass('element-processed');
      }
    });
  }

  function bind_gallery_form() {
    function hideAll() {
      $('select[name="gallery_tag_box"]').hide();
      $('select[name="gallery_tag_box_side"]').hide();
      $('select[name="gallery_tag_card"]').hide();
      $('select[name="gallery_tag_card_court"]').hide();
      $('select[name="gallery_tag_card_pip"]').hide();
      $('select[name="gallery_tag_card_joker"]').hide();
      $('select[name="gallery_tag_card_type"]').hide();
    }
    if (!$('form#gallery-form').hasClass('element-processed')) {
      $('select[name="gallery_tag"]').on('change', function() {
        switch ($(this).val()) {
          case 'Box':
            hideAll();
            $('select[name="gallery_tag_card"]').val('');
            $('select[name="gallery_tag_card_type"]').val('');
            $('select[name="gallery_tag_card_court"]').val('');
            $('select[name="gallery_tag_card_pip"]').val('');
            $('select[name="gallery_tag_box"]').show();
            break;
          case 'Card':
            hideAll();
            $('select[name="gallery_tag_box"]').val('');
            $('select[name="gallery_tag_card"]').show();
            $('select[name="gallery_tag_box_side"]').val('');
            break;
          case 'Photo':
            hideAll();
            $('select[name="gallery_tag_box"]').val('');
            $('select[name="gallery_tag_card"]').val('');
            $('select[name="gallery_tag_box_side"]').val('');
            $('select[name="gallery_tag_card_type"]').val('');
            $('select[name="gallery_tag_card_court"]').val('');
            $('select[name="gallery_tag_card_pip"]').val('');
            break;
          default:
            hideAll();
            $('select[name="gallery_tag_box"]').val('');
            $('select[name="gallery_tag_card"]').val('');
            $('select[name="gallery_tag_box_side"]').val('');
            $('select[name="gallery_tag_card_type"]').val('');
            $('select[name="gallery_tag_card_court"]').val('');
            $('select[name="gallery_tag_card_pip"]').val('');
            break;
        }
      });

      $('select[name="gallery_tag_box"]').on('change', function() {
        switch ($(this).val()) {
          case 'Side':
            $('select[name="gallery_tag_box_side"]').show();
            break;
          default:
            $('select[name="gallery_tag_box_side"]').hide();
            $('select[name="gallery_tag_box_side"]').val('');
            break;
        }
      });

      $('select[name="gallery_tag_card"]').on('change', function() {
        switch ($(this).val()) {
          case 'Ace':
            hideAll();
            $('select[name="gallery_tag_card_pip"]').val('');
            $('select[name="gallery_tag_card_joker"]').val('');
            $('select[name="gallery_tag_card"]').show();
            $('select[name="gallery_tag_card_type"]').show();
            break;
          case 'Court':
            hideAll();
            $('select[name="gallery_tag_card_type"]').val('');
            $('select[name="gallery_tag_card_pip"]').val('');
            $('select[name="gallery_tag_card_joker"]').val('');
            $('select[name="gallery_tag_card"]').show();
            $('select[name="gallery_tag_card_court"]').show();
            break;
          case 'Pip':
            hideAll();
            $('select[name="gallery_tag_card_type"]').val('');
            $('select[name="gallery_tag_card_joker"]').val('');
            $('select[name="gallery_tag_card"]').show();
            $('select[name="gallery_tag_card_pip"]').show();
            break;
          case 'Joker':
            hideAll();
            $('select[name="gallery_tag_card_type"]').val('');
            $('select[name="gallery_tag_card_pip"]').val('');
            $('select[name="gallery_tag_card"]').show();
            $('select[name="gallery_tag_card_joker"]').show();
            break;
          default:
            hideAll();
            $('select[name="gallery_tag_card_type"]').val('');
            $('select[name="gallery_tag_card_pip"]').val('');
            $('select[name="gallery_tag_card_joker"]').val('');
            $('select[name="gallery_tag_card"]').show();
            break;
        }
      });

      $('select[name="gallery_tag_card_court"], select[name="gallery_tag_card_pip"]').on('change', function() {
        if ($(this).val()) {
          $('select[name="gallery_tag_card_type"]').show();
        } else {
          $('select[name="gallery_tag_card_type"]').hide();
          $('select[name="gallery_tag_card_type"]').val('');
        }
      });

      $('form#gallery-form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData();
        image = $('form#gallery-form input[name="gallery_image"]')[0];
        formData.append('_token', $('form#gallery-form input[name="_token"]').val());
        formData.append('gallery_image', image.files[0]);
        formData.append('gallery_tag', $('form#gallery-form select[name="gallery_tag"]').val());
        formData.append('gallery_tag_box', $('form#gallery-form select[name="gallery_tag_box"]').val());
        formData.append('gallery_tag_box_side', $('form#gallery-form select[name="gallery_tag_box_side"]').val());
        formData.append('gallery_tag_card', $('form#gallery-form select[name="gallery_tag_card"]').val());
        formData.append('gallery_tag_card_type', $('form#gallery-form select[name="gallery_tag_card_type"]').val());
        formData.append('gallery_tag_card_court', $('form#gallery-form select[name="gallery_tag_card_court"]').val());
        formData.append('gallery_tag_card_pip', $('form#gallery-form select[name="gallery_tag_card_pip"]').val());
        formData.append('gallery_tag_card_joker', $('form#gallery-form select[name="gallery_tag_card_joker"]').val());
        $.ajax({
          url: $('form#gallery-form').attr('action'),
          data: formData,
          dataType: 'json',
          async: false,
          type: 'post',
          processData: false,
          contentType: false,
          success: function(response){
            if (response.success) {
              if ($('form#add-deck-form input[name="gallery[]"]').length && $('form#add-deck-form input[name="gallery[]"]').val() == '') {
                $('form#add-deck-form input[name="gallery[]"]').val(response.item_id);
              } else {
                $('form#add-deck-form .gallery').append('<input value="'+response.item_id+'" name="gallery[]" type="hidden">')
              }
              $('#gallery-modal').modal('hide');
              var element = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                  '<img src="'+response.gallery_image+'" class="img-responsive card-img">' +
                  '</div>';
              $('#gallery-img-button').closest('.row').prepend(element);
            }
          }
        });
      });
      $('form#gallery-form').addClass('element-processed');
    }
  }

});


/*expanded + and - for deck page*/

$(".deck-fields .expand").on("click",function(){
  if($(this).hasClass("expanded")){
    $(this).removeClass("expanded").html("(+)");
  } else {
    $(this).addClass("expanded").html("(-)");
  }
});