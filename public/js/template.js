//$.noConflict();
jQuery( document ).ready(function( $ ){

    var header = $(".head-menu");
    var headerHeight = header.height();
    var divContainer = header.children("div").eq(1);
    var navHeight = divContainer.children("div").last().height();
    var startHeight = headerHeight - navHeight;
    var search = divContainer.children("div").first();
    var imgHeader = divContainer.children("div").eq(1);
    var social = divContainer.children("div").eq(2);
    /*
    console.log(headerHeight);
    console.log(navHeight);
    console.log(startHeight);
    */
    $(window).scroll(function(){
        var top = $(window).scrollTop();
        //console.log(startHeight);
        //console.log(top);
        if(top > 148){
            search.addClass("d-none");
            imgHeader.addClass("d-none");
            social.addClass("d-none");
            $("#slide").addClass("top-margin-215");
            header.addClass("header-fixed");
            header.addClass("btn_scr");
            $("#logo").addClass("invisible");
            $("#logo_r").removeClass("invisible");
            $("#nav").addClass("menu_r");
        }
        else{
            search.removeClass("d-none");
            imgHeader.removeClass("d-none");
            social.removeClass("d-none");
            $("#slide").removeClass("top-margin-215");            
            header.removeClass("header-fixed");  
            header.removeClass("btn_scr");
            $("#logo_r").addClass("invisible");
            $("#logo").removeClass("invisible");
            $("#nav").removeClass("menu_r");
        }
    });
    
  $('a[href^="#"]').on('click',function (e) {
	    e.preventDefault();

	    var target = this.hash;
	    var $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 900, 'swing', function () {
	        window.location.hash = target;
	    });
	});

});