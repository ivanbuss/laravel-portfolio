$(document).ready(function () {

  /*search field on main page*/
  $( "#home-page-search-input" ).autocomplete({
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
  /**/
  /*search filter variables */
  var searchString = {};
  var typeOfSorting = '';
  var typeOfView = '';
  var typeOfSearch = '';
  var searchStringFromHome = location.href;

  if ($("#card-sorting").find("option:selected").length) {
    typeOfSorting = $("#card-sorting").find("option:selected").attr("value");
    //console.log(typeOfSorting);
  }
  if ($(".discover-bar i.active").length) {
    typeOfView = $(".discover-bar i.active").attr("id").split('-')[0];
    //console.log(typeOfView);
  }

  if ($('.collection-list.cards').length) {
    typeOfSearch = $('.collection-list.cards').attr('rel');
  }

  var quantity = {};
  var quantity_from;
  var quantity_to;
  var colors = {};
  var theme = {};
  var stocks = {};
  var styles = {};

  var quantityNames = [];
  var colorsNames = [];
  var themeNames = [];
  var stocksNames = [];
  var stylesNames = [];

  var decksSkip = null;

  /*tile and tile view*/
  $('#list-view-icon').on("click", function(){
    $(this).addClass("active");
    $('#tile-view-icon').removeClass("active");
  });
  $('#tile-view-icon').on("click", function(){
    $(this).addClass("active");
    $('#list-view-icon').removeClass("active");
  });
  /*enf of tile and tile view*/

  /*search main tabs discover and filter*/

  if(searchStringFromHome.indexOf('?search_string') + 1) {
    $("#SEARCH-STRING").css("display", "block");
    $(".search-page-filter-tabs").css("display", "none");

  }
    /**/
  $(".search-main-tab-item").on("click", function(){
    if($(this).hasClass("active")){
      //$(this).removeClass("active");
    } else {
      $(".search-main-tab-item").removeClass("active");
      $(this).addClass("active");
    }
    /**/
    if($(this).hasClass("search-main-tab-filter")){
      $(".search-page-filter-tabs").css("display", "block");
      $("#SEARCH-STRING").css("display", "none");
    } else {
    /*reset all filters if discover tab is active and search state items*/
      $(".search-page-filter-tabs").css("display", "none");
      $("#SEARCH-STRING").css("display", "block");
    }
    /**/
  });

  collection_sortable();
  notes();
  /**/

  /*select card sorting*/
  function getSearchResult(searchString, typeOfSorting, typeOfView, colors, quantity, theme, stocks, styles, decksSkip, quantity_from, quantity_to) {
    $(".search-state-icon").html("<img src='../img/preloader.gif'>");
    //console.log(quantity_from);
    //console.log(quantity_to);

    var tokenValue = $(".discover-bar form").find("input[name= '_token']").attr("value");
    var request = $.ajax({
      type:'POST',
      url: "search/post/ajax",
      data: {
        "search_string" : searchString,
        "decks_sort": typeOfSorting,
        "decks_skip" : decksSkip,
        "decks_take" : "20",
        "_token" : tokenValue,
        "view" : typeOfView,
        "colors" : colors,
        //"quantity" : quantity,
        "tuck" : theme,
        "stocks" : stocks,
        "styles" : styles,
        "quantity_from" : quantity_from,
        "quantity_to" : quantity_to
      },
      dataType: "html"
    });
    request.done(function(data) {
      if (decksSkip != null){
        $('.collection-list .cboxElement:not(.colorbox-processed)').each(function(){
          $(this).colorbox.remove();
        });
        $(".search-state-icon").empty();
        $(".collection-list .thumbnails").append(data);
        $('.cboxElement:not(.colorbox-processed)').addClass("colorbox-processed").each(function(){
          $(this).colorbox({inline:true, href: $(this).attr("href"), opacity: 0.9 , rel:'group1', previous :"<", next : ">", close : "X"});
        });
      } else {
        $('.collection-list .cboxElement:not(.colorbox-processed)').each(function(){
          $(this).colorbox.remove();
        });
        $(".search-state-icon").empty();
        $(".collection-list .thumbnails").empty().append(data);
        $('.cboxElement:not(.colorbox-processed)').addClass("colorbox-processed").each(function(){
          $(this).colorbox({inline:true, href: $(this).attr("href"), opacity: 0.9 , rel:'group1', previous :"<", next : ">", close : "X"});
        });
      }

      $('.collection-add:not(.collection-link-processed)').on('click', function(event) {
        event.preventDefault();
        collectionRequest('collection', this);
      }).addClass('collection-link-processed');

      $('.wishlist-add:not(.collection-link-processed)').on('click', function(event) {
        event.preventDefault();
        collectionRequest('wishlist', this);
      }).addClass('collection-link-processed');

      $('.tradelist-add:not(.collection-link-processed)').on('click', function(event) {
        event.preventDefault();
        collectionRequest('tradelist', this);
      }).addClass('collection-link-processed');

      collectionLinks();
      $("#animated-icons .collection-actions .adding:not(.collection-link-processed)").on("click", function(){
        collectionRequest('collection', this);
      }).addClass('collection-link-processed');
      $("#animated-icons .collection-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function(){
        collectionRequest('collection', this);
      }).addClass('collection-link-processed');

      $("#animated-icons .wishlist-actions .adding:not(.collection-link-processed)").on("click", function(){
        collectionRequest('wishlist', this);
      }).addClass('collection-link-processed');
      $("#animated-icons .wishlist-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function(){
        collectionRequest('wishlist', this);
      }).addClass('collection-link-processed');

      $("#animated-icons .tradelist-actions .adding:not(.collection-link-processed)").on("click", function(){
        collectionRequest('tradelist', this);
      }).addClass('collection-link-processed');
      $("#animated-icons .tradelist-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function(){
        collectionRequest('tradelist', this);
      }).addClass('collection-link-processed');

      /*front and back animated images for deck*/
      $(".cards .item-wrapper:not(.mouseenter-link-processed)").mouseenter(function(){
        $(this).find(".front-img").css("display", "none");
        $(this).find(".back-img").css("display", "block");
        $(this).next(".decks-description").css("display", "block");
      }).addClass('mouseenter-link-processed');
      $(".cards .item-wrapper:not(.mouseleave-link-processed)").mouseleave(function(){
        $(this).find(".front-img").css("display", "block");
        $(this).find(".back-img").css("display", "none");
        $(this).next(".decks-description").css("display", "none");
      }).addClass('mouseleave-link-processed');
      /**/
      /*front and back images for colorbox*/
      $(".collection-card-lightbox .colorbox-deck-image").mouseenter(function(){
        $(this).find(".front-img").css("display", "none");
        $(this).find(".back-img").css("display", "block");
      });
      $(".collection-card-lightbox .colorbox-deck-image").mouseleave(function(){
        $(this).find(".front-img").css("display", "block");
        $(this).find(".back-img").css("display", "none");
      });
      /**/
      notes();
      collection_sortable();
      ratingStars ();
    });
    request.fail(function() {
      //console.log ("error");
    });
  }

  function getCollectionResult(typeOfSearch, typeOfSorting, typeOfView, decksSkip) {
    $(".search-state-icon").html("<img src='../img/preloader.gif'>");

    var tokenValue = $(".discover-bar form").find("input[name= '_token']").attr("value");
    var request = $.ajax({
      type:'POST',
      url: $(".discover-bar form").attr('action'),
      data: {
        "search_string" : typeOfSearch,
        "sort": typeOfSorting,
        "decks_skip" : decksSkip,
        "decks_take" : "20",
        "_token" : tokenValue,
        "view" : typeOfView
      },
      dataType: "html"
    });

    request.done(function(data) {
      if (decksSkip != null){
        $('.collection-list .cboxElement:not(.colorbox-processed)').each(function(){
          $(this).colorbox.remove();
        });
        $(".search-state-icon").empty();
        $(".collection-list .table-items").append(data);
        $('.cboxElement:not(.colorbox-processed)').addClass("colorbox-processed").each(function(){
          $(this).colorbox({inline:true, href: $(this).attr("href"), opacity: 0.9 , rel:'group1', previous :"<", next : ">", close : "X"});
        });

      } else {
        $('.collection-list .cboxElement:not(.colorbox-processed)').each(function(){
          $(this).colorbox.remove();
        });
        $(".search-state-icon").empty();
        $(".collection-list .table-items").empty().append(data);
        $('.cboxElement:not(.colorbox-processed)').addClass("colorbox-processed").each(function(){
          $(this).colorbox({inline:true, href: $(this).attr("href"), opacity: 0.9 , rel:'group1', previous :"<", next : ">", close : "X"});
        });
      }

      $('.collection-add:not(.collection-link-processed)').on('click', function(event) {
        event.preventDefault();
        collectionRequest('collection', this);
      }).addClass('collection-link-processed');

      $('.wishlist-add:not(.collection-link-processed)').on('click', function(event) {
        event.preventDefault();
        collectionRequest('wishlist', this);
      }).addClass('collection-link-processed');

      $('.tradelist-add:not(.collection-link-processed)').on('click', function(event) {
        event.preventDefault();
        collectionRequest('tradelist', this);
      }).addClass('collection-link-processed');

      collectionLinks();
      $("#animated-icons .collection-actions .adding:not(.collection-link-processed)").on("click", function(){
        collectionRequest('collection', this);
      }).addClass('collection-link-processed');
      $("#animated-icons .collection-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function(){
        collectionRequest('collection', this);
      }).addClass('collection-link-processed');

      $("#animated-icons .wishlist-actions .adding:not(.collection-link-processed)").on("click", function(){
        collectionRequest('wishlist', this);
      }).addClass('collection-link-processed');
      $("#animated-icons .wishlist-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function(){
        collectionRequest('wishlist', this);
      }).addClass('collection-link-processed');

      $("#animated-icons .tradelist-actions .adding:not(.collection-link-processed)").on("click", function(){
        collectionRequest('tradelist', this);
      }).addClass('collection-link-processed');
      $("#animated-icons .tradelist-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function(){
        collectionRequest('tradelist', this);
      }).addClass('collection-link-processed');

      /*front and back animated images for deck*/
      $(".cards .item-wrapper:not(.mouseenter-link-processed)").mouseenter(function(){
        $(this).find(".front-img").css("display", "none");
        $(this).find(".back-img").css("display", "block");
        $(this).next(".decks-description").css("display", "block");
      }).addClass('mouseenter-link-processed');
      $(".cards .item-wrapper:not(.mouseleave-link-processed)").mouseleave(function(){
        $(this).find(".front-img").css("display", "block");
        $(this).find(".back-img").css("display", "none");
        $(this).next(".decks-description").css("display", "none");
      }).addClass('mouseleave-link-processed');
      /**/
      /*front and back images for colorbox*/
      $(".collection-card-lightbox .colorbox-deck-image").mouseenter(function(){
        $(this).find(".front-img").css("display", "none");
        $(this).find(".back-img").css("display", "block");
      });
      $(".collection-card-lightbox .colorbox-deck-image").mouseleave(function(){
        $(this).find(".front-img").css("display", "block");
        $(this).find(".back-img").css("display", "none");
      });
      /**/

      collection_sortable();
      notes();
      ratingStars();
    });
    request.fail(function() {
      //console.log ("error");
    });
  }

  function collection_sortable() {
    if ($('#collection-sortable').hasClass('ui-sortable')) {
      $('#collection-sortable').sortable( "destroy" );
    }
    $( "#collection-sortable" ).sortable({
      stop: function(event, ui) {
        var order = []; var weights = [];
        $('.collection-list-item').each(function() {
          order.push($(this).attr('rel'));
          weights.push($(this).find('input.collection-weight').val());
        });

        $.ajax({
          type: 'POST',
          url: $('#collection-sortable').attr('data-url'),
          data: {
            _token:$('meta[name="csrf-token"]').attr('content'),
            order: order,
            weights: weights
          },
          success: function( data ) {

          }
        });
      }
    });
    $( "#collection-sortable" ).disableSelection();
  }

  /*search string*/
  $("#search-string-field").change( function(){
    decksSkip = null;
    searchString = $("#search-string-field").val();
    getSearchResult(searchString, typeOfSorting, typeOfView, colors, quantity, theme, stocks, styles, decksSkip, quantity_from, quantity_to);
  });

  /*select filter*/
  $("#card-sorting").on("change", function(){
    decksSkip = null;
    typeOfSorting = $("#card-sorting").find("option:selected").attr("value");
    getSearchResult(searchString, typeOfSorting, typeOfView, colors, quantity, theme, stocks, styles, decksSkip, quantity_from, quantity_to);
  });

  $('#collection-card-sorting').on("change", function(){
    decksSkip = null;
    typeOfSorting = $("#collection-card-sorting").find("option:selected").attr("value");
    getCollectionResult(typeOfSearch, typeOfSorting, typeOfView, decksSkip);
  });
  /**/

  /*icon type of view*/
  if ($('.collections-view-icons').length) {
    $('.collections-view-icons i').on("click", function(){
      decksSkip = null;
      typeOfView = $(this).attr("id").split('-')[0];
      getCollectionResult(typeOfSearch, typeOfSorting, typeOfView, decksSkip);
    });
  } else {
    $(".view-icon").on("click", function(){
      decksSkip = null;
      typeOfView = $(this).attr("id").split('-')[0];
      getSearchResult(searchString, typeOfSorting, typeOfView, colors, quantity, theme, stocks, styles, decksSkip, quantity_from, quantity_to);
    });
  }
  /**/

  /*full search with filters*/
  $(".search-page-filter-tabs .tab-content .search-filter-item").on("click", function(){
    decksSkip = null;

    //var typeOfView = $(".discover-bar i.active").attr("id");

    if($(this).hasClass("active")){

      $(this).removeClass("active");

      if ($(this).hasClass("quantity-item")) {
        $(".quantity-item").removeClass("active");
        $(this).addClass("active");
        quantity_from = $(this).attr("data-quantity-from");
        quantity_to = $(this).attr("data-quantity-to");
      }
      if($(this).hasClass("color-item")){
        colors = {};
        colorsNames = [];
        $(".tab-content .search-filter-item.color-item.active").each( function() {
          var colorItem = $(this).attr("data-id");
          var colorsNamesItem = $(this).html();
          //colors.push(colorItem);
          colorsNames.push(colorsNamesItem);
          colors[colorItem] = colorsNamesItem;
        });
      } else if($(this).hasClass("theme-item")){
        theme = {};
        themeNames = [];
        $(".tab-content .search-filter-item.theme-item.active").each( function() {
          var themeItem = $(this).attr("data-id");
          var themeNamesItem = $(this).html();
          //theme.push(themeItem);
          themeNames.push(themeNamesItem);
          theme[themeItem] = themeNamesItem;
        });
      } else if($(this).hasClass("stock-item")){
        stocks = {};
        stocksNames = [];
        $(".tab-content .search-filter-item.stock-item.active").each( function() {
          var stocksItem = $(this).attr("data-id");
          var stocksNamesItem = $(this).html();
          //stocks.push(stocksItem);
          stocksNames.push(stocksNamesItem);
          stocks[stocksItem] = stocksNamesItem;
        });
      } else if($(this).hasClass("style-item")){
        styles = {};
        stylesNames = [];
        $(".tab-content .search-filter-item.style-item.active").each( function() {
          var stylesItem = $(this).attr("data-id");
          var stylesNamesItem = $(this).html();
          //styles.push(stylesItem);
          stylesNames.push(stylesNamesItem);
          styles[stylesItem] = stylesNamesItem;
        });
      }
    } else {
      $(this).addClass("active");
      if ($(this).hasClass("quantity-item")) {
        $(".quantity-item").removeClass("active");
        $(this).addClass("active");
        quantity_from = $(this).attr("data-quantity-from");
        quantity_to = $(this).attr("data-quantity-to");
      } else if($(this).hasClass("color-item")){
        colors = {};
        colorsNames = [];
        $(".tab-content .search-filter-item.color-item.active").each( function() {
          //colors.push($(this).attr("data-id"));
          colorsNames.push($(this).html());
          colors[$(this).attr("data-id")] = $(this).html();
        });
      } else if($(this).hasClass("theme-item")){
        theme = {};
        themeNames = [];
        $(".tab-content .search-filter-item.theme-item.active").each( function() {
          //theme.push($(this).attr("data-id"));
          themeNames.push($(this).html());
          theme[$(this).attr("data-id")] = $(this).html();
        });
      } else if($(this).hasClass("stock-item")){
        stocks = {};
        stocksNames = [];
        $(".tab-content .search-filter-item.stock-item.active").each( function() {
          //stocks.push($(this).attr("data-id"));
          stocksNames.push($(this).html());
          stocks[$(this).attr("data-id")] = $(this).html();
        });
      } else if($(this).hasClass("style-item")){
        styles = {};
        stylesNames = [];
        $(".tab-content .search-filter-item.style-item.active").each( function() {
          //styles.push($(this).attr("data-id"));
          stylesNames.push($(this).html());
          styles[$(this).attr("data-id")] = $(this).html();
        });
      }
    }
    /*search state*/
    var quantity_from_to =  quantity_from  + " - " + quantity_to;

    if(quantity_from == undefined ||quantity_to == undefined ){
      quantity_from_to  = "";
    }



      $(".search-state-items").empty().html(colorsNames + " " + quantity_from_to +  " " +  themeNames + " " +  stocksNames + " " +  stylesNames);
    /**/
    //console.log(colors);
    //console.log(quantity);
    //console.log(theme);
    //console.log(stocks);
    //console.log(styles);

    getSearchResult(searchString, typeOfSorting, typeOfView, colors, quantity, theme, stocks, styles, decksSkip, quantity_from, quantity_to);
  });

  collectionLinks();
  $('.collection-add:not(.collection-link-processed)').on('click', function(event) {
    event.preventDefault();
    if (!$('#signUpModal').length) collectionRequest('collection', this);
  }).addClass('collection-link-processed');

  $('.wishlist-add:not(.collection-link-processed)').on('click', function(event) {
    event.preventDefault();
    if (!$('#signUpModal').length) collectionRequest('wishlist', this);
  }).addClass('collection-link-processed');

  $('.tradelist-add:not(.collection-link-processed)').on('click', function(event) {
    event.preventDefault();
    if (!$('#signUpModal').length) collectionRequest('tradelist', this);
  }).addClass('collection-link-processed');

  /*adding/removing deck from collection by animated icons*/
  if (!$('#signUpModal').length) {
    $("#animated-icons .collection-actions .adding:not(.collection-link-processed)").on("click", function () {
      collectionRequest('collection', this);
    }).addClass('collection-link-processed');
    $("#animated-icons .collection-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function () {
      collectionRequest('collection', this);
    }).addClass('collection-link-processed');

    $("#animated-icons .wishlist-actions .adding:not(.collection-link-processed)").on("click", function () {
      collectionRequest('wishlist', this);
    }).addClass('collection-link-processed');
    $("#animated-icons .wishlist-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function () {
      collectionRequest('wishlist', this);
    }).addClass('collection-link-processed');

    $("#animated-icons .tradelist-actions .adding:not(.collection-link-processed)").on("click", function () {
      collectionRequest('tradelist', this);
    }).addClass('collection-link-processed');
    $("#animated-icons .tradelist-actions .deleting .fa-minus:not(.collection-link-processed)").on("click", function () {
      collectionRequest('tradelist', this);
    }).addClass('collection-link-processed');
  }
  function collectionRequest($type, element) {
    formData = $(element).parents('form#'+$type+'-add-form:first').serialize();
    $.ajax({
      url: $(element).parents('form:first').attr('action'),
      dataType: 'json',
      data: formData,
      type: 'post',
      success: function( data ) {
        if (data.success) {
          if ($(element).hasClass('deck-view-collections')) {
            if (data.collection_count == 0) {
              if ((data.type == 'collection') && !$('.collection-actions.collection-deck-'+data.deck_id).hasClass('disabled')) $('.collection-actions.collection-deck-'+data.deck_id).addClass('disabled');
              if ((data.type == 'wishlist') && !$('.wishlist-actions.collection-deck-'+data.deck_id).hasClass('disabled')) $('.wishlist-actions.collection-deck-'+data.deck_id).addClass('disabled');
              if ((data.type == 'tradelist') && !$('.tradelist-actions.collection-deck-'+data.deck_id).hasClass('disabled')) $('.tradelist-actions.collection-deck-'+data.deck_id).addClass('disabled');
            } else {
              if ((data.type == 'collection') && $('.collection-actions.collection-deck-'+data.deck_id).hasClass('disabled')) $('.collection-actions.collection-deck-'+data.deck_id).removeClass('disabled');
              if ((data.type == 'wishlist') && $('.wishlist-actions.collection-deck-'+data.deck_id).hasClass('disabled')) $('.wishlist-actions.collection-deck-'+data.deck_id).removeClass('disabled');
              if ((data.type == 'tradelist') && $('.tradelist-actions.collection-deck-'+data.deck_id).hasClass('disabled')) $('.tradelist-actions.collection-deck-'+data.deck_id).removeClass('disabled');
            }
            if (data.type == 'collection') $('.collection-actions.collection-deck-'+data.deck_id+' .adding span.number').html(data.collection_count);
            if (data.type == 'wishlist') $('.wishlist-actions.collection-deck-'+data.deck_id+' .adding span.number').html(data.collection_count);
            if (data.type == 'tradelist') $('.tradelist-actions.collection-deck-'+data.deck_id+' .adding span.number').html(data.collection_count);
          } else {
            if (data.action == 'add') {
              $('a.'+$type+'-add[rel="'+data.deck_id+'"]').addClass('collection-deck-success');
            } else if (data.action == 'remove') {
              $('a.'+$type+'-add[rel="'+data.deck_id+'"]').removeClass('collection-deck-success');
            }
          }
        }
      }
    });
  }

  function collectionLinks() {
    $("#animated-icons .adding:not(.collection-link-mouseout-processed)").on("mousedown", function(){
      $(this).addClass('active');
    }).on('mouseup', function() {
      $(this).removeClass('active');
    }).addClass('collection-link-mouseout-processed');

    $("#animated-icons .deleting .fa-minus:not(.collection-link-mouseout-processed)").on("mousedown", function(){
      $(this).addClass('active');
    }).on('mouseup', function() {
      $(this).removeClass('active');
    }).addClass('collection-link-mouseout-processed');
  }

  function notes() {
    $('form#deck-notes:not(.notes-form-processed)').bind('submit', function(e) {
      e.preventDefault();
      if (!$('#signUpModal').length) {
        $.ajax({
          url: $(this).attr('action'),
          dataType: 'json',
          data: $(this).serialize(),
          type: 'post',
          success: function( response ) {
            if (response.success) {
              alert('Notes have been updated')
            }
          }
        });
      }
    }).addClass('notes-form-processed');
  }

  /*infinity scroll*/
  if(searchStringFromHome.indexOf('?search_string') + 1) {
    searchStringFromHome = searchStringFromHome.split('=')[1];
    searchString = searchStringFromHome;
  }

  if ($('.cards.collection-list').length) {

    $( window ).scroll(function() {
      var win = $(window);
      if ($(document).height() - win.height() == win.scrollTop()) {
        if ($(".collection-list .list-item").length) {
          decksSkip = $(".collection-list .list-item ").size();
        } else {
          decksSkip = $(".collection-list .collection-item ").size();
        }

        if (typeOfSearch == 'search') {
          getSearchResult(searchString, typeOfSorting, typeOfView, colors, quantity, theme, stocks, styles, decksSkip, quantity_from, quantity_to);
        } else if (typeOfSearch == 'collection' || typeOfSearch == 'wishlist' || typeOfSearch == 'tradelist') {
          getCollectionResult(typeOfSearch, typeOfSorting, typeOfView, decksSkip);
        }
      }
    });
  }
  /**/

  /*rating*/
  function ratingStars(){
    if ($('#signUpModal').length) readonly = true;
      else readonly = false;

    $(function() {
      $('.rating-select').barrating({
        theme: 'fontawesome-stars',
        readonly: readonly ? true : false
      });
    });

    //var ratingForm = $('#rate-form');
    //ratingForm.find(".rating-select").on('change', function() {
    //  rate(ratingForm);
    //});

    $('#rate-form .rating-select').on('change', function() {
      var ratingForm = $(this).closest('#rate-form');
      rate(ratingForm);
  });

    function rate(form) {
      //console.log(form.serialize());
      $.post({
        url: "/rate",
        dataType: 'json',
        data: form.serialize(),
        success: function( data ) {
          console.log('success');
        },
        error:  function(xhr, str){
          console.log('error');
        }
      });
    }
  }
  ratingStars();
});