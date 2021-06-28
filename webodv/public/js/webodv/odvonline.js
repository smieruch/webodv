$(document).ready(function() {

    //var navbar_height = $('#navbar').position().top+60;

    //$('.webodv_top_nav').hide();

    //hide navbar on mouse position
    // $(document).mousemove(function( event ) {
    // 	var msg = "Handler for .mousemove() called at ";
    // 	msg += event.pageX + ", " + event.pageY;
    // 	console.log(msg)
    // 	if (event.pageY<8){
    // 	    $('.webodv_top_nav').show();
    // 	    iframe_responsive(navbar_height);
    // 	} 
    // 	if (event.pageY>=(navbar_height-10)){
    // 	    $('.webodv_top_nav').hide();
    // 	    iframe_responsive(10);
    // 	}
    // });

    var add_iframe = '<iframe src="'+ route_odvonline +'" id="odvonline_iframe" height="100%" width="100%" style="border:none;" scrolling="no"></iframe>';
    $('#add_iframe').append(add_iframe);


	//distance main py-4 to div add_iframe
	// var top_main = $('.py-4').offset().top;
	// var top_add_iframe = $('#add_iframe').offset().top;
	// var diff_main_add_iframe = top_add_iframe-top_main;
    //shift add_iframe
    //$('#add_iframe').css({'top': '-'+ (diff_main_add_iframe).toString() + 'px', 'position':'relative'});

    
    $('#hide_top_bar').on('click', function(){
	//$('.webodv_top_nav').toggle(); // this is done in webodvcore.js
	iframe_responsive();
    });

    //make the iframe responsive
    iframe_responsive = function(){




	//get height of header
	var navbar_height = $('#navbar').position().top+60;
	var navbar_height_str = (navbar_height).toString()+'px';
	// console.log("navbar_height_str")
	// console.log(navbar_height_str)


	// if (navbar_height>40){
	//     $("#add_iframe").css({'height':'83%'});
	// } else {
	//     $("#add_iframe").css({'height':'85%'});
	// }
	
	//$('#add_iframe').css({'top': navbar_height_str, 'position':'relative'});
	
	//get height of document
	//var win_height = $("#odvonline_iframe").parent().height();
	var doc_height = window.innerHeight;
	//var doc_height = $('.py-4').height();
	console.log("doc height")
	console.log(doc_height)
	// console.log("win height")
	// console.log(win_height)
	var doc_width = window.innerWidth;

	//$('#add_iframe').css({'height': height_str, 'position':'relative'});


	//console.log("doc_width="+doc_width.toString())
	var doc_width_str = doc_width.toString()+'px';
	//height of odv-online
	var odvonline_height = doc_height-navbar_height;
	var odvonline_height_str = (doc_height-navbar_height-5).toString()+'px';

	//$('#add_iframe')
	//$('#odvonline_iframe')
	$('#add_iframe').css({'height':odvonline_height_str,
				    'position':'absolute',
				    'top': navbar_height_str,
				    'width':doc_width_str});
	
    }


    //scale iframe on first call
    iframe_responsive();

    //scale iframe on window resize
    $( window ).resize(function() {
	iframe_responsive();
    });
    
    
    //disable scolling for the full page
    //$('body').css({'overflow': 'hidden'});


    //shift buttons and hide scroll
    $('#odvonline_iframe').on('load', function() {
	var myiframe = $('#odvonline_iframe').contents();
	var mybuttons = myiframe.find(".LbPane").children("button");
	//console.log(mybuttons)
	//shift station and sample buttons a little bit down to center
	mybuttons.css({'margin-top':'-5px'});

	//hide y scrollbar in iframe
	//allow x-scollbar in the iframe -> iframe attribute scrolling in odv_online_init.blade.php
	var iframe_body = myiframe.find("body");
	//console.log(iframe_body)
	//iframe_body.css({'overflow-y': 'hidden'});
	//
	//change src of the top line images, i.e. logo and help
	//and the help download link
	var odv_logo_img = myiframe.find("#TbIconPane").children("img");
	//console.log(odv_logo_img.attr('src'))
	var odv_logo_img_src = odv_logo_img.attr('src');
	//odv_logo_img.attr('src',odv_logo_img_src.replace('..','odvonline_js_css'));
	//
	var help_link = myiframe.find("#TbHelpPane").children("a");
	//console.log(help_link)
	var help_link_src = help_link.attr('href');
	//console.log(help_link_src)
	//help_link.attr('href',help_link_src.replace('..','odvonline_js_css'));
	//
	var odv_help_img = myiframe.find("#TbHelpPane").children("a").children("img");
	//console.log(odv_help_img)
	var odv_help_img_src = odv_help_img.attr('src');
	//odv_help_img.attr('src',odv_help_img_src.replace('..','odvonline_js_css'));

	//close session
	// myiframe.on("click",function(evt){
	//     //console.log(evt.target)
	//     var close_session = $(evt.target);
	//     //console.log(close_session.text())
	//     if (close_session.text() == "Close Session"){
	// 	window.location = window.location.protocol + '//' + window.location.hostname;
	//     }
	// });
	
    });
    


});

