$( document ).ready(function() {
	

// profile menu open and close 

    $('.left-top-nav').click(function() {
		
        $('body').toggleClass('opened-ui');
        $('.main-wrapper').toggleClass('casino leftMenu');
		$('.left-nav-container-m').toggleClass('open');
		$('.left-top-nav').removeClass('hidden')
		
    });
	
	$('.closed-nav-icon').click(function() {
		
        $('body').removeClass('opened-ui');
        $('.main-wrapper').removeClass('casino leftMenu');
		$('.left-nav-container-m').removeClass('open');
		$('.wrapper-m').removeClass('rightMenu');
		$('.right-nav-container-m').removeClass('open');
		$('.left-top-nav').removeClass('hidden');
		
    });
	
	
// end profile menu open and close


// user menu open and close 

    $('.sw-contain-b-reg-in.ss').click(function() {

        $('body').toggleClass('opened-ui');
        $('.wrapper-m').toggleClass('rightMenu');
		$('.right-nav-container-m').toggleClass('open');
		$('.home-wrapper-login-block').toggleClass('closed-nav-icon');
		$('.home-user-icon').toggleClass('hidden');
		
		if ($('.right-nav-container-m.ss').hasClass('open')) {
        $(".home-user-icon").css({display: "none"});
        $(".usernameMoney").css({display: "none"});
        
		} else {
        $(".home-user-icon").css({display: "unset"});
$(".usernameMoney").css({display: "inline-block"});
		}
		
    });
	
	
	
// end menu open and close


// signup and sign in menu open and close 

    $('.right-top-nav-new-h.signin').click(function() {
		
		$('.sw-contain-b-reg-in').removeClass('registrationForm');
		$('.sign-in-m').removeClass('hidden');
		$('.registration-form-b').addClass('hidden');
		$('.left-top-nav').addClass('hidden');
        $('body').addClass('opened-ui');
        $('.wrapper-m').addClass('rightMenu');
		$('.right-nav-container-m').addClass('open');
		
    });
	
    $('.right-top-nav-new-h.signup').click(function() {
		
		$('.sw-contain-b-reg-in').addClass('registrationForm');
		$('.registration-form-b').removeClass('hidden');
		$('.sign-in-m').addClass('hidden');
		$('.left-top-nav').addClass('hidden');
        $('body').addClass('opened-ui');
        $('.wrapper-m').addClass('rightMenu');
		$('.right-nav-container-m').addClass('open');
		
    });
	
	
	    $('.open-signin').click(function() {
		
		$('.sw-contain-b-reg-in').removeClass('registrationForm');
		$('.sign-in-m').removeClass('hidden');
		$('.registration-form-b').addClass('hidden');
		$('.left-top-nav').addClass('hidden');
        $('body').addClass('opened-ui');
        $('.wrapper-m').addClass('rightMenu');
		$('.right-nav-container-m').addClass('open');
		
    });
    
    
        $('.open-signup').click(function() {
		
		$('.sw-contain-b-reg-in').addClass('registrationForm');
		$('.registration-form-b').removeClass('hidden');
		$('.sign-in-m').addClass('hidden');
		$('.left-top-nav').addClass('hidden');
        $('body').addClass('opened-ui');
        $('.wrapper-m').addClass('rightMenu');
		$('.right-nav-container-m').addClass('open');
		
    });
	
	
// end signup and sign in menu open and close 
	
	
//top first slider	
  $(".first-slider").slick({
        dots: true,
        infinite: true,
        slidesToShow: 3,
		autoplay: true,
        slidesToScroll: 1,
		  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
      slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }
  ]
      });
// end top first slider	


// scroll buttons right left sports cat
$('.sports-navigation-scroll-buttons.left-button').click(function() {
  event.preventDefault();
  $('.sport-nav-container-m').animate({
    scrollLeft: "-=200px"
  }, "slow");
});

 $('.sports-navigation-scroll-buttons.right-button').click(function() {
  event.preventDefault();
  $('.sport-nav-container-m').animate({
    scrollLeft: "+=200px"
  }, "slow");
});
// end scroll buttons right left sports cat


//top best events slider	
  $(".best-events").slick({
        dots: true,
        infinite: true,
        slidesToShow: 3,
		autoplay: true,
        slidesToScroll: 1,
		  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
      slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }
  ]
      });
// end top best events slider



//slot games index slider	
  $(".slot-slider").slick({
        dots: true,
        infinite: true,
        slidesToShow: 3,
		centerMode: true,
		autoplay: true,
        slidesToScroll: 1
      });
// end slot games index slider

    // scroll top page

    var scrollTop = $("#scrollup");

    $(window).scroll(function() {

        var topPos = $(this).scrollTop();


        if (topPos > 100) {
            $(scrollTop).css("display", "block");
        } else {
            $(scrollTop).css("display", "none");
        }

    });


    $(scrollTop).click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;

    });

    // end scroll top page


// bet slip open and close
$('.betslip-balance-view-m').click(function() {
	
$('.betslip-full-view').addClass('active');

});


$('.closed-betslip-icon').click(function() {
	
$('.betslip-full-view').removeClass('active');

});

// end bet slip open and close


// lang select and timezone menu

      $('.goto-url-select').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
// end lang select and timezone menu

// toggle match box

    $('.single-market-title-m').click(function() {
        $(this).toggleClass('active', 2000);
    });

// end toggle match box

    // more event sportradar tabs

    $('ul.tabss li').click(function() {
        var tab_id = $(this).attr('data-tab');

        $('ul.tabss li').removeClass('active');
        $('.tab-pane').removeClass('active in');

        $(this).addClass('active');
        $("#" + tab_id).addClass('active in');
    });

   // end more event sportradar tabs


});