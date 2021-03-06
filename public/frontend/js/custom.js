jQuery(document).ready(function(){
	jQuery('.shipping_charge').trigger('click');

    $('.categorytabContent .tab-pane').each(function(i, el) {
        if ( i === 0) {
            $(this).addClass('show active');
            // $('.row',this).addClass('active-blog');
            $('.row',this).slick({
                speed: 700,
                arrows: false,
                dots: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay:true,
                prevArrow: '<button type="button" class="arrow-prev"><i class="zmdi zmdi-caret-left"></i></button>',
                nextArrow: '<button type="button" class="arrow-next"><i class="zmdi zmdi-caret-right"></i></button>',
                responsive: [
                    {  breakpoint: 992,   settings: { slidesToShow: 3,  }  },
                    {  breakpoint: 768,   settings: { slidesToShow: 2, }   },
                ]
            });
        }
    });
   
    $('.active-featured-product2').slick({ 
        speed: 700, 
        arrows: true, 
        dots: false,
        slidesToShow: 5,
		autoplay:true,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="arrow-prev"><i class="zmdi zmdi-caret-left"></i></button>',
        nextArrow: '<button type="button" class="arrow-next"><i class="zmdi zmdi-caret-right"></i></button>',
        responsive: [
            {  breakpoint: 992,   settings: { slidesToShow: 3,  }  },
            {  breakpoint: 768,   settings: { slidesToShow: 1, }   },
            {  breakpoint: 480,   settings: { slidesToShow: 1, }   },
			{  breakpoint: 320,   settings: { slidesToShow: 1, }   },
        ]
    });   
    
    $('.active-featured-product').slick({ 
        speed: 700, 
        arrows: true, 
        dots: false,
        slidesToShow: 5,
		autoplay:true,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="arrow-prev"><i class="zmdi zmdi-caret-left"></i></button>',
        nextArrow: '<button type="button" class="arrow-next"><i class="zmdi zmdi-caret-right"></i></button>',
        responsive: [
            {  breakpoint: 992,   settings: { slidesToShow: 3,  }  },
            {  breakpoint: 768,   settings: { slidesToShow: 1, }   },
            {  breakpoint: 480,   settings: { slidesToShow: 1, }   },
			{  breakpoint: 320,   settings: { slidesToShow: 1, }   },
        ]
    });    


    $('.active-team-member').slick({
        speed: 700,
        arrows: false,
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay:true,
        prevArrow: '<button type="button" class="arrow-prev"><i class="zmdi zmdi-caret-left"></i></button>',
        nextArrow: '<button type="button" class="arrow-next"><i class="zmdi zmdi-caret-right"></i></button>',
        responsive: [
            {  breakpoint: 992,   settings: { slidesToShow: 3,  }  },
            {  breakpoint: 768,   settings: { slidesToShow: 1, }   },
        ]
    });

});
$(document).on('click','.shipping_charge',function(){
	var dataCouponValue = jQuery('#dataCouponValue').attr('data-coupon-value');
    var total = parseInt($(this).attr('data-price'))+parseInt($(this).attr('data-charge'));
	if(dataCouponValue){
		total = total - parseInt(dataCouponValue)
	}
    $('#checkout_total').html('BDT '+total);
});

$('.no_product').closest('.slick-active').hide();

$(window).bind('scroll', function () {
    if ($(window).scrollTop() > 200) {
        $('.layout-header1').addClass('sticky_navbar');
    } else {
        $('.layout-header1').removeClass('sticky_navbar');
    }
});

$(document).on('change','#shipping-option',function(){
    $('.shipping_details').slideToggle();
    if ($(this).is(':checked')) {
        $('input[name="shipping_division"],input[name="shipping_district"],input[name="shipping_city"],input[name="shipping_postalCode"],input[name="shipping_address"]').attr('required','true');
    }else{
        $('input[name="shipping_division"],input[name="shipping_district"],input[name="shipping_city"],input[name="shipping_postalCode"],input[name="shipping_address"]').removeAttr('required');
    }
});

$(document).on('click','input[name="size"]',function(){
    var type_price = $('input[name="type"]:checked').attr('data-add-price') ?? 0;
    var size_price = $('input[name="size"]:checked').attr('data-add-price') ?? 0;
    var color_price = $('input[name="color"]:checked').attr('data-add-price') ?? 0;
    var weight_price = $('input[name="weight"]:checked').attr('data-add-price') ?? 0;
    var product_price = $('#productPrice').val();
    var new_price = parseInt(product_price)+parseInt(size_price)+parseInt(color_price)+parseInt(weight_price)+parseInt(type_price);
    $('#updatedPrice').text('BDT '+new_price);

});

$(document).on('click','input[name="type"]',function(){
    var type_price = $('input[name="type"]:checked').attr('data-add-price') ?? 0;
    var size_price = $('input[name="size"]:checked').attr('data-add-price') ?? 0;
    var color_price = $('input[name="color"]:checked').attr('data-add-price') ?? 0;
    var weight_price = $('input[name="weight"]:checked').attr('data-add-price') ?? 0;
    var product_price = $('#productPrice').val();
    var new_price = parseInt(product_price)+parseInt(size_price)+parseInt(color_price)+parseInt(weight_price)+parseInt(type_price);
    $('#updatedPrice').text('BDT '+new_price);
});

$(document).on('click','input[name="color"]',function(){
    var type_price = $('input[name="type"]:checked').attr('data-add-price') ?? 0;
    var size_price = $('input[name="size"]:checked').attr('data-add-price') ?? 0;
    var color_price = $('input[name="color"]:checked').attr('data-add-price') ?? 0;
    var weight_price = $('input[name="weight"]:checked').attr('data-add-price') ?? 0;
    var product_price = $('#productPrice').val();
    var new_price = parseInt(product_price)+parseInt(size_price)+parseInt(color_price)+parseInt(weight_price)+parseInt(type_price);
    $('#updatedPrice').text('BDT '+new_price);
});


$(document).on('click','input[name="weight"]',function(){
    var type_price = $('input[name="type"]:checked').attr('data-add-price') ?? 0;
    var size_price = $('input[name="size"]:checked').attr('data-add-price') ?? 0;
    var color_price = $('input[name="color"]:checked').attr('data-add-price') ?? 0;
    var weight_price = $('input[name="weight"]:checked').attr('data-add-price') ?? 0;
    var product_price = $('#productPrice').val();
    var new_price = parseInt(product_price)+parseInt(size_price)+parseInt(color_price)+parseInt(weight_price)+parseInt(type_price);
    $('#updatedPrice').text('BDT '+new_price);
});


$(document).on('click','.bar_child',function(){
    $('.mymobile_menu').slideDown();
    $('.bar_child').addClass('bar_child_tow');
});

$(document).on('click','.bar_child_tow',function(){
    $('.mymobile_menu').slideUp();
    $('.bar_child').removeClass('bar_child_tow');
});

$(document).on('click','.single-footer-c',function(){
	
	if($('.contact_us_form').hasClass('shown')){
		$(this).find('.contact_us_form').slideUp(500);
		$('.contact_us_form').removeClass('shown');
		$('.carat_ico').html('<i class="zmdi zmdi-caret-down"></i>');
	}else{
		$(this).find('.contact_us_form').slideDown(500);
		$('.contact_us_form').addClass('shown');
		$('.carat_ico').html('<i class="zmdi zmdi-caret-up"></i>');
	}
    
});

jQuery(document).on('click','.product-accordion ul li input',function(){
    if(jQuery(this).attr('data-backorder-available') < 1 ){
        jQuery('.to-cart').hide();
        jQuery('.in_stock').hide();
        jQuery('.out_of_stock').show();
    }else{
        jQuery('.out_of_stock').hide();
        jQuery('.in_stock').show();
        jQuery('.to-cart').show();

    }
});


jQuery(document).on('click','#same_as_billing input',function(){
    jQuery('input[name="shipping_postalCode"]').val(jQuery('input[name="postal_code"]').val())[0].dispatchEvent(new Event('input'));
    jQuery('input[name="shipping_city"]').val(jQuery('input[name="city"]').val())[0].dispatchEvent(new Event('input'));
    jQuery('input[name="shipping_district"]').val(jQuery('input[name="district"]').val())[0].dispatchEvent(new Event('input'));
    jQuery('input[name="shipping_state"]').val(jQuery('input[name="state"]').val())[0].dispatchEvent(new Event('input'));
    jQuery('textarea[name="shipping_address"]').val(jQuery('textarea[name="address"]').val())[0].dispatchEvent(new Event('input'));
});

jQuery(document).on('click','.feedback .ul-f li',function(){
	
	if(jQuery('.feedback_details').hasClass('showing_feedback')){
		jQuery('.feedback_details').hide();
		jQuery('.feedback').css('right',-43);
		jQuery('.feedback_details').removeClass('showing_feedback');
	}else{
		jQuery('.feedback_details').show();
		jQuery('.feedback').css('right',jQuery('.feedback_details').width()+8);
		jQuery('.feedback_details').addClass('showing_feedback');
	}
	
});


jQuery(document).on('click','.feedback_submit',function(){
	if(jQuery('input[name="feedback"]').val() != '' &&  jQuery('input[name="age"]').val() != '' ){
		jQuery('.first_content').html('<br><h3>THANK YOU SO MUCH!<h3> <p>Your feedback helps take us to the next level. We???re really grateful for it.</p>');
	}
	
});

jQuery(document).on('click','.trigger_gd',function(){
	jQuery('.payment-2').show();
});

jQuery(document).on('click','.trigger_gd1',function(){
	jQuery('.payment-2').show();
});

jQuery(document).on('click','.zoom_03',function(){
	var src = jQuery(this).attr('src');
	console.log(src);
	jQuery('#zoom_03').attr('src',src);
});


jQuery(document).on('click','.show_filter',function(){
	jQuery('#filter_bar_mobile').show();
});

jQuery(document).on('click','.close_filter',function(){
	jQuery('#filter_bar_mobile').hide();
});

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}




window.addEventListener("online", (event) => {
  console.log('online');
});


/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */


jQuery(document).on('click','#pills-tab  .nav-item button',function(){

    var id = $(this).attr('data-bs-target');

    $('#pills-tab  .nav-item button').each(function() {
        $(this).removeClass('active bg-lq');
    });
    
    $('.categorytabContent .tab-pane').each(function() {
        $(this).removeClass('show active');
        // $('.row',this).removeClass('active-blog slick-initialized slick-slider');
    });
    
    $(this).addClass('active bg-lq');
    $(id).addClass('show active');
    // $('.row',id).addClass('active-blog');

    $('.row',id).slick({
        speed: 700,
        arrows: true,
        dots: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay:true,
        prevArrow: '<button type="button" class="arrow-prev"><i class="zmdi zmdi-caret-left"></i></button>',
        nextArrow: '<button type="button" class="arrow-next"><i class="zmdi zmdi-caret-right"></i></button>',
        responsive: [
            {  breakpoint: 992,   settings: { slidesToShow: 3,  }  },
            {  breakpoint: 768,   settings: { slidesToShow: 1, }   },
        ]
    });

});


