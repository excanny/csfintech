(function($) {
  'use strict';
  $(function() {
    var body = $('body');
    var contentWrapper = $('.content-wrapper');
    var scroller = $('.container-scroller');
    var footer = $('.footer');
    var sidebar = $('.sidebar');

    //Add active class to nav-link based on url dynamically
    //Active class can be hard coded directly in html file also as required

    function addActiveClass(element) {
      // if (current === "") {
        //for root url
        // if (element.attr('href') !== current) {
        //   element.parents('.nav-item').last().addClass('active');
        //   if (element.parents('.sub-menu').length) {
        //     element.closest('.collapse').addClass('show');
        //     element.addClass('active');
        //   }
        // }
      // } else {
        if (current.slice(-1) === '/') {
            current = current.substring(0, current.length - 1);
        }
        //for other url
        if (element.attr('href') === current) {
            // console.log(element.parents('.nav-item'));
          element.parents('.nav-item').last().addClass('active');
          // if (element.parents('.sub-menu').length) {
          //   element.closest('.collapse').addClass('show');
          //   element.addClass('active');
          // }
          // if (element.parents('.submenu-item').length) {
          //   element.addClass('active');
          // }
        }
        else {
            element.parents('.nav-item').last().removeClass('active');
        }
      // }
    }

    var current = location.href;
    $('.nav li a', sidebar).each(function() {
        var $this = $(this);
      addActiveClass($this);
    });

    //Close other submenu in sidebar on opening any

    sidebar.on('show.bs.collapse', '.collapse', function() {
      sidebar.find('.collapse.show').collapse('hide');
    });


    //Change sidebar

    $('[data-toggle="minimize"]').on("click", function() {
      body.toggleClass('sidebar-icon-only');
    });

    //checkbox and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
  });
})(jQuery);
