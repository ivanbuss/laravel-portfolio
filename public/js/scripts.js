$(function() {

  /*front and back animated images for deck*/
  $(".cards .card-wrapper").mouseenter(function(){
    $(this).find(".front-img").css("display", "none");
    $(this).find(".back-img").css("display", "block");
    $(this).find(".decks-description").css("display", "block");
  });
  $(".cards .card-wrapper").mouseleave(function(){
    $(this).find(".front-img").css("display", "block");
    $(this).find(".back-img").css("display", "none");
    $(this).find(".decks-description").css("display", "none");
  });
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

});


$(document).ready(function () {
  /*colorbox*/
  $('.cboxElement:not(.colorbox-processed)').addClass("colorbox-processed").each(function(){
    $(this).colorbox({overlayClose : "true", inline:true, href: $(this).attr("href"), opacity: 0.9 , rel:'group1', previous :"<", next : ">", close : "X"});
  });

  //$('.cboxElement:not(.colorbox-processed)').addClass("colorbox-processed").each(function(){
  //  $(this).colorbox({overlayClose : "true", inline:true, href: $(this).attr("href"), opacity: 0.9 , rel:'group2', previous :"<", next : ">", close : "X"});
  //});
  /*end of colorbox*/

  /*gallery colorbox*/
  $('.galleryCboxElement').colorbox({ width:'90%', height:'90%', opacity: "0.5" , rel:'group2', previous :"<", next : ">", close : "X"});

});


