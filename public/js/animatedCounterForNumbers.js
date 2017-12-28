(function( $ ){
  "use strict";

  $.fn.counterUp = function( options ) {

    // Defaults
    var settings = $.extend({
      'time': 400,
      'delay': 10
    }, options);

    return this.each(function(){

      // Store the object
      var $this = $(this);
      var $settings = settings;

      var counterUpper = function() {
        var nums = [];
        var divisions = $settings.time / $settings.delay;
        var num = $this.text();
        var isComma = /[0-9]+,[0-9]+/.test(num);
        num = num.replace(/,/g, '');
        var isInt = /^[0-9]+$/.test(num);
        var isFloat = /^[0-9]+\.[0-9]+$/.test(num);
        var decimalPlaces = isFloat ? (num.split('.')[1] || []).length : 0;

        // Generate list of incremental numbers to display
        for (var i = divisions; i >= 1; i--) {

          // Preserve as int if input was int
          var newNum = parseInt(num / divisions * i);

          // Preserve float if input was float
          if (isFloat) {
            newNum = parseFloat(num / divisions * i).toFixed(decimalPlaces);
          }

          // Preserve commas if input had commas
          if (isComma) {
            while (/(\d+)(\d{3})/.test(newNum.toString())) {
              newNum = newNum.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
            }
          }

          nums.unshift(newNum);
        }

        $this.data('counterup-nums', nums);
        $this.text('0');

        // Updates the number until we're done
        var f = function() {
          $this.text($this.data('counterup-nums').shift());
          if ($this.data('counterup-nums').length) {
            setTimeout($this.data('counterup-func'), $settings.delay);
          } else {
            delete $this.data('counterup-nums');
            $this.data('counterup-nums', null);
            $this.data('counterup-func', null);
          }
        };
        $this.data('counterup-func', f);

        // Start the count up
        setTimeout($this.data('counterup-func'), $settings.delay);
      };

      // Perform counts when the element gets into view
      $this.waypoint(counterUpper, { offset: '100%', triggerOnce: true });
    });

  };


})( jQuery );


$( document ).ready(function() {
  var b = $('.counter2').addClass("counter-started").attr("data-counter2");
  var a = $('.counter1').addClass("counter-started").attr("data-counter1");


  $( window ).on("scroll", function() {
    if(window.pageYOffset  > 500){

      if (a > 1000) {
        a = a.substring(0, a.length - 3) + "," + a.substring(a.length - 3, a.length);
        $('.counter1.counter-started').empty().append(a).counterUp({
          delay: 10,
          time: 1000
        });
      }
      if (b > 1000) {
        b = b.substring(0, b.length - 3) + "," + b.substring(b.length - 3, b.length);
        $('.counter2.counter-started').empty().append(b).counterUp({
          delay: 10,
          time: 1000
        });
      }
    }
  });
});


