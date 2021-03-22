//**All config js of theme
(function ($) {
    'use strict';
    jQuery(document).ready(function ($) {
        // Chụp ảnh màn hình
            //ab_screenshot();
        // Tính năng hover vị trí ảnh
            //ab_Mapoid();
        // Kiểm tra Responsive
            //ab_responsive();
        // Nút cuộn lên đầu trang
        ab_BackToTop();
        //Stick nội dung
            //ab_StickyContent();
        // Thanh tìm kiếm ajax
        ab_AjaxSearch();
        // Lọc Nội dung
            //ab_AjaxContent();
            /*$( document ).on( 'elementor/popup/show' , function () { 
                ab_AjaxSearch();
            } );*/
        // Ẩn hiện nội dung 
        ab_ToggleSlideDown('.menu-toggle', 'click','.toggle' , '.catalog-menu');
        // Xem nhanh
        ab_ProductQuickView();
        // Bảo hành
            //ab_BaoHanh();
        // Chọn cửa hàng
            //ab_SelectAddress();
        // Mini cart hiển thị khi add to cart
        ab_MiniCart();
        // Custom carousel
        ab_MyCarousel('.show-slide', '.show-slide .products ', 5, 1, true, true, true, true, false, '', 2, true, false, 1, true, false);
        // Ẩn hiện sidebar
            //ab_ToggleSidebar();
        //Date Time Picker
        ab_Datetime_Picker('#datepicker');
    });
    //Date Time Picker
    function ab_Datetime_Picker(wrap){
        if($( wrap )[0]){
            $( wrap ).datepicker({
                altField: "#alternate",
                altFormat: "DD, d MM, yy"
            });
        }
    }
    //Toggle slidedown
    function ab_ToggleSlideDown( wrap, event, selector, show) {
        if($(wrap)[0]){
            $(document).on(event,selector, function(e){
                e.preventDefault();
                $(this).toggleClass('active');
                $(this).parents(wrap).toggleClass('loading');
                $(this).parents(wrap).find(show).slideToggle();
            });
        }
    };
    // Ẩn hiện sidebar
    function ab_ToggleSidebar(){
        if($('.sidebar-toggle-button')[0]){
            $(document).on('click','.sidebar-toggle-button .button-toggle', function(){
                $(this).parent().addClass('active');
                $(this).parents('body').find('.elementor-widget-sidebar').addClass('show');
            });
            $(document).on('click','.close-button, .mash', function(){
                $('.sidebar-toggle-button').removeClass('active');
                $('.elementor-widget-sidebar').removeClass('show');
            });
        }
    };
    // Custom carousel
    function ab_MyCarousel(wrap,selector,slides_to_show=4,scroll=1,arrows=true,dots=false,
        infinite=true,autoplay=false,centerMode=false,centerPadding='',
        tab_slidesToShow=2,tab_arrows=true,tab_dots=false, mb_slidesToShow=2,mb_arrows=true,mb_dots=false){
        $(wrap).each(function () {
            if (!$(selector).hasClass('slick-slider')) {
                $(selector).slick({ 
                    slidesToShow: slides_to_show,
                    slidesToScroll: scroll,
                    arrows: arrows,
                    dots: dots,
                    infinite: infinite,
                    autoplay: autoplay,
                    centerMode: centerMode,
                    centerPadding: centerPadding,
                    prevArrow: '<span class="ab-carousel-btn prev-item"><i class="ab-font ab-icon-prev"></i></span>',
                    nextArrow: '<span class="ab-carousel-btn next-item "><i class="ab-font ab-icon-next"></i></span>',
                    
                    responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: tab_slidesToShow,
                            slidesToScroll: 1,
                            arrows: tab_arrows,
                            dots: tab_dots,
                        }
                    },
                    {
                        breakpoint: 768.98,
                        settings: {
                            slidesToShow: mb_slidesToShow,
                            slidesToScroll: 1,
                            arrows: mb_arrows,
                            dots: mb_dots,
                        }
                    }
                    ]
                });
            }
        });
    };
    //Mini cart hiển thị khi add to cart
    function ab_MiniCart(){
        if (typeof wc_add_to_cart_params != 'undefined') {
            $(document).on('added_to_cart', function (event, fragments) {
                $('.elementor-menu-cart__container').addClass('elementor-menu-cart--shown');
            });
            $(document).on('click', 'button.single_add_to_cart_button:not(.disabled)', function (e) {
                e.preventDefault();
                var $thisbutton = $(this),
                $form = $thisbutton.closest('form.cart'),
                id = $thisbutton.val(),
                product_qty = $form.find('input[name=quantity]').val() || 1,
                product_id = $form.find('input[name=product_id]').val() || id,
                variation_id = $form.find('input[name=variation_id]').val() || 0;
                var variations={};
                $form.find('select').each(function () {
                    variations[$(this).attr('name')]=$(this).val();
                });
                var data = {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: product_id,
                    product_sku: '',
                    quantity: product_qty,
                    variation_id: variation_id,
                    variations: variations,
                };
                $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
                $.ajax({
                    type: 'post',
                    url: wc_add_to_cart_params.ajax_url,
                    data: data,
                    beforeSend: function (response) {
                        $thisbutton.removeClass('added').addClass('loading');
                    },
                    complete: function (response) {
                        $thisbutton.addClass('added').removeClass('loading');
                    },
                    success: function (response) {
                        if (response.error & response.product_url) {
                            window.location = response.product_url;
                            return;
                        } else {
                            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                            $('.elementor-menu-cart__container').addClass('elementor-menu-cart--shown');
                        }
                    },
                });
                return false;
            });
        }
    };
    //Chọn địa chỉ
    function ab_SelectAddress(){
        if($('.store-infomation')[0]){
            //Tabs
            $(document).on('click','.map-7-11 .choose-address', function(){
                $('.map-7-11 .item').removeClass('active');
                $('.list-panel .select').css('display','none');
                $('.list-panel .select-address').css('display','block');
                $(this).addClass('active');
            });
            $(document).on('click','.map-7-11 .choose-name', function(){
                $('.map-7-11 .item').removeClass('active');
                $('.list-panel .select').css('display','none');
                $('.list-panel .select-name').css('display','block');
                $('.store-infomation').find('.show-address *').remove();
                $(this).addClass('active');
            });
            $(document).on('click','.map-7-11 .choose-code', function(){
                $('.map-7-11 .item').removeClass('active');
                $('.list-panel .select').css('display','none');
                $('.list-panel .select-code').css('display','block');
                $('.store-infomation').find('.show-address *').remove();
                $(this).addClass('active');
            });

            $('.btn-show-store-infomation').on('click', function(e){
                e.preventDefault();
                $('.check-store').addClass('active');
            });
            $('.close-panel,.btn-choose-store').on('click', function(e){
                e.preventDefault();
                $('.check-store').removeClass('active');
            });
            $('.select-address select.sub_distric').change(function(){
                var value_city = $(this).parent().find('.city').val();
                var value_distric = $(this).parent().find('.distric').val();
                var value_sub_distric = $(this).val();
                var data = {
                    action: 'ab_ajax_address_show',
                    value_city: value_city,
                    value_distric: value_distric,
                    value_sub_distric: value_sub_distric,
                };
                $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: "POST",
                }).success(function (response) {
                    if(response){
                        $('.store-infomation').removeClass('loading');
                        $('.check-store').addClass('loaded');
                        $('.store-infomation').find('.show-address *').remove();
                        $('.store-infomation').find('.show-address').append(response);
                        var address = $(response).find('.store-address').text();
                        var code = $(response).find('.store-code').text();
                        $('#ab_address_field').val(address);
                        $('#ab_code_field').val(code);
                    }
                    else{
                        console.log("Không có kết quả");
                    }
                    
                }).error(function (ex) {
                    console.log(ex);
                });
            });
            $('.select-address select').change(function(){
                var value = $(this).val();
                if(value=='#'){
                    return;
                }
                $(this).parents('.store-infomation').addClass('loading');
                var field = $(this).data('field');
                var filter = $(this).data('filter');
                var data = {
                    action: 'ab_ajax_address',
                    value: value,
                    field: field,
                    filter: filter,
                };
                $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: "POST",
                }).success(function (response) {
                    if(response){
                        $('.store-infomation').removeClass('loading');
                        $('.select-address').find('.'+filter+' *:not(.default)').remove();
                        $('.select-address').find('.'+filter).append(response);
                        $('.select-address').find('.'+filter).removeClass('un-active');
                        $('.select-address').find('.'+filter).addClass('active');
                        if(filter=='distric'){
                            $('.mess-distric').css('display','block');
                        }
                        if(filter=='sub_distric'){
                            $('.mess-sub-distric').css('display','block');
                        }
                    }
                    else{
                        console.log("Không có kết quả");
                    }
                    
                }).error(function (ex) {
                    console.log(ex);
                });
            });
            //Chọn tên
            $(document).on('click','.btn-name', function(e){
                e.preventDefault();
                var name = $(this).parent().find('.name').attr('value');
                if( name ){
                 $(this).parents('.store-infomation').addClass('loading');
                 var data = {action: 'ab_find_store_name', name: name}; 
                 $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: "POST",
                    success: function (response) {
                        $('.store-infomation').removeClass('loading');
                        $('.check-store').addClass('loaded');
                        $('.store-infomation').find('.show-address *').remove();
                        $('.store-infomation').find('.show-address').append(response);
                        var address = $(response).find('.store-address').text();
                        var code = $(response).find('.store-code').text();
                        $('#ab_address_field').val(address);
                        $('#ab_code_field').val(code);
                    }
                });
             }
             else{
                alert('Xin bạn vui lòng nhập lại tên cửa hàng');
                return;
            }

        });
            //Chọn tên
            $(document).on('click','.btn-code', function(e){
                e.preventDefault();
                var code = $(this).parent().find('.code').attr('value');
                if( code ){
                    console.log(code);
                    $(this).parents('.store-infomation').addClass('loading');
                    var data = {action: 'ab_find_store_code', code: code}; 
                    $.ajax({
                        url: ajaxurl,
                        data: data,
                        type: "POST",
                        success: function (response) {
                            $('.store-infomation').removeClass('loading');
                            $('.check-store').addClass('loaded');
                            $('.store-infomation').find('.show-address *').remove();
                            $('.store-infomation').find('.show-address').append(response);
                            var address = $(response).find('.store-address').text();
                            var code = $(response).find('.store-code').text();
                            $('#ab_address_field').val(address);
                            $('#ab_code_field').val(code);
                        }
                    });
                }
                else{
                    alert('Xin bạn vui lòng nhập lại mã cửa hàng');
                    return;
                }
                
            });
        }
    };
    //Bảo Hành
    function ab_BaoHanh(){
        if($('.bao-hanh')[0]){
            $(document).on('click','.btn-bao-hanh', function(e){
                e.preventDefault();
                var $this = $(this);
                var code = $this.parent().find('.code').attr('value');
                if( code ){
                 $this.addClass('loading');
                 var data = {action: 'ab_bao_hanh', code: code}; 
                 $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: "POST",
                    success: function (response) {
                        if($('.bao-hanh-content')[0]){
                            $('.bao-hanh-content').remove()
                        }
                        $('.bao-hanh-append').append(response);
                        $this.removeClass('loading'); 
                    }
                });
             }
             else{
                alert('Xin bạn vui lòng nhập mã bảo hành được cung cấp');
                return;
            }

        });
        }
    };
    
    //Image map
    function ab_Mapoid(){
        if($("map[name=map]")[0]){
            $("map[name=map]").mapoid({
                fillColor:'#fc6702',
                fadeTime: 500,
                fillOpacity: 0.5,
                selectOnClick:false,
                click: function(){
                    alert("OK");
                },
            });
        }
    };
    //Responsive
    function ab_responsive(){
        $(document).on('click','#top-sidebar-prebuild .responsive-devices li',function(){
            var device = $(this).data('device');
            $(this).parents('body').find('#show-device').removeAttr('class');
            $(this).parents('body').find('#show-device').addClass(device);
        });
        
    };
    //Take screen shoot
    function ab_screenshot(){
        if($('#btnSave')[0]){
            $(document).on('click','#btnSave',function(){
                $(this).addClass('loading');
                html2canvas($("#show-device"), {
                    allowTaint: true,
                    onrendered: function(canvas) {
                        //$('#output-image').append(canvas);
                        $('#btnSave').removeClass('loading');
                        var dataURL = canvas.toDataURL();
                        var a = document.createElement('a');
                        a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                        a.download = 'ab-screenshot.jpg';
                        a.click();
                        //alert('Ảnh đã được tải về máy tính của bạn, hãy gửi ảnh và yêu cầu thiết kế cho chúng tôi.');
                    }
                });
            });
        }
        
    };
    //Sticky kit
    function ab_StickyContent(warp = '.sticky-kit', height) {
        if (typeof  $.fn.stick_in_parent != 'undefined' && $(warp)[0]) {
            var window_width = '';
            $(window).resize(function () {
                if (window_width != $(window).width()) {
                    window_width = $(window).width();
                    let $stick_content = $(warp);
                    if (window_width > 1025) {
                        if(height){
                            $stick_content.stick_in_parent({offset_top: height});
                        }
                        else{
                            $stick_content.stick_in_parent({offset_top: $('div[data-elementor-type=header]').height()});
                        }
                        
                    } else {
                        $stick_content.trigger('sticky_kit:detach');
                    }
                }
            }).resize();
        }
    };
    // Ajax load content use shortcode template
    function ab_AjaxContent(){

        $(document).on( 'click','.filter-key .key', function(e){
            e.preventDefault();
            if($(this).hasClass('active')){
                return;
            }
            $(this).parents('.ajax-filter-content').find('.filter-key .key').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.ajax-filter-content').find('.loading').addClass('active');
            var data = {} ;
            data['id'] = $(this).data('id');
            data['action'] = 'content';
            $.ajax({
                url: ajaxurl,
                data: data,
                type: 'post',
            }).success(function (response) {
                $('.ajax-filter-content .loading').removeClass('active');
                $('.ajax-filter-content').find('.filter-content>*').remove();
                $('.ajax-filter-content').find('.filter-content').append(response);
                ab_StickyContent();
            }).error(function (ex) {
                console.log(ex);
            });

        });
    };
    // Ajax Search
    function ab_AjaxSearch() {
        if($('.ajax-search')[0]){
            $('.ajax-search select[name=categories]').change(function(){
                var wrap = $(this).parents('.ajax-search');
                $(this).parents('.ajax-search').find('.close-result').addClass('loading');
                wrap.find('.close-result').removeClass('active');
                var cate_name = $(this).val();
                var post_type = wrap.find('input[name=post_type]').attr('value');
                var taxonomy = wrap.find('input[name=post_type]').attr('taxonomy');
                var data = {
                    action: 'ab_ajax_search',
                    post_type: post_type,
                    taxonomy: taxonomy,
                    cate_name: cate_name,
                };
                $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: "POST",
                }).success(function (response) {
                    if(response){
                        wrap.find('.ajax-search-result').remove();
                        wrap.find('.close-result').removeClass('loading');
                        wrap.find('.close-result').addClass('active');
                        if(data['cate_name']){
                            wrap.append(response);
                        }
                        else{
                            wrap.find('.close-result').removeClass('active');
                        }
                        
                    }
                    else{
                        wrap.find('.close-result').removeClass('loading');
                        wrap.find('.ajax-search-result').remove();
                        wrap.find('.close-result').addClass('active');
                        wrap.append('<ul class="ajax-search-result"><li class="search-result no-result">Xin lỗi, không có kết quả được tìm thấy!</li></ul>');
                        
                    }
                    
                }).error(function (ex) {
                    console.log(ex);
                });
            });
            $('.ajax-search .ipt').keyup(function() {
                var wrap = $(this).parents('.ajax-search');
                var cate_name = $(this).parents('.ajax-search').find('.zoo-search-category select[name=categories]').val();
                var text = $(this).val();
                var post_type = wrap.find('input[name=post_type]').attr('value');
                var taxonomy = wrap.find('input[name=post_type]').attr('taxonomy');
                wrap.find('.close-result').removeClass('active');
                if(text.length){
                    $(this).parents('.ajax-search').find('.close-result').addClass('loading');
                    var data = {
                        action: 'ab_ajax_search',
                        post_type: post_type,
                        taxonomy: taxonomy,
                        keyword: text,
                        cate_name: cate_name,
                    };
                    $.ajax({
                        url: ajaxurl,
                        data: data,
                        type: "POST",
                    }).success(function (response) {
                        if(response){
                            wrap.find('.close-result').removeClass('loading');
                            wrap.find('.close-result').addClass('active');
                            wrap.find('.ajax-search-result').remove();
                            wrap.append(response);
                        }
                        else{
                            wrap.find('.ajax-search-result').remove();
                            wrap.find('.close-result').addClass('active');
                            wrap.find('.close-result').removeClass('loading');
                            wrap.append('<ul class="ajax-search-result"><li class="search-result no-result">Xin lỗi, không có kết quả được tìm thấy!</li></ul>');
                        }
                        
                    }).error(function (ex) {
                        console.log(ex);
                    });

                }
                else{
                    wrap.find('.ajax-search-result').remove();
                }
                
            });

            $(document).on('click','.ajax-search .btn',function(e) {
                e.preventDefault();
                var wrap = $(this).parents('.ajax-search');
                var cate_name = $(this).parents('.ajax-search').find('.zoo-search-category select[name=categories]').val();
                var text = $(this).parents('.ajax-search').find('.search-form-ajax .ipt').val();
                var post_type = wrap.find('input[name=post_type]').attr('value');
                var taxonomy = wrap.find('input[name=post_type]').attr('taxonomy');
                wrap.find('.close-result').removeClass('active');
                if(text.length){
                    $(this).parents('.ajax-search').find('.close-result').addClass('loading');
                    var data;
                    data = {
                        action: 'ab_ajax_search',
                        post_type: post_type,
                        taxonomy: taxonomy,
                        keyword: text,
                        cate_name: cate_name,
                    };
                    $.ajax({
                        url: ajaxurl,
                        data: data,
                        type: "POST",
                    }).success(function (response) {
                        if(response){
                            wrap.find('.close-result').removeClass('loading');
                            wrap.find('.close-result').addClass('active');
                            wrap.find('.ajax-search-result').remove();
                            wrap.append(response);
                        }
                        else{
                            wrap.find('.close-result').addClass('active');
                            wrap.find('.close-result').removeClass('loading');
                            wrap.append('<ul class="ajax-search-result"><li class="search-result no-result">Xin lỗi, không có kết quả được tìm thấy!</li></ul>');
                        }
                        
                    }).error(function (ex) {
                        console.log(ex);
                    });

                }
                else{
                    wrap.find('.ajax-search-result').remove();
                }
                
            });
            $(document).on('click','.close-result.active', function() {
                var wrap = $(this).parents('.ajax-search');
                $(this).removeClass('active');
                wrap.find('.ajax-search-result').remove();
            });
            $('.ajax-search .ipt').keypress(function(e) {
                var keycode = (e.keyCode ? e.keyCode : e.which);
                if (keycode == '13') {
                    e.preventDefault();
                }
            });
        }
    };
    //Back to top function
    function ab_BackToTop() {
        $(window).on('load',function () {
            if ($('#ab-back-to-top')[0]) {
                let $toTopButton = $('#ab-back-to-top');
                jQuery(window).on("scroll", function () {
                    if ($(window).scrollTop() > 100) {
                        $toTopButton.addClass('active')
                    } else {
                        $toTopButton.removeClass('active')
                    }
                });
                $(document).on('click', '#ab-back-to-top', function (e) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: 0
                    }, 700);
                });
            }
        });
    };
    //Product Quickview
    function ab_ProductQuickView(){
        $(document).on('click', '.product .btn-quick-view', function (e) {
            e.preventDefault();
            var $this = $(this);
            $this.addClass('loading');
            var load_product_id = $(this).attr('data-productid');
            var data = {action: 'ab_quick_view', product_id: load_product_id}; 
            $.ajax({
                url: ajaxurl,
                data: data,
                type: "POST",
                success: function (response) {
                    $('body').append(response);
                    $this.removeClass('loading'); 
                }
            });
        });
        $(document).on('click', '.close-quickview, .mash-color', function (e) {
            e.preventDefault();
            $('#ab-quickview-lb').remove();
        });
    };

})(jQuery);