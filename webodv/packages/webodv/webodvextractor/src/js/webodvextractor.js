
//Global vars

var old_storage_name = userId + '_' + project + '_' + datasetname;

var storage_name = '12' + '_' + userId + '_' + project + '_' + datasetname;


var websocket_login = false;
//remove localstorage
localStorage.removeItem(old_storage_name);
// var i=1;
// for (i=1;i<=10;i++){
//     localStorage.removeItem(i + '_' + old_storage_name);
// }
//


var treeview_emodnet_contaminants_connect_go = true;
var init_outvar_treeview_done = false;


var loading_snippet = '<div class="loading_snippet"><h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1></div>';

// console.log("scatter_plot_button_snippet")
// console.log(scatter_plot_button_snippet)



//use get_data_availability_information
//for every zoom command to check for the newly availabe data
// if (contaminants == true && project == "EMODnet Chemistry"){
//     var switch_get_canvas_get_data = true;
// } else {
//     var switch_get_canvas_get_data = false;
// }
var switch_get_canvas_get_data = false;

var selected_variables_vars_height = "300px";

var treeview_mode = 0;

var W_get_data_availability_information = {};

var page2_visit = false;

var webodv_status = {};
//this is needed in emodnet.js

//output vars
var output_vars_list = {};
var output_p01_list = {};
var reset_clicked = false;

var scatterplot_yes_button_clicked = false;

var use_all_vars = true;
var var_x_dropdown = "";

var primary_var_id = 0;

var a_variable_has_changed = "";

webodv_status[storage_name] = {};

//move map window to top
//move scatter plot to right
var move_map_scatter = true;
var count_move_map_scatter = 0;

//var window.storage_name = {};
//webodv_status.name ={storage_name}
//console.log(webodv_status)
//
var pager_num = 1;
var pager_num_previous = 1;
var websocket = null;

var download_image_name = "";
//map coordinates etc.
var modify_view_data = {};
//map coordinates etc.
//zoom
//console.log(wsUri)
//console.log(point_size)
//console.log(default_koordinates)
var k1 = default_koordinates[0]; //-1e10;
var k2 = default_koordinates[1]; //1e10;
var m1 = default_koordinates[2]; //-1e10;
var m2 = default_koordinates[3]; //1e10;
var zoom_options = {};
//zoom
//pointsize
var pointsize = Number(point_size);
//
//canvas
var orig_size_img_x = "";
var orig_size_img_y = "";
var screen_size_img_x = "";
var screen_size_img_y = "";
var img_offset = {};
var htmlcanvas = {};
var left_click_data = {};
var modify_view_data = {};
var get_dock_metavar_labels = {};
var get_dock_metavar_labels_list = "";
var get_dock_datavar_labels = {};
var get_dock_datavar_labels_list = "";
//
//dates
//console.log(dates)
//arrays must be related component wise otherwise a change of dates yields a change of default_dates
var dates = [];
dates[0] = default_dates[0];
dates[1] = default_dates[1];
//console.log("start")
//console.log(default_dates)

var W_get_collection_information = {};

var p01_list = {};
var p01_num = [];



//required vars
var required_vars_mode = false;
var required_vars_text = "0 variables selected";


//visualization axis
var x_var = default_x_visualization-1;
var y_var = default_y_visualization-1;
depth_var_num = depth_var_num-1;
//console.log("x_var="+x_var)
//console.log("y_var="+y_var)
//console.log("type x_var="+typeof(x_var))
//console.log("type y_var="+typeof(y_var))


var viz_var_trigger = true;

var y_axis_reversed = [];
var x_axis_reversed = [];

var Xstatus = {};

var date1 = [];
var date2 = [];
//treeview
var treeview_exists = false;
var treeview_vars_id = "";
var treeview_container_search_div = "";
var List = {"id" : [], "dataid" : [], "text" : []};
var List_full = {"id" : [], "dataid" : [], "text" : []};
var List_indeterminate = {"id" : [], "dataid" : [], "text" : []};
var emodnet_contaminants_tree = {}; //save treeview for emodnet
var OutVarState = {};
//
//currently selected station
var current_cruise = "";
//

//get browser height
var window_height = $(window).height();
//console.log("window_height="+window_height)

var enable_pager = true;


    //tooltips
    $(function () {
	$('[data-toggle="tooltip"]').tooltip()
    })

    //hide tooltips after 2 seconds
    $(document).on('shown.bs.tooltip', function (e){
	//console.log("tooltip shown")
	//console.log(e.target)
	var that = e.target;
	//console.log($(that).)
	// setTimeout(function(){
        //     $(that).tooltip('hide');
	// }, 2000);
    });



$(document).ready(function() {
    //console.log("webodvextractor.js")

    //csrf ajax
    $.ajaxSetup({
	headers: {
    	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
    });


    $('.data_preview').html(scatter_plot_button_snippet);
    
    //emodnet variables dev mode
    if (typeof(role) != "undefined"){
    	if (role == "devuser"){
    	    $('#variables_dev_mode_li').show();
    	}
    }
    
    $('#output_vars_help_close_button').on('click', function(){
	// console.log("password")
	// console.log($('#variables_dev_mode').val())
	var dev_pass = $('#variables_dev_mode').val();

	$.ajax({
    	    type: "POST",
	    url: window.location.protocol + '//' + window.location.hostname + http_port + '/dev/emodnet/variables_dev',
	    data: {"dev_pass":dev_pass},
	    dataType: "json",
    	    cache: "false",
	    func: "dev_emodnet_variables_dev",
    	    error:   function(data){
    		//console.log("ERROR");
    	    },
    	    success: function(data){
    		//console.log("SUCCESS");
    	    },
	});
	
    });

    
    //help

    if (typeof(datasetname) != "undefined"){
	$('#extractor_reset_all,#extractor_help').show();
    } else {
	$('#extractor_reset_all,#extractor_help').hide();
    }




    //this is needed to tell emodnet.js, that it already exists
    var extractor_help_closure = true
    //only for emodnet    
    if (project == "EMODnet Chemistry"){
	$('#extractor_help').on('click',function(e){
	    e.preventDefault();
	    $('#extractor_help_modal').modal('show');	
	});
    }
    //eraser
    $('.extractor_eraser').on('click',function(e){
	e.preventDefault();
	$('#extractor_eraser_modal').modal('show');	
    });

    $('#outliers_help').on('click',function(e){
	//console.log("click")
	$('#hide_outliers_modal').modal('show');
    });

    $('.help_output_var').on('click',function(e){
	$('#output_vars_help_modal').modal('show');
    });

    $('.selected_vars_info_help').on('click',function(e){
	//console.log("click")
	$('#and_or_help_modal').modal('show');
    });

    //check if a modal is open
    $('.modal').on("shown.bs.modal", function(){
	//console.log("visible")
	//disable pager
	//disable pager during loading
	$('.page-link').addClass('pager-disabled');
	enable_pager = false;
    });
    $('.modal').on("hidden.bs.modal", function(){
	//enable pager during loading
	$('.page-link').removeClass('pager-disabled');
	enable_pager = true;
    });

    

    $('#page2_button_enlarge').on('click',function(){
	
	//close tooltip
	$(this).tooltip("hide");

	if ($(this).hasClass("map_small")){
	    $(this).removeClass("map_small").addClass("map_large");
	    //change left column
	    $('.page2_map_left').removeClass("col-4").addClass("col-1");
	    //change center column
	    $('.page2_map').removeClass("col-4").addClass("col-10");
	    //change right column
	    $('.page2_map_right').removeClass("col-4").addClass("col-1");
	    //change button symbol
	    $(this).html('<i class="fa fa-compress"></i>');
	    //change tooltip
	    //$(this).tooltip({title: "Decrease image size"});
	    $(this).attr("data-original-title","Decrease image size");
	} else if ($(this).hasClass("map_large")){
	    $(this).removeClass("map_large").addClass("map_small");
	    //change left column
	    $('.page2_map_left').removeClass("col-1").addClass("col-4");
	    //change center column
	    $('.page2_map').removeClass("col-10").addClass("col-4");
	    //change right column
	    $('.page2_map_right').removeClass("col-1").addClass("col-4");
	    //change button symbol
	    $(this).html('<i class="fa fa-expand"></i>');
	    //change tooltip
	    //$(this).tooltip({title: "Increase image size"});
	    $(this).attr("data-original-title","Increase image size");
	}

	set_window_height();
	

    });
    // $('#page2_button_reduce').on('click',function(){
    // 	$('.page2_map').removeClass("col-10").addClass("col-4");
    // 	$('.page2_map_buttons').removeClass("col-2").addClass("col-8");
    // 	$('#page2_button_enlarge_text').text(" Enlarge");
    // 	$('#page2_button_reduce_text').html(" Reduce&nbsp;&nbsp;");
    // 	$('.selected_variables_vars').css("height","400px");
    // });
    

    $('.axes_ranges_question').on('click',function(e){
	$('#axes_ranges_modal').modal('show');
    });

    $('.axes_variables_question').on('click',function(e){
	$('#axes_variables_modal').modal('show');
    });

    $('.axes_full_range_question').on('click',function(e){
	$('#axes_full_range_modal').modal('show');
    });


    if (contaminants == true && project == "EMODnet Chemistry"){
	// $('.the_of').hide();
	// $('.num_variables_total').hide();
    }
    
    
    $('#extractor_eraser_modal').find('#extractor_eraser_apply_button').on('click',function(){
	//console.log("eraser");

	localStorage.removeItem(storage_name);
	localStorage.removeItem("auth_help_not_show_again")
	localStorage.removeItem("cookies_help_not_show_again")
	webodv_status[storage_name] = {};
	x_var = default_x_visualization-1;
	y_var = default_y_visualization-1;
	y_axis_reversed = [];
	x_axis_reversed = [];

	//init empty
	localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));

	//init treeview for EMODnet contaminants
	if (contaminants == true && project == "EMODnet Chemistry"){
	    treeview_emodnet_contaminants_connect();
	}

	
	//switch to page 1
	//$('#pager_large_1, #pager_small_1').trigger("click");
	$('.reset').trigger("click");
	//$('#treeview_button_reset_vars').trigger("click");
	//console.log("default_output_vars")
	//console.log(default_output_vars)
	treeview_default();
	treeview_mandatory();
	//uncheck outliers
	$('#outliers').prop("checked",false);

	//localStorage.clear(); 
	// $.each(localStorage, function(i,e){
	//     if (i.includes(project)) {
	// 	//console.log("contains "+project)
	// 	//console.log(i)
	// 	localStorage.removeItem(i);
	// 	webodv_status[storage_name] = {};
	// 	//console.log(e)
	// 	//console.log(typeof(e))
	// 	// var storage_data = JSON.parse(e);
	// 	// $.each(storage_data,function(f,g){
	// 	//     console.log(f + " = " + g)
	// 	// });
	//     }
	// });

	//set_window_height();
	
    });




    function check_var_reversed_state(){
	if (typeof(Xstatus["reversed_"+y_var.toString()]) != "undefined"){
	    y_axis_reversed[y_var.toString()] = Xstatus["reversed_"+y_var.toString()];
	    if (y_axis_reversed[y_var.toString()] == "T"){
		$("#reversed_axis_checkbox_y").prop("checked",true);
	    }
	} else {
	    y_axis_reversed[y_var.toString()] = "F";
	}
	if (typeof(Xstatus["reversed_"+x_var.toString()]) != "undefined"){
	    x_axis_reversed[x_var.toString()] = Xstatus["reversed_"+x_var.toString()];
	    if (x_axis_reversed[x_var.toString()] == "T"){
		$("#reversed_axis_checkbox_x").prop("checked",true);
	    }
	} else {
	    x_axis_reversed[x_var.toString()] = "F";
	}
    }

    
    //localStorage.removeItem(storage_name);

    treeview_mode_check();
    
    if (localStorage.getItem(storage_name) !== null){
	//console.log(localStorage.getItem(storage_name))
	webodv_status[storage_name] = JSON.parse(localStorage.getItem(storage_name));


	Xstatus = JSON.parse(localStorage.getItem(storage_name))
	//console.log(Xstatus)
	if (typeof(Xstatus.date1) != "undefined"){
	    dates[0] = Xstatus.date1;
	}
	if (typeof(Xstatus.date2) != "undefined"){
	    dates[1] = Xstatus.date2;
	}

	if (typeof(Xstatus.pointsize) != "undefined"){
	    pointsize = Number(Xstatus.pointsize);
	}

	if (typeof(Xstatus.ReqVarNum) != "undefined"){	    
	    if (Xstatus.ReqVarNum == 1){
		required_vars_text = Xstatus.ReqVarNum + " variable selected";
	    } else {
		required_vars_text = Xstatus.ReqVarNum + " variables selected";
	    }
	    $('#required_vars_button').text(required_vars_text);
	}
	if (typeof(Xstatus.viz_x_var) != "undefined"){
	    x_var = Number(Xstatus.viz_x_var);
	}
	if (typeof(Xstatus.viz_y_var) != "undefined"){
	    y_var = Number(Xstatus.viz_y_var);
	}
	var range_id = 'range_'+x_var.toString()+'_'+y_var.toString();
	//console.log("x_var="+x_var)
	//console.log("y_var="+y_var)
	//console.log(range_id)
	//console.log(Xstatus)
	//console.log('Xstatus[range_id]')
	//console.log(Xstatus[range_id])
	if (typeof(Xstatus[range_id]) != "undefined"){
	    webodv_status[storage_name][range_id] = Xstatus[range_id];
	    //console.log(webodv_status[storage_name][range_id])
	} else {
	    var range = {'x_range_from':-1e10, 'x_range_to':1e10, 'y_range_from':-1e10, 'y_range_to':1e10}
	    webodv_status[storage_name][range_id] = range;
	}
	//
	if (typeof(Xstatus.outliers) != "undefined"){
	   //console.log("Xstatus.outliers = "+Xstatus.outliers)
	    $("#outliers").prop("checked",Xstatus.outliers);
	}
	//console.log("Xstatus")
	//console.log(Xstatus["reversed_"+x_var.toString()])
	//console.log(typeof(Xstatus["reversed_"+x_var.toString()]))

	//
	//
	check_var_reversed_state();

	//call this function here to capture the click below
	//console.log("trigger treeview_mode_check");
	//treeview_mode_check();
	
	if (typeof(Xstatus.treeview_mode) != "undefined"){
	    //console.log("Xstatus.treeview_mode")
	   //console.log(Xstatus.treeview_mode)
	    if (Xstatus.treeview_mode == 0){
		$("#treeview_mode_or").trigger("click");
		treeview_mode = 0;
	    }
	    if (Xstatus.treeview_mode == 1){
		$("#treeview_mode_and").trigger("click");
		treeview_mode = 1;
	    }	    
	}
	

    }

   //console.log('webodv_status[storage_name][range_id]')
   //console.log(webodv_status[storage_name][range_id])

    //console.log(x_var)
    //console.log(y_var)

    //allow data-toggle on page 3
    var myDefaultWhiteList = $.fn.selectpicker.Constructor.DEFAULTS.whiteList;
    myDefaultWhiteList.span = ['data-toggle', 'data-placement', 'title'];
    

    $(".selectpicker_wrapper").children('.dropdown').css({"background-color": "#fafafa"});
    
    //console.log((pointsize))
    //console.log(typeof(pointsize))
    function setpointsize(){
	//set pointsize option selected
	var pointsize_option = $("#point_size").children('option');
	$.each(pointsize_option,function(i,e){
	    //console.log(i)
	    //console.log(typeof($(this).val()))
	    //console.log((pointsize))
	      //console.log($(this).val())
	    //console.log(typeof(pointsize))
	    if (Number($(this).val()) == Number(pointsize)){
		//console.log("set pointsize to "+pointsize)
		//$(this).attr("selected",true);
		//$("#point_size").next('button').attr('title',(pointsize.toString()));
		//var pointsizetext = $("#point_size").find('div.filter-option-inner-inner');
		//console.log(pointsizetext)
		//pointsizetext.text(pointsize.toString());
		$('#point_size').selectpicker('val',(pointsize.toString()));
		//console.log();
	    }
	});
    }
    //$('#point_size').on('loaded.bs.select',function(){
    setpointsize();
    //});

    //console.log("pointsize="+pointsize)
    //console.log(pointsize_option)

    //console.log("start1")
    //console.log(default_dates)

    


    // var doc_scroll = 0;
    // $(document).on('scroll',function(){
    // 	doc_scroll = $(this).scrollTop();
    // 	console.log(doc_scroll)
    // });


    
    //------------pager-------------------------------------------------//
    //init
    $("#pager_large_1,#pager_small_1").addClass('active');

    //---function
    var number_of_pages = 4; //5;
    activate_pager = function(){
	//console.log("pager_num="+pager_num)
	if (enable_pager == true && pager_num < (number_of_pages+2) ){
	    $(".data_preview_page").hide();
	    
	    $(".page-item").removeClass('active');
	    if (pager_num < 1){
		pager_num=1;
	    }
	    if (pager_num > number_of_pages){
		pager_num=number_of_pages;
	    }
	    $('#pager_large_'+pager_num.toString()+',#pager_small_'+pager_num.toString()).addClass('active');
	    //
	    //display page
	    $('.webodvextractor_page').hide();
	    //console.log("show page "+pager_num)
	    //console.log("show page "+typeof(pager_num))
	    $('.webodvextractor_page_'+pager_num.toString()).show();
	    //
	    //console.log("in activate pager")
	    set_window_height();
	    //

	    //
	    //console.log("pager_num= "+pager_num)
	    //hide treeview search if entering a page the first time
	    if (pager_num==2){
		$('#search_output_vars').hide();
	    }
	    if (pager_num==1){
		$('#search_output').hide();
	    }
	    //event
	    $(document).trigger('page',pager_num);
	}
    }

    //disable th focus on a click on a pager
    $('.page-link').on('focus', function(e){
	$(this).blur();
    });

    //prevent to scroll to top on a click on a pager link
    $('.page-link').on('click', function(e){
	e.preventDefault();
    });


    //data preview
    $(".data_preview").on("click",function(){
	show_image_loading('.img_col');
	pager_num_previous = pager_num;
	pager_num = 99;
	$('.webodvextractor_page').hide();
	$(".page-item").removeClass('active');
	$('.data_preview_page').show();
	//event
	$(document).trigger('page',pager_num);	
    });

    
    $(".data_preview_go_back").on("click",function(){
	//console.log("data_preview_go_back")
	//console.log(pager_num_previous)
	pager_num = pager_num_previous;
	$('#pager_large_'+pager_num+', #pager_small_'+pager_num).trigger("click");
	//$(document).trigger('page',pager_num);
    });
    
    //click on a pager button
    //extract number of clicked
    $(".webodv_pager").on("click", function() {
	pager_num = Number($(this).attr('id').substring(12,13));
	//console.log("click on pager")
	activate_pager();
    });
    //click on left
    $(".webodv_pager_left").on("click", function() {
	pager_num--;
	activate_pager();
    });
    //click on right
    $(".webodv_pager_right").on("click", function() {
	pager_num++;
	activate_pager();
    });
    //left right keyboard
    $(document).keydown(function( event ) {
	//console.log(event.which)
	//console.log("click left right")

	var disable_pager_now = false;
	
	if (enable_pager){
	    
	    //only if date selectors are not in focus
	    //and cruise search is not focussed
	    if ($('#date1').is(':focus')){
	    	disable_pager_now = true;
	    }
	    if ($('#date2').is(':focus')){
	    	disable_pager_now = true;
	    }
	    //
	    if ($('.range').is(':focus')){
	    	disable_pager_now = true;
	    }
	    //
	    if ($('.bs-searchbox').children('input').is(':focus')){
	    	disable_pager_now = true;
	    }
	    //console.log("cruise_focus")
	    //console.log(cruise_focus)

	    //treeview search
	    if ($('#search_input_vars').is(':focus')){
	    	disable_pager_now = true;
	    }

	    //console.log("focus")
	    //console.log($('#search_input').is(':focus'))
	    if ($('#search_input').is(':focus')){
	    	disable_pager_now = true;
	    }
	    
	    //console.log(typeof(date1_focus))
	    
	    if (disable_pager_now == false){
		if ( event.which == 37 ) {
		    event.preventDefault();
		    pager_num--;
		    activate_pager();
		}
		if ( event.which == 39 ) {
		    event.preventDefault();
		    pager_num++;
		    activate_pager();
		}
	    }

	}
    });
    //console.log("swipe")
    //mobile swipe
    // $(".swipe_on").swipe({
    // 	//Generic swipe handler for all directions
    // 	swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
    // 	    console.log("You swiped " + direction );
    // 	    console.log(typeof(direction))
    // 	    if (direction == "left"){
    // 		pager_num++;
    // 		activate_pager();
    // 	    }
    // 	    if (direction == "right"){
    // 		pager_num--;
    // 		activate_pager();
    // 	    }
    // 	}
    // });

    //------------pager-------------------------------------------------//



    
    //use this function for all websocket functionality
    //if a get_canvas is involved
    //this means it is triggered also before any modify_view, load_view etc
    //as long as a get_canvas is involved
    //the unblock is triggered in the image load function
    window.show_image_loading = function(loading_id_or_class){
	//.img_col
	// $(loading_id_or_class).block({
	//     message: '<h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1>',
	//     overlayCSS: { backgroundColor: '#fffff' },
	// });
	//
	//custom
	//console.log("append image loading")
	$(loading_id_or_class).prepend(loading_snippet);
	
	//disable pager during loading
	$('.page-link').addClass('pager-disabled');
	enable_pager = false;
	//enable on image load
    }

    window.hide_image_loading = function(loading_id_or_class){
    	//$(loading_id_or_class).unblock();
	//custom
	//console.log("remove image loading")
	$(".loading_snippet").remove();
	//enable pager during loading
	$('.page-link').removeClass('pager-disabled');
	enable_pager = true;
	//console.log("hide done")
    }


    //init
//    if (contaminants == true && project == "EMODnet Chemistry"){
	// $('.show_loading_here').show();
	// $('.output_treeview_col').hide();
 //   } else {
//	$('.show_loading_here').hide();
//	$('.output_treeview_col').show();
 //   }


    
    $('.webodv_pager_left').children('a').addClass('pager-arrow-disabled');

    $(document).on('page', function(e,pager_num){
	//console.log("page changed")
	//console.log(pager_num)
	$('.webodv_pager_left').children('a').removeClass('pager-arrow-disabled');
	$('.webodv_pager_right').children('a').removeClass('pager-arrow-disabled');
	//

	//if (pager_num != 3){
	if (pager_num != 99){
	    count_move_map_scatter = 0;
	    move_map_scatter = true;
	}

	//data_preview
	//if (pager_num == 3){
	if (pager_num == 99){
	   //console.log("first pager_num=3")
	   //console.log("-> get_data_availability_information")
	    var num_stations = Number($('.num_stations').text());
	    if (num_stations == 0){
		//$('.webodvextractor_page_3').hide();
		$('.data_preview_page').hide();		
		$('#zero_stations_modal').modal('show');
	    }
	    if (num_stations > 0){
		// show_image_loading();
		// var load_view_options = {"view":"$OneScatterWin$"};
		// send_wsODV_msg("load_view",load_view_options);

		//move map to top
		//
		//{"cmd":"modify_view","view_snippet":"<MapWindow> <Window> <Geometry x_length_bb='18.5'
		//x_offset_bb='1.5' y_length_bb='16.5' y_offset_bb='2.5'/ > </Window> </MapWindow>"}
		//
		//here I have added two modify views, that's the reason why I had problems
		//with the axes ranges
		//because here the default vars 0 and 1 are used with their full range
		//
		//do this only once
		
		// if (move_map_scatter){
		//     var view_snippet = '<MapWindow> <Window> <Geometry x_length_bb="8.5" x_offset_bb="1.5" y_length_bb="7.5" y_offset_bb="11.5"/ > </Window> </MapWindow>';
		//     //console.log(view_snippet)
		//     var option = {"view_snippet":view_snippet};
		//     send_wsODV_msg('modify_view',option);
		//     //
		//     var view_snippet =  '<DataWindow window_id="1"> <Window> <Geometry x_offset_bb="13" y_offset_bb="2" y_length_bb="17" x_length_bb="14.5"/> </Window> </DataWindow>';
		//     var option = {"view_snippet":view_snippet};
		//     send_wsODV_msg('modify_view',option);
		//     //
		//     move_map_scatter = false;
		// }
		
		//following is now under get_data_availability
		//e.g. the get_canvas
		//console.log("send1")
		send_wsODV_msg('get_data_availability_information');
	    }
	}
	if (pager_num == 1){
	    $('.webodv_pager_left').children('a').addClass('pager-arrow-disabled');
	    //show_image_loading();
	    //show_image_loading('.img_col');
	    var load_view_options = {"view":"$FullScreenMap$"};
	    send_wsODV_msg("load_view",load_view_options);

	    // var view_snippet = '<MapWindow> <Window> <Geometry x_length_bb="17" x_offset_bb="2" y_length_bb="17" y_offset_bb="40"/ > </Window> </MapWindow>';
	    // //console.log(view_snippet)
	    // var option = {"view_snippet":view_snippet};
	    // send_wsODV_msg('modify_view',option);



	    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	}
	if (pager_num == 2){
	    page2_visit = true;

	    //set_window_height();
	    
	    // $('.show_loading_here').show();
	    // $('.output_treeview_col').hide();

	    // console.log("baggi1")
	    // console.log(webodv_status[storage_name])
	    // Xstatus = webodv_status[storage_name];
	    // console.log(Xstatus.OutVar)
	    
	    // if (typeof(Xstatus.OutVar) == "undefined"){		    
	    // 	    treeview_default();
	    // }
	    
	    //
	    //show_image_loading('.img_col');
	    var load_view_options = {"view":"$FullScreenMap$"};
	    send_wsODV_msg("load_view",load_view_options);
	    //hide treeview and show if finally rendered
	    //some inits
	    //hide treeview and show if finally rendered
	    //show_image_loading();
	    // show_image_loading('.show_loading_here');
	    // $('.output_treeview_col').hide();

	    //get treeview_mode
	    treeview_mode = $('input[name="treeview_mode"]:checked').val();
	    webodv_status[storage_name]['treeview_mode'] = treeview_mode;
	    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	    
	    
	    
	    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	    send_wsODV_msg('get_canvas_window',get_canvas_window_options);

	    
	    var num_stations = Number($('.num_stations').text());
	    //do it in 2 or 3, because in 2 we have to set the treeview
	    //in 3 we have to adapt the axes
	    //thus if one jumps over step two we need it in 3
	    //
	    //do it only at download page
	    // if (num_stations == 0){
	    // 	$('.webodvextractor_page_2').hide();		
	    // 	$('#zero_stations_modal').modal('show');
	    // }
	    if (num_stations >= 0){
		//console.log("fire websocket get_data_availability_information to get potential available data")

		//reset required to get "potential data availability"
		//if (contaminants == true && project == "EMODnet Chemistry"){
		    var view_snippet = '<StationSelectionCriteria>' + '<RequiredVars var_ids="' + "" + '" use_or_logic="T"/>'  + '</StationSelectionCriteria>';
		    var required_vars_option = {"view_snippet":view_snippet};
		    send_wsODV_msg('modify_view',required_vars_option);
		//}
		//
		
		//setTimeout(function(){
		//    console.log("now send get_data_avail")
		send_wsODV_msg('get_data_availability_information');    
		//}, 8000);


	    }
	    //
	    //
	}
	//
	if (pager_num != 2){
	    //hide treeview and show if finally rendered
	    //show_image_loading();
	    if (contaminants == true && project == "EMODnet Chemistry"){
		//$('.show_loading_here').show();
		//$('.output_treeview_col').hide();
	    } else {
		//$('.show_loading_here').hide();
		//$('.output_treeview_col').show();
	    }
	    // show_image_loading('.show_loading_here');
	}

	if (pager_num == 3){
	    var num_stations = Number($('.num_stations').text());
	    if (num_stations == 0){
	    	$('.webodvextractor_page_3').hide();		
	    	$('#zero_stations_modal').modal('show');
	    }

	    //show_image_loading();
	    //show_image_loading('.img_col');
	    var load_view_options = {"view":"$FullScreenMap$"};
	    send_wsODV_msg("load_view",load_view_options);
	    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	}

	// if (pager_num == 4){
	//     //console.log("baggi")
	//    //console.log(localStorage.getItem("auth_help_not_show_again"))
	//    //console.log("username:"+username)
	//     //only go into it if not logged in
	//     if (username == ""){
	// 	if (localStorage.getItem("auth_help_not_show_again") === null){
	// 	    $('#auth_help_modal').modal('show');
	// 	    var auth_help_not_show_again = $('#auth_help_modal').find('#auth_help_not_show_again');
	// 	    auth_help_not_show_again.on("click",function(){
	// 		//console.log("click")
	// 		localStorage.setItem("auth_help_not_show_again",true);
	// 	    });
	// 	}
	//     }
	// }
	// if (pager_num == 5){
	if (pager_num == 4){
	    $('.webodv_pager_right').children('a').addClass('pager-arrow-disabled');
	}
    });

    
    //------------station info
    // $(".station_info").on("click",function() {
    // 	//console.log(get_dock_metavar_labels_list)
    // 	//console.log(current_cruise)
    // 	//console.log($(this))
    // 	$(".get_dock_metavar_labels_list").html(get_dock_metavar_labels_list).toggle();
    // 	if ($(".get_dock_metavar_labels_list").css("display") == "none") {
    // 	    //$(this).text('Cruise: '+ current_cruise);
    // 	    $(this).html('Cruise: '+ current_cruise + '<span class="float-right"><i class="fa fa-caret-down"></i></span>');
    // 	} else {
    // 	    //$(this).text('Cruise: '+ current_cruise);
    // 	    $(this).html('Cruise: '+ current_cruise + '<span class="float-right"><i class="fa fa-caret-up"></i></span>');
    // 	}
    // });
    //------------station info
    function station_info(){
	//console.log(get_dock_metavar_labels_list)
	//console.log(current_cruise)
	//console.log($(this))
	//$(".get_dock_metavar_labels_list").html(get_dock_metavar_labels_list).toggle();
    }


    
    //close on left click
    // $('.webodvextractor_page_1').on("click",function(e){
    // 	//console.log("click");
    // 	//get clicked
    // 	//console.log(e.target)
    // 	//station_info
    // 	var clicked_element = $(e.target);
    // 	if (clicked_element.hasClass('station_info') == false){
    // 	    //hasClass(':not(.closedTab)')
    // 	    $(".get_dock_metavar_labels_list").hide();
    // 	    //$('#station_info').html('Cruise: '+ current_cruise  + ' &nbsp; &nbsp; <i class="fa fa-caret-down"></i>');
    // 	    //$('.station_info').children(".text-left").text('Cruise: '+ current_cruise);
    // 	    $('.station_info').html('Cruise: '+ current_cruise + '<span class="float-right"><i class="fa fa-caret-down"></i></span>');
    // 	}
    // });

    

    // $("#sample_info").on("click",function() {
    // 	//console.log(get_dock_metavar_labels_list)
    // 	$("#get_dock_datavar_labels_list").html(get_dock_datavar_labels_list).toggle();
    // 	if ($("#get_dock_datavar_labels_list").css("display") == "none") {
    // 	    $(this).html('Sample Info &nbsp; &nbsp; <i class="fa fa-caret-down"></i>');
    // 	} else {
    // 	    $(this).html('Sample Info &nbsp; &nbsp; <i class="fa fa-caret-up"></i>');
    // 	}
    // });
    

    window.onbeforeunload = function() {
	//show_image_loading();
	show_image_loading('.img_col');
	send_wsODV_msg('logout_request');
    }





    $('.download_image').on('click',function(){
	$.blockUI({
	    message: '<h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1>',
	});

	//transparency test WMS
	// var snippet = '<GeographicMap><AutoMapLayerSettings ocean_bathymetry="F" coastline_fill="F" coastlines="F"/></GeographicMap>';
	// snippet = snippet + '<MapWindow background_color="-1"><Axis color="-1" create_labels="F" draw_grid="F"/></MapWindow>';

	// send_wsODV_msg('modify_view',{"view_snippet":snippet});


	
	//console.log("download button")
	//console.log("pager_num="+pager_num)
	//send_wsODV_msg('get_image',download_image_option);
	download_image_name = Math.random().toString(36).substr(2, 8) + '.png';
	var download_image_option = {"win_id":-1, "name":download_image_name};
	send_wsODV_msg('export_graphics',download_image_option);

    });

    //-----------cruise---------------------//

    //select all on startup
    // $('#select_cruise').on('loaded.bs.select,refreshed.bs.select,shown.bs.select', function(){
    // 	console.log("selectAll")
    // 	$(this).selectpicker('selectAll');
    // });

    //align text left
    //$('.filter-option-inner-inner').addClass("text-left");

    //add placeholder to Cruises search
    //and change bg color
    var select_cruise_dropdown = {};
    $('.selectpicker').on('rendered.bs.select',function(){
	$(".bs-searchbox").children("input").attr("placeholder","Search");
	//delete tooltip of button from variable selection if EMODnet contaminants
	select_cruise_dropdown = $('#select_cruise').siblings('div');
	//console.log("select_cruise_dropdown")
	//console.log(select_cruise_dropdown)
    });


    
    //on change
    $('#select_cruise').on('changed.bs.select', function(){
	var cruises = [-999];
	$("#select_cruise option:selected").each(function(i,e) {
	    cruises[i] = $(this).val();
	});
	//console.log(cruises.join(" || "))
	//show_image_loading();
	//show_image_loading('.img_col');
	//
	webodv_status[storage_name]['cruises'] = cruises;
	localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	//
	if (cruises == ""){
	    var view_snippet = '<StationSelectionCriteria> <Names cruise="'+ '*' +'"/> </StationSelectionCriteria>';
	} else {
	    var view_snippet = '<StationSelectionCriteria> <Names cruise="'+ cruises.join(" || ") +'"/> </StationSelectionCriteria>';
	}
	//console.log(view_snippet)
	//console.log(view_snippet)
	var cruise_option = {"view_snippet":view_snippet};
	send_wsODV_msg('modify_view',cruise_option);

	var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	send_wsODV_msg('get_canvas_window',get_canvas_window_options);


    });

    $('#select_cruise').on('hidden.bs.select', function(){
	//console.log("hidden")
	send_wsODV_msg('get_data_availability_information');
    });
    

    //-----------cruise---------------------//



    //------------zoom----------------------//

    // $(document).on("cropper_destroy",function(){
    // 	webodv_canvas_img.cropper.destroy();
    // });


    $(".zoom_in").on("click", function(){
	var that_text = $(this).val();
	if (that_text == "Zoom mode"){
	    $(this).val("Apply");
	    $(".zoom_out").val("Cancel");
	    //disable full range and global
	    $('.zoom_full_range,.zoom_global').prop("disabled",true).css("cursor",'not-allowed');

	    //ctx.clearRect(0, 0, htmlcanvas.width, htmlcanvas.height);
	    Xcropper();
	}
	if (that_text == "Apply"){
	    $(this).val("Zoom mode");
	    $(".zoom_out").val("Zoom out");
	    //send zoom
	    //show_image_loading();
	    //show_image_loading('.img_col');
	    zoom_options = {"coords":[k1,k2,m1,m2], "pointsize":pointsize};
	    send_wsODV_msg("map_domain",zoom_options);
	    //send_wsODV_msg('get_canvas_window');

	    //check here data_avail to update the selected vars list
	    //instead of get_canvas_window
	    //!!!!!!!!!!!!!!!!
	    // checking for data_avail takes long
	    // thus it has to be fired after the new image has been loaded
	    //
	    if (switch_get_canvas_get_data){
		var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
		send_wsODV_msg('get_canvas_window',get_canvas_window_options);

		//send_wsODV_msg('get_data_availability_information');
	    } else{
		var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
		send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	    }
	    $('.zoom_full_range,.zoom_global').prop("disabled",false).css("cursor",'pointer');

	    //wsODV_map_domain(k1,k2,m1,m2);
	    // lon_min = k1;
	    // lon_max = k2;
	    // lat_min = m1;
	    // lat_max = m2;
	    // wsODV_select_current_sample(station_id,sample_id)
	    $(document).trigger("cropper_destroy");
	} 
    });

    $(".zoom_out").on("click", function(){
    	var that_text = $(this).val();
    	if (that_text == "Cancel"){
    	    $(this).val("Zoom out");
    	    $(".zoom_in").val("Zoom mode");
	    $('.zoom_full_range,.zoom_global').prop("disabled",false).css("cursor",'pointer');
    	    $(document).trigger("cropper_destroy");
    	} else {
	   //console.log("lon1="+k1)
	   //console.log("lon2="+k2)
	   //console.log("lat1="+m1)
	   //console.log("lat2="+m2)
	   //console.log("")
	    //convert k from 0 360 to -180 180
	    if (k1>180){
	    	k1 = k1-360
	    }
	    if (k2>180){
	    	k2 = k2-360
	    }


	    k1 = k1-10; //default_koordinates[0];
	    k2 = k2+10; //default_koordinates[1];
	    m1 = m1+10; //default_koordinates[2];
	    m2 = m2-10; //default_koordinates[3];
	   //console.log("lon1="+k1)
	   //console.log("lon2="+k2)
	   //console.log("lat1="+m1)
	   //console.log("lat2="+m2)
	   //console.log("")
	    // if (k1 <= 0){
	    // 	k1 = 360+k1;
	    // }
	    // if (k2 >= 360){
	    // 	k2 = 0+k2;
	    // }
	    // if (m2 <= -90){
	    // 	m2 = -90;
	    // }
	    // if (m1 >= 90){
	    // 	m1 = 90;
	    // }	    
	    if (k1 <= -180){
	    	k1 = -180;
	    }
	    if (k2 >= 180){
	    	k2 = 180;
	    }
	    if (m2 <= -90){
	    	m2 = -90;
	    }
	    if (m1 >= 90){
	    	m1 = 90;
	    }	    
	    //show_image_loading('.img_col');
    	    zoom_options = {"coords":[k1,k2,m1,m2], "pointsize":pointsize};
	    send_wsODV_msg("map_domain",zoom_options);
	    //if (switch_get_canvas_get_data){
	    //send_wsODV_msg('get_data_availability_information');
	    //} else{		 
		var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
		send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	    //}
        }
    });

    $(".zoom_full_range").on("click", function(){
	k1 = -1e10; //default_koordinates[0];
	k2 = 1e10; //default_koordinates[1];
	m1 = -1e10; //default_koordinates[2];
	m2 = 1e10; //default_koordinates[3];
	if (branch == "geotraces"){
	    k1 = default_koordinates[0];
	    k2 = default_koordinates[1];
	    m1 = default_koordinates[2];
	    m2 = default_koordinates[3];	    
	}

	//show_image_loading('.img_col');
    	zoom_options = {"coords":[k1,k2,m1,m2], "pointsize":pointsize};
	send_wsODV_msg("map_domain",zoom_options);
	//send_wsODV_msg('get_canvas_window');

	//if (switch_get_canvas_get_data){
	//    send_wsODV_msg('get_data_availability_information');
	//} else{
	    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	//}
    });
    $(".zoom_global").on("click", function(){
	k1 = -180;
	k2 = 180;
	m1 = -90;
	m2 = 90;
	//show_image_loading('.img_col');
    	zoom_options = {"coords":[k1,k2,m1,m2], "pointsize":pointsize};
	send_wsODV_msg("map_domain",zoom_options);
	//send_wsODV_msg('get_canvas_window');
	//if (switch_get_canvas_get_data){
	//	send_wsODV_msg('get_data_availability_information');
	  //  } else{
		var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
		send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	//    }
    });
    


    //------------zoom----------------------//
    

    //hide and show the elements ???????
    // $('#Cruises').on('mouseenter',function(){
    // 	console.log("mouseenter")
    // 	$('.cruises').show();
    // });
    // $('#Cruises').on('mouseout',function(){
    // 	console.log("mouseout")
    // 	$('.cruises').hide();
    // });


    // $('.webodvextractor_page_1 .num_stations').on("change", function(){
    // 	console.log("span change")
    // // console.log("pager_num="+pager_num)
    // 	// //remove list of vars
    // 	// //$('.h4_selected_variables_vars').children('div').remove();
    // 	// send_wsODV_msg('get_data_availability_information');
    // 	//$(treeview_vars_id).trigger("CheckUncheckDone");
    // });
    

    //------------date select-------------------------------------------------//

    //$('#datepicker').datepicker({ showOnFocus: true, showRightIcon: false });

    $('#date_enable').on("click", function(){
	$(this).hide();
	$('#date_ban').show();
	//$('.show_time_range').show();
	$('.show_time_range').css('opacity',1).find('input,button').attr('disabled',false).css('cursor','pointer');
	$('.show_time_range').find('i').show();
	setdate();
    });
    $('#date_ban').on("click", function(){
	$(this).hide();	
	$('#date_enable').show();
	//$('.show_time_range').hide();
	$('.show_time_range').css('opacity',0.3).find('input,button').attr('disabled',true).css('cursor','not-allowed');
	$('.show_time_range').find('i').hide();
	date_options = {"date1":'01/01/-99999', "date2":'12/31/99999'};
	send_wsODV_msg("set_date",date_options);
	var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	send_wsODV_msg('get_canvas_window',get_canvas_window_options);

    });

    
    $('#date1').datepicker({
    	uiLibrary: 'bootstrap4',
    	showOnFocus: false,
    	showRightIcon: true,
	value: dates[0],
	minDate: default_dates[0],
	maxDate: default_dates[1],
	close: function (e) {
	    //setdate();
	    //send_wsODV_msg('get_canvas_window');
        },
	change: function (e) {
	    var new_date = $(this).datepicker().value();
	    var date_test = check_date(new_date);
	    //console.log("date_test= "+date_test)

	    var date_test_min_max = check_date_min_max(new_date);
	    
	    //send websocket if true otherwise set back
	    if (date_test && date_test_min_max){
		setdate();
	    } else {
		set_datepicker_val('1',dates[0])
	    }
	    //send_wsODV_msg('get_canvas_window');
	    //console.log("change")
        }
    });
    $('#date2').datepicker({
    	uiLibrary: 'bootstrap4',
    	showOnFocus: false,
    	showRightIcon: true,
	value: dates[1],
	minDate: default_dates[0],
	maxDate: default_dates[1],
	close: function (e) {
	    //setdate();
	    //send_wsODV_msg('get_canvas_window');
        },
	change: function (e) {
	    var new_date = $(this).datepicker().value();
	    var date_test = check_date(new_date);
	    //console.log("date_test= "+date_test)
	    var date_test_min_max = check_date_min_max(new_date);
	    
	    if (date_test && date_test_min_max){
		setdate();
	    } else {
		set_datepicker_val('2',dates[1])
	    }
	    //send_wsODV_msg('get_canvas_window');
        }
    });

    //also fire on hide, i.e. if a date has been set manually in the form

    check_date = function(datestring){
	return moment(datestring, "MM/DD/YYYY", true).isValid();
    }

    check_date_min_max = function(new_date){
	var d_min = new Date(default_dates[0]);
	var d_max = new Date(default_dates[1]);
	var d_new = new Date(new_date);

	var check_min_max = true;
	//if new value is out of range
	if (d_new.getTime() < d_min.getTime()){
	    check_min_max = false;
	}
	if (d_new.getTime() > d_max.getTime()){
	    check_min_max = false;
	}
	return check_min_max;
    }

    
    set_datepicker_val = function(num,val){
	//num is a string
	var $Xdatepicker = $('#date'+ num).datepicker();
	$Xdatepicker.value(val);
    }
    
    setdate = function(){
	date1 = $('#date1').datepicker().value();
	date2 = $('#date2').datepicker().value();

	// console.log("set_date")
	// console.log(date1)
	// console.log(date2)
	
	var date_options = {"date1":date1, "date2":date2};
	//show_image_loading('.img_col');
	send_wsODV_msg("set_date",date_options);
	//send_wsODV_msg('get_canvas_window');

	//send_wsODV_msg('get_data_availability_information');

	//if (switch_get_canvas_get_data){
	 //   send_wsODV_msg('get_data_availability_information');
	//} else{
	    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	//}
	
	//console.log(date1)
	// var view_snippet = '<StationSelectionCriteria>' +  '<Period first_day="' + date1[1] + '" last_year="' + date2[2] + '" first_month="' + date1[0] + '" last_month="' + date2[0] + '" last_day="' + date2[1] + '" first_year="' + date1[2] + '" first_hour="' + "0" + '" last_hour="' + "24" + '" first_minute="' + "1" + '" last_minute="' + "60" +'"/>'   + '</StationSelectionCriteria>';
	// var date_option = {"view_snippet":view_snippet};
	// send_wsODV_msg('modify_view',date_option);
    }



    //required_vars_button
    $('#required_vars_button').on('click',function(){
	//open modal
	$('#required_vars_modal').modal('show');
    });


    $('#required_variables_help').on('click',function(){
	$('#required_vars_help_modal').modal('show');
    });
    $('#date_help').on('click',function(){
	$('#date_help_modal').modal('show');
    });

    $('.station_info_help').on('click',function(){
	$('#station_info_help_modal').modal('show');
    });
    
    //$('#exit_button').on('click',function(){
	//window.location = dataset_link;
    //});


    $('.reset').on('click',function(){
	//	
	$('#required_vars_button').text('0 variables selected');
	reset_clicked = true;

	$('#select_cruise').selectpicker('selectAll');
	webodv_status[storage_name]['cruises'] = [];


	
	//required
	webodv_status[storage_name]['ReqVarNum'] = 0;
	webodv_status[storage_name]['ReqVarList'] = "";
	webodv_status[storage_name]['ReqVarEx'] = "";
	webodv_status[storage_name]['pointsize'] = point_size;
	pointsize = Number(point_size);
	//console.log("reset_clicked set pointsize")
	//console.log(point_size)
	//console.log(pointsize)
	localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	setpointsize();
	
	var view_snippet = '<StationSelectionCriteria>' + '<RequiredVarExpression expr=""/>'  + '</StationSelectionCriteria>';
	var required_vars_option = {"view_snippet":view_snippet};
	send_wsODV_msg('modify_view',required_vars_option);

	k1 = default_koordinates[0]; //-1e10;
	k2 = default_koordinates[1]; //1e10;
	m1 = default_koordinates[2]; //-1e10;
	m2 = default_koordinates[3]; //1e10;

	
	//zoom map
	zoom_options = {"coords":[default_koordinates[0],default_koordinates[1],default_koordinates[2],default_koordinates[3]], "pointsize":point_size};
	send_wsODV_msg("map_domain",zoom_options);
	//set date
	date_options = {"date1":default_dates[0], "date2":default_dates[1]};
	set_datepicker_val('2',default_dates[1]);
	set_datepicker_val('1',default_dates[0]);

	//console.log("reset")
	//console.log(default_dates)
	//send_wsODV_msg("set_date",date_options);
	//canvas
	//send_wsODV_msg('get_canvas_window');
    });




    //$('#treeview_button_reset_vars').on('click',function(){
	//$("#treeview_vars").hummingbird("uncheckAll");
	//treeview_default();
	//save
        //webodv_status[storage_name]['OutVar'] = [];
	//localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	//treeview_mandatory();
    //});
    // $('#treeview_button_collapse_vars').on('click',function(){
    // 	$("#treeview_vars").hummingbird("collapseAll");
    // 	$("#treeview_vars").hummingbird("expandNode",{attr:"data-id",name: "0",expandParents:false});
    // });



    //point size
    $('#point_size').on('changed.bs.select', function(){
	pointsize = $("#point_size option:selected").text();
	//console.log("pointsize="+pointsize);
	//
	zoom_options = {"coords":[k1,k2,m1,m2], "pointsize":pointsize};
	send_wsODV_msg("map_domain",zoom_options);
	//show_image_loading('.img_col');
	//send_wsODV_msg('get_canvas_window');

	var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	send_wsODV_msg('get_canvas_window',get_canvas_window_options);

	//
    });
    //point size

    //$('#set_x_var')
    //visualization
    // $('.dropdown').on("click", function(e){
    // 	console.log("dropdown:"+doc_scroll)
    // });



    //catch a click or change on page 3
    // $('#set_x_var, #set_y_var').on('changed.bs.select', function(){
    // 	console.log("var changed 1")
    // });

    $('.treeview_logic_help').on('click',function(e){
	$('#treeview_logic_help_modal').modal('show');
    });


    $('#AND_mode_not_show_again').on("click",function(){
	localStorage.setItem('AND_mode_not_show_again',true);
    });
    
    function treeview_mode_check(){
	$('input[name="treeview_mode"]').on("click",function(){
	    treeview_mode = $(this).val();
	    // console.log("treeview_mode")
	    // console.log(treeview_mode)
	    // console.log(localStorage.getItem('AND_mode_not_show_again'))
	    // console.log(localStorage.getItem('AND_mode_not_show_again') === null)
	    // console.log(localStorage.getItem('AND_mode_not_show_again') !== null)
	    if (localStorage.getItem('AND_mode_not_show_again') === null){
		//console.log("is null")
		if (treeview_mode == 1 && pager_num == 2){
		    $('#treeview_AND_help_modal').modal('show');
		}
	    }

	    webodv_status[storage_name]['treeview_mode'] = treeview_mode;
	    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	    
	    $(".treeview_mode_at_selected_variables").text($(this).next("label").text());

	    //console.log("102 trigger on init")
	    $(treeview_vars_id).trigger("CheckUncheckDone");
	});
    }

    
    
    $('#set_x_var, #set_y_var').on('changed.bs.select', function(){
	//console.log("var changed 2")
	//console.log("x or y axis triggered")
	//strings


	a_variable_has_changed = $(this).attr("id").substring(4, 5);
	//console.log(a_variable_has_changed)

	var x_var_str = $("#set_x_var option:selected").val();
	var y_var_str = $("#set_y_var option:selected").val();

	x_var = Number(x_var_str);
	y_var = Number(y_var_str);
	//x_var--;
	//y_var--;
	

	if (y_var == depth_var_num){
	   //console.log("reverse y-axis")
	    //var y_axis_reversed = "T";
	    //flip inputs
	    //console.log("reversed")
	    //$('#y_minimum').removeClass('order-1').addClass('order-2');//.text('maximum:');
	    // $('#y_minimum_text').text('bottom:');
	    //$('#y_maximum').removeClass('order-2').addClass('order-1');//.text('minimum:');
	    // $('#y_maximum_text').text('top:');
	} else {
	    //var y_axis_reversed = "F";
	    //flip inputs
	    //console.log("not reversed")
	    //$('#y_minimum').removeClass('order-2').addClass('order-1');
	    // $('#y_minimum_text').text('bottom:');
	    //$('#y_maximum').removeClass('order-1').addClass('order-2');
	    // $('#y_maximum_text').text('top:');			
	}

	webodv_status[storage_name]['viz_x_var'] = x_var;
	webodv_status[storage_name]['viz_y_var'] = y_var;
	localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));

	
	
	//
	//console.log("x_axis_reversed")
	//console.log(x_axis_reversed[x_var.toString()])


	//console.log("change vars")
	//console.log("x-axis="+x_axis_reversed)
	//console.log("y-axis="+y_axis_reversed)

	//check if reverse state exist
	//check_var_reversed_state();

	
	// if (typeof(x_axis_reversed[x_var.toString()]) == "undefined"){
	//     x_axis_reversed[x_var.toString()] = "F"
	// } 

	// if (typeof(y_axis_reversed[y_var.toString()]) == "undefined"){
	//     y_axis_reversed[y_var.toString()] = "F"
	// } 
	
	
	if (viz_var_trigger){
	    //console.log("new scatter plot at viz_var_trigger")
	   //console.log("set_x_var,set_y_var make plot")
	    //console.log(y_axis_reversed)
	    var view_snippet = '<DataWindow window_id="1"> <AxisVariables> <xAxis var_id="' + x_var + '" is_reversed="' + x_axis_reversed[x_var.toString()] + '"/> <yAxis var_id="' + y_var + '" is_reversed="' + y_axis_reversed[y_var.toString()] + '"/> </AxisVariables> </DataWindow>';

	    //
	    var range_id = 'range_'+x_var.toString()+'_'+y_var.toString();
	    var current_range = webodv_status[storage_name][range_id]
	    //
	    if (typeof(current_range) == "undefined" ){
		var x_from = -1e10;
		var x_to = 1e10;
		var y_from = -1e10;
		var y_to = 1e10;
	    } else {
		var x_from = current_range.x_range_from;
		var x_to = current_range.x_range_to;
		var y_from = current_range.y_range_from;
		var y_to = current_range.y_range_to;
	    }
	    //

	    //set state of check boxes
	    // if (x_from>x_to){
	    // 	//console.log("xxxx")
	    // 	$("#reversed_axis_checkbox_x").prop("checked",true);
	    // } else {
	    // 	$("#reversed_axis_checkbox_x").prop("checked",false);
	    // }
	    // //
	    // if (y_from>y_to){
	    // 	$("#reversed_axis_checkbox_y").prop("checked",true);
	    // } else {
	    // 	$("#reversed_axis_checkbox_y").prop("checked",false);
	    // }



	    //
	    view_snippet = view_snippet + '<DataWindow window_id="1"> <Window> <Geometry x_left="'+ x_from  +'" x_right="'+ x_to  +'" /> </Window> </DataWindow>' +'<DataWindow window_id="1"> <Window> <Geometry y_bottom="'+ y_from  +'" y_top="'+ y_to  +'" /> </Window> </DataWindow>';
	    //

	    //console.log(view_snippet)
	    var set_x_var_option = {"view_snippet":view_snippet};
	    //show_image_loading('.img_col');
	    send_wsODV_msg('modify_view',set_x_var_option);
	    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	    //
	}


    });
    

    
    $("#reversed_axis_checkbox_y").on("click",function(){
	var reversed_state = $(this).prop("checked");
	//console.log(reversed_state)
	if (reversed_state){
	    y_axis_reversed[y_var] = "T";
	    //console.log("y_axis_reversed = T")
	} else {
	    y_axis_reversed[y_var] = "F";
	    //console.log("y_axis_reversed = F")
	}
	$(document).trigger("click_on_reversed_box");
    });
    $("#reversed_axis_checkbox_x").on("click",function(){
	var reversed_state = $(this).prop("checked");
	//console.log(reversed_state)
	if (reversed_state){
	    x_axis_reversed[x_var.toString()] = "T";
	    //console.log("x_axis_reversed = T")
	} else {
	    x_axis_reversed[x_var.toString()] = "F";
	    //console.log("x_axis_reversed = F")
	}
	$(document).trigger("click_on_reversed_box");
    });


    

    //set window range
    //{"cmd":"modify_view","view_snippet":"<DataWindow window_id='1'> <Window> <Geometry x_left='9'
//x_right='29' /> </Window> </DataWindow>"}

    $(document).on("click_on_reversed_box",function(){
	//make_scatter_plot();
	//console.log("click_on_reversed_box")
	//trigger a change event
	//var x_range_from_val = $("#x_range_from").val();
	//$("#x_range_from").trigger("change");
	//console.log("trigger")

	show_image_loading('.img_col');
	
	var view_snippet = '<DataWindow window_id="1"> <AxisVariables> <xAxis var_id="' + x_var + '" is_reversed="' + x_axis_reversed[x_var.toString()] + '"/> <yAxis var_id="' + y_var + '" is_reversed="' + y_axis_reversed[y_var.toString()] + '"/> </AxisVariables> </DataWindow>';
	//console.log(view_snippet)
	var range_option = {"view_snippet":view_snippet};
	send_wsODV_msg('modify_view',range_option);

	var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	send_wsODV_msg('get_canvas_window',get_canvas_window_options);

	webodv_status[storage_name]["reversed_"+y_var.toString()] = y_axis_reversed[y_var.toString()];
	webodv_status[storage_name]["reversed_"+x_var.toString()] = x_axis_reversed[x_var.toString()];
	localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));


    });

    $('.range').on('change',function(){
	//console.log("new scatter plot at .range")
	//console.log("x_range")
	var xyrange_str = $(this).val();
	var this_range_id = $(this).attr('id');
	//console.log("xyrange")
	//console.log(typeof(xyrange_str))
	//console.log(xyrange_str)

	var trim_possible = !xyrange_str.trim();
	//console.log("trim_possible")
	//console.log(trim_possible)
	
	var xyrange = Number(xyrange_str);
	//console.log(this_range_id)
	var range_id = 'range_'+x_var.toString()+'_'+y_var.toString();
	var current_range = webodv_status[storage_name][range_id]
	if ( Number.isInteger(xyrange*1000) && trim_possible == false){
	   //console.log("integer")
	    //update webodv_status and localStorage
	    current_range[this_range_id] = xyrange;
	    //
	    var x_from = current_range.x_range_from;
	    var x_to = current_range.x_range_to;
	    var y_from = current_range.y_range_from;
	    var y_to = current_range.y_range_to;
	   //console.log(current_range)
	    //update plot
	    // if (y_var == 0){
	    // 	var y_from = current_range.y_range_to;
	    // 	var y_to = current_range.y_range_from;

	    // }


	    show_image_loading('.img_col');
	    
	    //var y_axis_reversed = "F";
	    // if (y_var == depth_var_num){
	    // 	y_axis_reversed = "T";
	    // }

	    //console.log("x-axis="+x_axis_reversed)
	    //console.log("y-axis="+y_axis_reversed)

	
	    
	    // var view_snippet = '<DataWindow window_id="1"> <AxisVariables> <xAxis var_id="' + x_var + '"/> <yAxis var_id="' + y_var + '" is_reversed="' + y_axis_reversed + '"/> </AxisVariables> </DataWindow>';
	    //console.log(view_snippet)
	    // var range_option = {"view_snippet":view_snippet};
	    // send_wsODV_msg('modify_view',range_option);
	    
	    //view_snippet = '<DataWindow window_id="1"> <Window> <Geometry x_left="'+ x_from  +'" x_right="'+ x_to  +'" /> </Window> </DataWindow>' +'<DataWindow window_id="1"> <Window> <Geometry y_bottom="'+ y_from  +'" y_top="'+ y_to  +'" /> </Window> </DataWindow>';

	    
	    view_snippet = '<DataWindow window_id="1"> <Window> <Geometry x_left="'+ x_from  +'" x_right="'+ x_to  +'" y_bottom="'+ y_from  +'" y_top="'+ y_to  +'" /> </Window> </DataWindow>';
	   //console.log(view_snippet)
	    var range_option = {"view_snippet":view_snippet};
	    send_wsODV_msg('modify_view',range_option);
	    

	    
	    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	} else {
	    //console.log("not integer")
	    //restore
	    $(this).val(current_range[this_range_id]);
	    //$(this).val('NaN');
	}
    });

    $('#outliers').on('click',function(){
	//console.log("checkbox is: "+$(this).is(":checked"))
	//console.log("new scatter plot at #outliers")
	show_image_loading('.img_col');
	if ($(this).is(":checked")){
	    var set_sample_filter_option = {"option":"set_sample_filter","win_id":1,"type":"no_outliers"};
	    webodv_status[storage_name]['outliers'] = true;
	} else {
	    var set_sample_filter_option = {"option":"set_sample_filter","win_id":1,"type":"all_samples"};
	    webodv_status[storage_name]['outliers'] = false;
	}
	localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	send_wsODV_msg('execute_option',set_sample_filter_option);
	var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	send_wsODV_msg('get_canvas_window',get_canvas_window_options);	
    });
    
    //full range
    $('#reset_p3').on('click',function(){
	//console.log("new scatter plot at reset_p3")
	var view_snippet = '<DataWindow window_id="1"> <Window> <Geometry x_left="-1e10" x_right="1e10" /> </Window> </DataWindow>' +
	    '<DataWindow window_id="1"> <Window> <Geometry y_bottom="-1e10" y_top="1e10" /> </Window> </DataWindow>';
	var range_option = {"view_snippet":view_snippet};
	show_image_loading('.img_col');
	send_wsODV_msg('modify_view',range_option);
	var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
	send_wsODV_msg('get_canvas_window',get_canvas_window_options);
    });




    //on page 3 check if many samples will be used in the scatter plot
    //and warn
    	    $('#scatterplot_yes_button').on('click', function(){
		scatterplot_yes_button_clicked = true;
		//console.log("scatterplot yes button")
		$('#scatterplot_modal').modal("hide");
		//unbind the click on the button		
		//$('#scatterplot_yes_button').unbind( "click" );
		//continue
		get_data_availability_information_continue();
	    });


	    
	    //if the modal is hidden
	    $('#scatterplot_modal').on('hidden.bs.modal',function(){
		//continue
		//console.log("scatterplot_modal hidden")
		if (scatterplot_yes_button_clicked == false){
		    //$('.img_col').unblock();
		    hide_image_loading('.img_col');
		    //enable pager during loading
		    //$('.page-link').removeClass('pager-disabled');
		    //enable_pager = true;
		    $('#pager_large_1, #pager_small_1').trigger("click");
		}
		scatterplot_yes_button_clicked = false;
	    });

    
    function get_data_availability_information_continue(){

	//console.log("get_data_availability_information_continue")
	//debugger;
	//console.log("send2")
	//console.log(pager_num)
	
	if (pager_num == 99){
	    //from line 482
	    //console.log("send3")
	    //show_image_loading('.img_col');
	    var load_view_options = {"view":"$OneScatterWin$"};
	    send_wsODV_msg("load_view",load_view_options);

	    if (move_map_scatter){
		var view_snippet = '<MapWindow> <Window> <Geometry x_length_bb="8.5" x_offset_bb="1.5" y_length_bb="7.5" y_offset_bb="11.5"/ > </Window> </MapWindow>';
		//console.log(view_snippet)
		var option = {"view_snippet":view_snippet};
		send_wsODV_msg('modify_view',option);
		//
		var view_snippet =  '<DataWindow window_id="1"> <Window> <Geometry x_offset_bb="13" y_offset_bb="2" y_length_bb="17" x_length_bb="14.5"/> </Window> </DataWindow>';
		var option = {"view_snippet":view_snippet};
		send_wsODV_msg('modify_view',option);
		//
		move_map_scatter = false;
	    }
	}

	    //go through only once
	if (treeview_emodnet_contaminants_connect_go){
	    treeview_emodnet_contaminants_connect();
	}
	//console.log("treeview_data_available start")
	treeview_data_available();
	//
	//init_outvar_treeview();
	//
	//console.log("treeview_data_available end")

	//set now height of selected vars right bg
	//console.log("init")
	set_window_height()
	//

	//if no vars have been selected in step 2, show all for visualisation
	//if some vars have been selected then show only those
	
	//Attention, in GEOTRACES we have these combined variables 12|46|51|61 =  SALINITY_D_CONC
	var anz = "0";
	var ex = [];
	var_x_dropdown = "";
	var OutVar_temp = [];
	//if (use_all_vars == false){
	    //console.log(jsonData.station_set)
	    //console.log('p01_list["COREDIST"]')
	    //console.log(p01_list["COREDIST"])
	    //console.log("build scatter plot vars")
	    //console.log('webodv_status[storage_name][OutVar]')
	    //console.log(webodv_status[storage_name]['OutVar'])
	    //add the primary variable by default
	    //console.log(p01_list)
	var scatter_plot_vars = [];
	if (typeof(webodv_status[storage_name]['OutVar']) != "undefined"){
	    scatter_plot_vars = webodv_status[storage_name]['OutVar'];
	}

	var primary_var_id_str = (primary_var_id+1).toString();
	
	//add primary var, e.g. depth or time
	if (contaminants || eutrophication){
	    scatter_plot_vars.splice(0,0,primary_var_id_str);
	}
	//add more if eutrophication
	// console.log("primary_var_id")
	// console.log(primary_var_id)
	// console.log("eutro_hidden_vars")
	// console.log(eutro_hidden_vars)
	//
	if (eutrophication){
	    for (var i in eutro_hidden_vars){
		if (eutro_hidden_vars[i] != primary_var_id_str){
		    scatter_plot_vars.splice(0,0,eutro_hidden_vars[i]);
		}
	    }
	}


	// console.log("W_get_collection_information")
	// console.log(W_get_collection_information)

	if (jQuery.isEmptyObject(W_get_collection_information) == false){
	    
	    $.each(scatter_plot_vars,function(i,e){		
		//console.log("i="+i+", e="+e + "type:"+typeof(e))
		//split
		ex = e.split('|');
		// console.log("ex")
		// console.log(ex)
		$.each(ex,function(f,g){
		    n = Number(g)-1;
		    ns = n.toString();
		    //console.log(jsonData.station_set[ns])
		    anz = jsonData.station_set[ns];
		    //this check in probably not needed anymore for contaminants because I'm now checking for the potentially available data

		    //but this is needed on page 1 to update the outvar list etc.
		    

		    if (anz!="-"){ // && anz!="0"){
			if (contaminants == true && project == "EMODnet Chemistry"){
			    //OutVar_temp.push($(this));
			    //console.log($(this))
			    var_x_dropdown = var_x_dropdown + '<option  value="' + n + '" data-content=\'<span data-toggle="tooltip" data-placement="auto" title="' + p01_list[p01_num[(n+1)]].tooltip  + '">' + p01_num[(n+1)] + '</span>\'>' + p01_num[(n+1)] + '</span></option>';
			} else {
			    var_x_dropdown = var_x_dropdown + '<option value="' + n + '">' + W_get_collection_information.datavar_unames[n] + '</option>';
			}
		    }
		})
	    });
	}
	//webodv_status[storage_name]['OutVar'] = OutVar_temp;
	//$(treeview_vars_id).trigger("CheckUncheckDone");
	//}

	
	//console.log($('#set_x_var').children('option'))
	$('#set_x_var').children('option').remove();
	$('#set_x_var').append(var_x_dropdown);
	$('#set_x_var').selectpicker('refresh');
	$('#set_y_var').children('option').remove();
	$('#set_y_var').append(var_x_dropdown);
	$('#set_y_var').selectpicker('refresh');

	//trigger the selectpicker for the axis vars
	//triggers twice, i.e. for x and y
		//only create image if we are in page 3
		//this must be triggered every time, because everything is so dynamic
		//stations could have changed, variables could have changed
		if (pager_num == 99){
		    //console.log("second pager_num=3")
		   //console.log("pager_num == 3 after get_data_availability_information")
		    //don't trigger modify view etc. by the selectoicker x_var_sel and y_var_sel
		    viz_var_trigger = false;
		    x_var_str = x_var.toString();
		    y_var_str = y_var.toString();
		    $('#set_x_var').selectpicker('val', x_var_str);
		    $('#set_y_var').selectpicker('val', y_var_str);
		    //note that a change of the selectpicker triggers an action
		    //see: $('#set_x_var, #set_y_var').on('changed.bs.select', function(){

		    // x_var++;
		    // y_var++;
		    
		    
		   //console.log("x_var")
		   //console.log(x_var)
		   //console.log("y_var")
		   //console.log(y_var)
		    
		    // x_var++;
		    // y_var++;
		   //console.log("y_var="+y_var)
		   //console.log("y_var="+typeof(y_var))
		   //console.log("depth_var_num="+depth_var_num)
		   //console.log("depth_var_num="+typeof(depth_var_num))
		    if (y_var == depth_var_num){
			//var y_axis_reversed = "T";
			//flip inputs
			//$('#y_minimum').removeClass('order-1').addClass('order-2');
			// $('#y_minimum_text').text('bottom:');
			//$('#y_maximum').removeClass('order-2').addClass('order-1');
			// $('#y_maximum_text').text('top:');
		    } else {
			//var y_axis_reversed = "F";
			//flip inputs
			//$('#y_minimum').removeClass('order-2').addClass('order-1');
			// $('#y_minimum_text').text('top:');
			//$('#y_maximum').removeClass('order-1').addClass('order-2');
			// $('#y_maximum_text').text('bottom:');			
		    }


		    //check reversed axes

		    n=Math.min(Math.max(1000, W_get_data_availability_information.sample_counts[1]),100000);
		    pointsize_scatter=Math.max(0.4,1.2-0.55*(Math.log10(n)-5.));
		    // console.log("pointsize_scatter")
		    // console.log(pointsize_scatter)

		    var view_snippet = '<DataWindow window_id="1"> <AxisVariables> <xAxis var_id="' + x_var + '" is_reversed="' + y_axis_reversed + '"/> <yAxis var_id="' + y_var + '" is_reversed="' + y_axis_reversed + '"/> </AxisVariables> <Symbol small_size="0.3" color="0" type="0" size="'+ pointsize_scatter  +'"/> </DataWindow>';
		    //
		    var range_id = 'range_'+x_var.toString()+'_'+y_var.toString();
		    var current_range = webodv_status[storage_name][range_id];
		   //console.log("current_range")
		   //console.log(current_range)
		    
		    if (typeof(current_range) == "undefined" ){
			var x_from = -1e10;
			var x_to = 1e10;
			var y_from = -1e10;
			var y_to = 1e10;
		    } else {
			var x_from = current_range.x_range_from;
			var x_to = current_range.x_range_to;
			var y_from = current_range.y_range_from;
			var y_to = current_range.y_range_to;
		    }
		    //
		    //view_snippet = "";
		    view_snippet = view_snippet + '<DataWindow window_id="1"> <Window> <Geometry x_left="'+ x_from  +'" x_right="'+ x_to  +'" /> </Window> </DataWindow>' +'<DataWindow window_id="1"> <Window> <Geometry y_bottom="'+ y_from  +'" y_top="'+ y_to  +'" /> </Window> </DataWindow>';
		    //
		    //
		    
		    //console.log(view_snippet)
		    var set_x_var_option = {"view_snippet":view_snippet};
		    //show_image_loading('.img_col');
		    send_wsODV_msg('modify_view',set_x_var_option);

		    if ($("#outliers").is(":checked")){
			var set_sample_filter_option = {"option":"set_sample_filter","win_id":1,"type":"no_outliers"};
		    } else {
			var set_sample_filter_option = {"option":"set_sample_filter","win_id":1,"type":"all_samples"};
		    }
		    send_wsODV_msg('execute_option',set_sample_filter_option);
		    
		    
		    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
		    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
		}
	//
		

    }
    

    
    //------------------------------connect to wsODV-------------------------------//
    //connect websockets
    if (typeof MozWebSocket == 'function') {
	WebSocket = MozWebSocket;
    }
    if ( websocket && websocket.readyState == 1 ) {
	websocket.close();
    }
    websocket = new WebSocket( wsUri );


    //onopen
    websocket.onopen = function (evt) {
    	console.log("CONNECTED");
    	//now login
	show_image_loading('.img_col');
    	send_wsODV_msg("login_request");
    }


    //onmessage
    websocket.onmessage = function (evt) {
    	//console.log(evt.data)

	
    	if (typeof(evt.data) == "string") {
    	    //parse message
    	    jsonData = JSON.parse(evt.data);
	    //console.log(jsonData)

	    
    	    //after 15 min of inactivity user will be logged out from odv
    	    //if (jsonData.reply_id == "time_out_event" && jsonData.success == true) {
	    //{“reply_id”:"notification","success":true,"notification_type":"time_out_event","msg":"closing session ab9cf"}
	    if (jsonData.reply_id == "notification" && jsonData.success == true && jsonData.notification_type == "time_out_event") {
		$('#inactivity_modal').modal('show');
		$('.webodvextractor_page_1, .webodvextractor_page_2, .webodvextractor_page_3, .webodvextractor_page_4').hide();

		$("body").on("click", function(e){
		    if ($(e.target).hasClass('exit-pager')) {
			return false;
		    }
	    	    $('#inactivity_modal').modal('show');
		});


		
		function inactivityX(){
		    //enable pager during loading
		    $('.page-link').removeClass('pager-disabled');
		    enable_pager = true;
		}
		
		inactivityX();
		//if pager has been clicked
		$(document).on('page',function(){
		    //if (pager_num != 5){
		    if (pager_num != 4){
			$('#inactivity_modal').modal('show');
		    }
		    //$('.webodvextractor_page_1, .webodvextractor_page_2, .webodvextractor_page_3, .webodvextractor_page_4').hide();
		    $('.webodvextractor_page_1, .webodvextractor_page_2, .webodvextractor_page_3, .data_preview_page').hide();
		    inactivityX();
		});
		
		//test
		//send_wsODV_msg("logout_request");
	    }

    	    if (jsonData.reply_id == "get_collection_information" && jsonData.success == true) {
		// console.log(jsonData.reply_id)
		// console.log(jsonData)
		//console.log(jsonData)
		W_get_collection_information = jsonData;
		//

		primary_var_id = jsonData.primary_var_id;

		//console.log("get_collection_information")
		
		//treeview_emodnet_contaminants_connect();

		
		//console.log(jsonData.cruise_names)
		//set cruises
		//					<option value="{{ $cruise }}" selected="selected" >{{ $cruise}}</option>
		var cruise_names = "";
		// if (jsonData.cruise_names.length <= 1){
		//     cruise_names = '<option value="' + '*' + '" selected="selected">' + 'All' + '</option>';
		// } else {
		    $.each(jsonData.cruise_names, function(i,e){
			cruise_names = cruise_names + '<option value="' + e + '" selected="selected">' + e + '</option>';
		    });
		//}
		//console.log(cruise_names)
		$("#select_cruise").append(cruise_names);
		$('#select_cruise').selectpicker('refresh');

		//console.log(Xstatus)
		if (typeof(Xstatus.cruises) != "undefined"){
		    if (Xstatus.cruises.length > 0){
			//console.log(Xstatus.cruises)
			// $('#select_cruise').on('loaded.bs.select,refreshed.bs.select,shown.bs.select', function(){
			// 	console.log("selectAll")
			// 	$(this).selectpicker('selectAll');
			// });
			$('#select_cruise').selectpicker('deselectAll');
			$('#select_cruise').selectpicker('val',Xstatus.cruises);
		    }
		}

	    }
	    
    	    if (jsonData.reply_id == "login_request" && jsonData.success == true) {
		//console.log("odvws login_request")
		//console.log(jsonData)
		websocket_login = true;
		modify_view_data = jsonData;
		$('.num_stations_total').text(jsonData.station_count).trigger("change");
		$('.num_stations').text(jsonData.selected_station_count).trigger("change");
		//console.log("login")
		//logged_in = true;
		// n=Math.min(Math.max(1000, jsonData.station_count),100000);
		// pointsize=Math.max(0.4,1.2-0.55*(Math.log10(n)-3.));
		
		//get collection info
		send_wsODV_msg("get_collection_information");
		
		
		//init fire
		//map
		if (localStorage.getItem(storage_name) !== null){
		    //console.log(localStorage.getItem(storage_name))
		    Xstatus = JSON.parse(localStorage.getItem(storage_name))
		    //console.log(Xstatus)
		    if (typeof(Xstatus.coords) != "undefined"){
			k1 = Xstatus.coords[0];
			k2 = Xstatus.coords[1];
			m1 = Xstatus.coords[2];
			m2 = Xstatus.coords[3];
		    }
		    if (typeof(Xstatus.pointsize) != "undefined"){
			pointsize = Xstatus.pointsize;
		    }

		    if (contaminants == true && project == "EMODnet Chemistry"){
			var dummy = true;
		    } else {
			if (typeof(Xstatus.ReqVarEx) != "undefined"){
			    ReqVarEx = Xstatus.ReqVarEx;
			    var view_snippet = '<StationSelectionCriteria>' + '<RequiredVarExpression expr="' + ReqVarEx + '"/>'  + '</StationSelectionCriteria>';
			    var required_vars_option = {"view_snippet":view_snippet};
			    send_wsODV_msg('modify_view',required_vars_option);
			}
		    }

		    // if (typeof(Xstatus.odv_mouse_x) != "undefined"){
		    // 	var left_mouse_click_options = {"odv_mouse_x":Xstatus.odv_mouse_x, "odv_mouse_y":Xstatus.odv_mouse_y};
		    // 	//letf click
		    // 	console.log("left_click")
		    // 	send_wsODV_msg("left_mouse_click",left_mouse_click_options);
		    // }
		}
		
		


		
		//zoom map
		zoom_options = {"coords":[k1,k2,m1,m2], "pointsize":pointsize};
		send_wsODV_msg("map_domain",zoom_options);

		//set date

		// console.log(dates[0])
		// console.log(dates[1])
		//
		//console.log("init_set_date")
		//date_options = {"date1":dates[0], "date2":dates[1]};
		date_options = {"date1":'01/01/-99999', "date2":'12/31/99999'};


		// get_canvas_window here !!!!!!!!!!!!!!!
		$('#date_ban').trigger("click");
		//send_wsODV_msg("set_date",date_options);

		//setdate();
		//var option = {"view_snippet":view_snippet};
		//send_wsODV_msg('modify_view',option);




		//send_wsODV_msg('get_canvas_window');

		
		//var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
		//send_wsODV_msg('get_canvas_window',get_canvas_window_options);


		if (init_outvar_treeview_done){
		    //console.log('get_data_availability_information from login')
		    send_wsODV_msg('get_data_availability_information');
		}
		//send_wsODV_msg("get_canvas_window");

		//console.log("login_request=true")
    		//$('#station_count').val(jsonData.station_count);
		//if datepickers are initialised send modify view
		//otherwise it is send from datepickers_init_event below
		//if (datepickers_init == 2){
		//    console.log("modify_view from datepickers_init_event in login")
		    //if this is the first modify view start auto
		    //wsODV_modify_view("cruise",true);
		    //wsODV_set_cruises("cruise",false);
		//    ws_monitor_timer();
		//}
		//console.log("was in login_request")
		//get_dock_metavar_labels
		send_wsODV_msg("get_labels");
    	    }


    	    if (jsonData.reply_id == "load_view" && jsonData.success == true) {
		//console.log("load_view")
	    	//console.log(jsonData)
		//update modify_view_data.rects[active_window]
		//if user comes back from visualisation to select stations
		//to correctly set the cropper
		if (pager_num == 1){
		    modify_view_data.rects[0] = jsonData.rects[0];
		}
		
	    }

	    if (jsonData.reply_id == "execute_option"){ // && jsonData.success == true) {
		//console.log(jsonData)
	    }

	    //this is only needed once
    	    if (jsonData.reply_id == "get_dock_metavar_labels" && jsonData.success == true) {
    	    	//console.log(jsonData.labels);
	    	get_dock_metavar_labels = jsonData.labels;
    	    }
    	    if (jsonData.reply_id == "get_dock_datavar_labels" && jsonData.success == true) {
    	    	//console.log(jsonData.labels);
	    	get_dock_datavar_labels = jsonData.labels;
    	    }

	    if (jsonData.reply_id == "get_data_availability_information" && jsonData.success == true) {
		//console.log("here get_data_availability_information")
		//console.log(jsonData)

		

		if (pager_num == 99){
		    //check number of samples in the scatterplot
		    //and warn in case
		    W_get_data_availability_information = jsonData;
		    if (jsonData.sample_counts[1]>1200000){
			//console.log("larger 10000")
			$('#scatterplot_num_samples').text(jsonData.sample_counts[1]);
			$('#scatterplot_modal').modal('show');				
		    } else {
			//console.log("smaller 10000")
			get_data_availability_information_continue();
			//$('#scatterplot_num_samples').text(jsonData.sample_counts[1]);
			//$('#scatterplot_modal').modal('show');	
		    }
		} else {
		    //console.log("W_get_data_availability_information")
		    //debugger;
		    W_get_data_availability_information = jsonData;
		    get_data_availability_information_continue();
		}
	    }	    	 
	    
		
		

    	    // if (jsonData.reply_id == "select_current_sample" && jsonData.success == true) {
    	    // 	console.log(jsonData.labels);
	    // 	draw_scene(jsonData);
    	    // }
	    
	    

	    // //this is needed on every click
    	    if (jsonData.reply_id == "get_current_station_dock_metadata" && jsonData.success == true) {
    	    	//console.log(jsonData.values);
	    	get_dock_metavar_labels_list = '<div class="table-responsive table_wrapper" style="overflow-y:scroll; height:200px;"><table class="table table-sm table-striped table-condensed">';
	    	//save cruise for picked stations
	    	//get cruise name from jsonData.values[1]
	    	//get number of that cruise from sites_list[jsonData.values[1]]
	    	//console.log("sites_list")
	    	//console.log(sites_list)
	    	//console.log("station_id= " + station_id);
	    	//console.log("site name= " + jsonData.values[1]);
	    	//cruise_num = sites_list[jsonData.values[1]];
	    	//stations_picked[station_id] = sites_list[jsonData.values[1]];
	    	//console.log("site number= " + stations_picked[station_id]);
	    	//console.log(stations_picked)

		
	    	$.each(get_dock_metavar_labels,function(i,e) {
	    	    //console.log("i= " + i + ", e= " + e + ", value=" + jsonData.values[i]);
	    	    get_dock_metavar_labels_list = get_dock_metavar_labels_list + '<tr class="station_name"><td>' + e + '</td>' + '<td class="station_name">' + jsonData.values[i] + '</td></tr>';
		    if (e.toString() == "Cruise"){
			current_cruise = jsonData.values[i];
			// $('.station_info').html('Cruise: '+ current_cruise  + ' <span style="float:right;"><i class="fa fa-caret-down"></i></span>');
			// $('.station_info').children(".text-left").text('Cruise: '+ current_cruise);
			// $('.station_info').children(".text-right").html('<i class="fa fa-caret-down"></i>');

			//$('.station_info').html('Cruise: '+ current_cruise + '<span class="float-right"><i class="fa fa-caret-down"></i></span>');
			
		    }
	    	});
	    	get_dock_metavar_labels_list = get_dock_metavar_labels_list + "</table></div>";
		//console.log(get_dock_metavar_labels_list)
		$(".get_dock_metavar_labels_list").html(get_dock_metavar_labels_list);
		//console.log("in get_current_station_dock_metadata")
		set_window_height();
    	    }

	    // if (jsonData.reply_id == "get_current_sample_dock_data" && jsonData.success == true) {
    	    // 	//console.log(jsonData.values);
	    // 	get_dock_datavar_labels_list = '<div class="table-responsive table_wrapper"><table class="table table-striped table-condensed">';

	    // 	$.each(get_dock_datavar_labels,function(i,e) {
	    // 	    //console.log("i= " + i + ", e= " + e);
	    // 	    get_dock_datavar_labels_list = get_dock_datavar_labels_list + '<tr class="station_name"><td>' + e + '</td>' + '<td class="station_name">' + jsonData.values[i] + '</td></tr>';
	    // 	});
	    // 	get_dock_datavar_labels_list = get_dock_datavar_labels_list + "</table></div>";

    	    // }
	    


    	    if (jsonData.reply_id == "modify_view" && jsonData.success == true) {
    	    	//$('#station_count').val(jsonData.selected_station_count);
		//console.log("modify_view")
		//console.log(jsonData)
		//console.log(jsonData.user_rects[0].left)
		k1 = jsonData.user_rects[0].left;
		k2 = jsonData.user_rects[0].right;
		m1 = jsonData.user_rects[0].top;
		m2 = jsonData.user_rects[0].bottom;

	    	//draw scene with old data
	    	//draw_scene(modify_view_data);


		//console.log("viz_var_trigger")
		//console.log(viz_var_trigger)
		
	    	modify_view_data = jsonData;

		//get new map coordinates and update k1, k2, m1, m2
		


		//console.log(modify_view_data.user_rects)
		if (pager_num == 99){
		   //console.log("third pager_num=3")
		   //console.log("modify_view : pager_num=3")

		    count_move_map_scatter++;
		   //console.log("count_move_map_scatter= "+count_move_map_scatter)
		    
		    //don't go through on the first two modify views, where I move the map and the scatterplot
		    if (count_move_map_scatter>2){
			
			var x_from = modify_view_data.user_rects[1].left;
			var x_to = modify_view_data.user_rects[1].right;
			var y_from = modify_view_data.user_rects[1].bottom;
			var y_to = modify_view_data.user_rects[1].top;

			//console.log(x_from + ", " + x_to + ", " + y_from + ", " + y_to)
			
			//set axes range forms
			$('#x_range_from').val(x_from);
			$('#x_range_to').val(x_to);
			$('#y_range_from').val(y_from);
			$('#y_range_to').val(y_to);


			//set state of check boxes if a_variable_has_changed
			if (a_variable_has_changed == "x"){
			    if (typeof(x_axis_reversed[x_var.toString()] != "undefined")){
				if (x_axis_reversed[x_var.toString()] == "T"){
				    $("#reversed_axis_checkbox_x").prop("checked",true);
				} else {
				    $("#reversed_axis_checkbox_x").prop("checked",false);
				}
			    } else {
				$("#reversed_axis_checkbox_x").prop("checked",false);
			    }
			    a_variable_has_changed = "w";
			}

			//console.log("xxxxx")
			//console.log(y_axis_reversed[y_var.toString()])
			
			if (a_variable_has_changed == "y"){
			    if (typeof(y_axis_reversed[y_var.toString()] != "undefined")){
				if (y_axis_reversed[y_var.toString()] == "T"){
				    $("#reversed_axis_checkbox_y").prop("checked",true);
				} else {
				    $("#reversed_axis_checkbox_y").prop("checked",false);
				}
			    } else {
				$("#reversed_axis_checkbox_y").prop("checked",false);
			    }
			    a_variable_has_changed = "w";
			}
			

			//update status and localstorage
			var m_range = {'x_range_from':x_from, 'x_range_to':x_to, 'y_range_from':y_from, 'y_range_to':y_to}
			var m_range_id = 'range_'+x_var.toString()+'_'+y_var.toString();
			webodv_status[storage_name][m_range_id] = m_range;
			localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
		    }
		}

		$('.num_stations').text(jsonData.selected_station_count).trigger("change");
		//console.log("baggi1")

	    	//draw_scene(modify_view_data);

		//trigger a left click on the last selected station
		if (!$.isEmptyObject(left_click_data)){
		    //console.log("select_current_sample")
		    //console.log(left_click_data)
		    //console.log(left_click_data.points.x_coords[0])
		    //get coordinates of the currently selected station
		    var select_current_sample_options = {"station_id":left_click_data.station_id.toString(),"sample_id":left_click_data.sample_id.toString()}; 
		    send_wsODV_msg("select_current_sample",select_current_sample_options);
			
		    //var left_mouse_click_options = {"odv_mouse_x":left_click_data.points.x_coords[0], "odv_mouse_y":left_click_data.points.y_coords[0]};
		    //send_wsODV_msg("left_mouse_click",left_mouse_click_options);
		}
		
    	    }


	    if (jsonData.reply_id == "select_current_sample"){ // && jsonData.success == true) {
		//console.log(jsonData)

		var left_mouse_click_options = {"odv_mouse_x":jsonData.points.x_coords[0], "odv_mouse_y":jsonData.points.y_coords[0]};
		send_wsODV_msg("left_mouse_click",left_mouse_click_options);

	    }


	    if (jsonData.reply_id == "export_graphics" && jsonData.success == true) {
		//console.log(jsonData)
		$.unblockUI();
		// var host_path = window.location.protocol + '//' + window.location.hostname + ':' + window.location.port;
	    	// $('#download_image_a_page'+pager_num.toString()).attr("href",host_path+"/downloads/"+download_image_name).attr("download",download_image_name);

		// $('#download_image_button_page'+pager_num.toString()).trigger("click");

	    }
	    
	    if (jsonData.reply_id == "canvas_left_click_event" && jsonData.success == true) {
		//console.log(jsonData)
		left_click_data = jsonData;
		//console.log(left_click_data)
		draw_scene(jsonData);
		//active_window = jsonData.clicked_win_id;
		// $("#active_window").text('Active window ' + active_window);
		// if (active_window == "0"){
		//     $("#full_range").text("full domain");
		//     //$("#global_map").prop("disabled",false);
		// } else {
		//     $("#full_range").text("full range");
		//     //$("#global_map").prop("disabled",true);
		// }
		// $("#point_size").text(active_window_point_size[active_window]);
		// station_id = jsonData.station_id;
		// sample_id = jsonData.sample_id;
	    }
    	}

    	if (evt.data instanceof Blob)
    	{
	    //console.log("it is a blob")
    	    var head;
    	    var blob = new Blob([evt.data]);
    	    var header = blob.slice(0, 256);
	    //console.log(header)
	    var body = blob.slice(256);
	    //console.log(body)
    	    var reader = new FileReader();

	    
    	    reader.onload = function(evt)
    	    {
    		if (head === undefined) {
    		    head = evt.target.result;
    		    head = head.substring(0, head.indexOf("\0"));
    		    head = JSON.parse(head);
    		    reader.readAsDataURL(body)
    		} else {
		    //console.log(head.msg_type)
    		    switch (head.msg_type) {
    		    case "canvas_image":
    			//console.log("get_canvas_window")
			//wsodv_get_collection_information();
    			//$("#loading").hide();
    			//$("#img_container").show();
    			$(".wsODV_map").attr("src",evt.target.result);
			//automatic timer only if the new image is coming from the user
			//console.log("manual_wsODV= " + manual_wsODV)
			//go through on first visit		
    			break;
    		    case "file_contents":
			//console.log("file")
    			//saveBlobAs(body, head.file);
			// $('#download_image_a_page'+pager_num.toString()).attr("href",evt.target.result);
			// $('#download_image_button_page'+pager_num.toString()).trigger("click");
	    		$('#download_image_a_page'+pager_num.toString()).attr("href",evt.target.result).attr("download",download_image_name);
			$('#download_image_button_page'+pager_num.toString()).trigger("click");

			break
    		    case "custom_image":
			//console.log("custom_image")
			//$('#download_image_a_page'+pager_num.toString()).attr("href",evt.target.result);
			//$('#download_image_button_page'+pager_num.toString()).trigger("click");
    			break
    		    }
    		}
    	    };
    	    //reader.readAsDataURL(blob);
    	    reader.readAsText(header);
    	}
    }//end websocket.onmessage




    websocket.onclose = function (evt) {
	//console.log(evt)
    	console.log("DISCONNECTED");
	//window.location = home;
	
    };

    websocket.onerror = function (evt) {
    	console.log('ERROR');
	$('#error_modal').modal('show');
    };


    send_wsODV_msg = function(xcommand,options){

	
	
	//--login_request
	if (xcommand == "login_request"){
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
    		    {
			'cmd' : 'login_request',
			'view' : '$FullScreenMap$',
    		    },
		],
    	    };
	}

	if (xcommand == "logout_request"){
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
    		    {
			'cmd' : 'logout_request',
			'save_view' : false,
    		    },
		],
    	    };
	}

	//--save image
	if (xcommand == "get_image"){
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
    		    {
			'cmd' : 'get_image',
			'type' : 'x_histogram',
			'fmt' : 'gif',
			'window_id' : options.window_id,
    		    },
		],
    	    };
	}

	//--save graphics
	//{"cmd":"export_graphics","win_id":-1,"file":"C:/atmp/image.png","dpi":300,
	//"transparent_background":true}
	if (xcommand == "export_graphics"){
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
    		    {
			'cmd' : 'export_graphics',
			'win_id' : options.win_id,
			'fmt' : "png",
			//'file' : '/var/www/html/webodv/public/downloads/' + options.name,
			//'file' : '/home/woody/' + options.name,
			'dpi' : 300,
			'transparent_background': true,
    		    },
		],
    	    };
	}

	//console.log(websocket_msg)


	//--load_view
	if (xcommand == "load_view"){
	    // $('.img_col').block({
	    // 	message: '<h1>Loading ...</h1>',
	    // 	overlayCSS: { backgroundColor: '#fffff' },
	    // });

	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
    		    {
			'cmd' : 'load_view',
			'view' : options.view,
    		    },
		],
    	    };
	}

	//--get_canvas_window
	if (xcommand == "get_canvas_window"){
	    //block image
	    // $('.img_col').block({
	    // 	message: '<h1>Loading ...</h1>',
	    // 	overlayCSS: { backgroundColor: '#fffff' },
	    // });

	    
	    var websocket_msg = {
    		"sender_id" : sessionId,
    		"cmds" :  [
    		    {
    			"cmd" : "get_canvas_window",
    			"win_id" : options.win_id,
    			"extended_rect" : options.extended_rect,
    			"fmt" : "gif",
    		    },
    		]
    	    }
	}


	//--execute option
	if (xcommand == "execute_option"){

	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
		    {
			"cmd" : "execute_option",
			"option" : options.option,
			"win_id" : options.win_id,
			"type" : options.type,
		    },
		    // {
    		    // 	"cmd" : "get_canvas_window",
    		    // 	"win_id" : -1,
    		    // 	"extended_rect" : false,
    		    // 	"fmt" : "gif",
    		    // },
		]
	    }
	}
	
	//--modify view
	if (xcommand == "modify_view"){
	    //console.log("modify_view")
	    // $('.img_col').block({
	    // 	message: '<h1>Loading ...</h1>',
	    // 	overlayCSS: { backgroundColor: '#fffff' },
	    // });

	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
		    {
			"cmd" : "modify_view",
			"view_snippet" : options.view_snippet,
		    },
		    // {
    		    // 	"cmd" : "get_canvas_window",
    		    // 	"win_id" : -1,
    		    // 	"extended_rect" : false,
    		    // 	"fmt" : "gif",
    		    // },
		]
	    }
	}

	//--select_current_sample
	if (xcommand == "select_current_sample"){
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
		    {
			"cmd" : "select_current_sample",
			"conditions" :
			[
			    {"name" : "station_id",
			     "value" : options.station_id, //jsonData_canvas_left_click_event.station_id.toString(),
			    },
			    {"name" : "sample_id",
			     "value" : options.sample_id, //jsonData_canvas_left_click_event.sample_id.toString(),
			    }
			]
		    },
		    // {
		    // 	"cmd" : "get_current_station_dock_metadata",
		    // },
		    // {
		    // 	"cmd" : "get_current_sample_dock_data",
		    // },
		]
	    }
	    //console.log(JSON.stringify(websocket_msg))
	}

	//--get labels
	if (xcommand == "get_labels"){
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
    		    {
			"cmd" : "get_dock_metavar_labels",
    		    },
    		    {
			"cmd" : "get_dock_datavar_labels",
    		    },
		],
    	    }
	}

	//--get collection
	if (xcommand == "get_collection_information"){
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
    		    {
			"cmd" : "get_collection_information",
    		    },
		],
    	    }
	}
	


	//--set date
	if (xcommand == "set_date"){
	    //
	    //console.log("websockets")
	    //console.log(options.date1)
	    //console.log(options.date2)
	    //localStorage.setItem("date1", options.date1);
	    //localStorage.setItem("date2", options.date2);
	    
	    //do not save for -99999
	    if (options.date1 != '01/01/-99999'){
		webodv_status[storage_name]['date1'] = options.date1;
		webodv_status[storage_name]['date2'] = options.date2;
		dates[0] = options.date1;
		dates[1] = options.date2;
		localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	    }
	    
	    date1 = options.date1.split('/');
	    date2 = options.date2.split('/');

	    //
	    var websocket_msg = {
	    	"sender_id" : sessionId,
	    	"cmds" :  [
	    	    {
	    		"cmd" : "modify_view",
	    		"view_snippet" :  '<StationSelectionCriteria>' +  '<Period first_day="' + date1[1] + '" last_year="' + date2[2] + '" first_month="' + date1[0] + '" last_month="' + date2[0] + '" last_day="' + date2[1] + '" first_year="' + date1[2] + '" first_hour="' + "0" + '" last_hour="' + "24" + '" first_minute="' + "1" + '" last_minute="' + "60" +'"/>'   + '</StationSelectionCriteria>'
	    	    },
	    	]
	    }
	}
	    
	
	//--map domain
	if (xcommand == "map_domain"){
	    //
	    //console.log(options.coords)
	    webodv_status[storage_name]['coords'] = options.coords;
	    webodv_status[storage_name]['pointsize'] = options.pointsize;

	    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));

	    //localStorage.setItem("default_koordinates", [options.coords[0],options.coords[1],options.coords[2],options.coords[3]]);
	    //localStorage.setItem("pointsize", options.pointsize);
	    //
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
		    {
			"cmd" : "modify_view",
			"view_snippet" :  '<GeographicMap> <MapDomain lon_min="' + options.coords[0] + '" lon_max="' + options.coords[1] + '" lat_min="' + options.coords[2] + '" lat_max="' + options.coords[3] + '"/> </GeographicMap>' + '<MapWindow  background_color="31"> <Symbol small_size="0.4" color="1" type="0" size="-0.1"/>'
		    },
		    //+ '<MapWindow  background_color="31"> <Symbol small_size="0.4" color="1" type="0" size="' + options.pointsize + '"/>'
    		    // {
    		    // 	"cmd" : "get_canvas_window",
    		    // 	"win_id" : -1,
    		    // 	"extended_rect" : false,
    		    // 	"fmt" : "gif",
    		    // },		
		]
	    }
	}

	//--pointsize
	if (xcommand == "pointsize"){
	    //
	    webodv_status[storage_name]['pointsize'] = options.pointsize;
	    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	    //
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
		    {
			"cmd" : "modify_view",
			"view_snippet" :  '<MapWindow  background_color="31"> <Symbol small_size="0.4" color="1" type="0" size="' + options.pointsize + '"/>'
		    },
		]
	    }
	}

	
	//--left_click
	if (xcommand == "left_mouse_click"){

	    //console.log("websocket left_mouse_click")
	    //console.log("save status")
	    //console.log(options)
	    
	    webodv_status[storage_name]['odv_mouse_x'] = options.odv_mouse_x;
	    webodv_status[storage_name]['odv_mouse_y'] = options.odv_mouse_y;


	    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	    
	    //console.log(status)

	    
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
		    {
			"cmd" : "canvas_left_click_event",
			"x" : options.odv_mouse_x,
			"y" : options.odv_mouse_y,
		    },
		    {
			"cmd" : "get_current_station_dock_metadata",
		    },
		    {
			"cmd" : "get_current_sample_dock_data",
		    },
		],
	    };
	}

	//--get_data_availability_information
	if (xcommand == "get_data_availability_information"){
	    // webodv_status[storage_name]['odv_mouse_x'] = options.odv_mouse_x;
	    // webodv_status[storage_name]['odv_mouse_y'] = options.odv_mouse_y;
	    // localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	    var websocket_msg = {
		"sender_id" : sessionId,
		"cmds" :  [
		    {
			"cmd" : "get_data_availability_information",
		    },
		],
	    };
	}

	

	
	
    	var msg = JSON.stringify(websocket_msg);
	//console.log("################################################")
	//console.log(msg)
    	if ( websocket != null ) websocket.send( msg );
    }









    $(document).on("cropper_destroy",function(){
	webodv_canvas_img.cropper.destroy();
    });

    
    var webodv_canvas_img = document.getElementById("wsODV_map");
    function Xcropper() {
	new Cropper(webodv_canvas_img,{
     	    movable: false,
    	    zoomable: false,
    	    rotatable: false,
    	    scalable: false,
    	    autoCropArea: 0.3,
	    guides: false,
	    viewMode: 1,
	    background: false,


	    ready: function(e) {

		//console.log("cropper")
		//console.log(modify_view_data)
		//console.log(active_window)
		//console.log(modify_view_data.rects[active_window])

		var active_window = 0;
		this.cropper.crop();
		zoom_mode = true;

		cropperData = this.cropper.getData();
		cropperData.x = modify_view_data.rects[active_window].left + (modify_view_data.rects[active_window].right-modify_view_data.rects[active_window].left)*0.25;
		cropperData.y = modify_view_data.rects[active_window].top + (modify_view_data.rects[active_window].bottom-modify_view_data.rects[active_window].top)*0.25;
		cropperData.width = (modify_view_data.rects[active_window].right-modify_view_data.rects[active_window].left)*0.5;
		cropperData.height = (modify_view_data.rects[active_window].bottom-modify_view_data.rects[active_window].top)*0.5;
		this.cropper.setData(cropperData);

		$(document).on("dblclick", function() {
		    // if (zoom_mode == true) {
		    // 	//console.log("dblclick")
		    // 	if (active_window == 0) {
		    // 	    wsODV_map_domain(k1,k2,m1,m2);
		    // 	} else {
		    // 	    wsODV_axis_range(k1,k2,m1,m2);
		    // 	}
		    //webodv_canvas_img.cropper.destroy();
		   // $(document).trigger("cropper_destroy");
		    // 	zoom_mode = false;
		    // }
		}).on("keyup", function(e) {
		    //if (zoom_mode == true) {
			if (e.which == 13) {
			    // if (active_window == 0) {
			    // 	wsODV_map_domain(k1,k2,m1,m2);
			    // } else {
			    // 	wsODV_axis_range(k1,k2,m1,m2);
			    // }			    
			    //webodv_canvas_img.cropper.destroy();
			     //$(document).trigger("cropper_destroy");
			    // zoom_mode = false;
			}
			if (e.which == 27) {
			    //webodv_canvas_img.cropper.destroy();
			    //$(document).trigger("cropper_destroy");
			    //zoom_mode = false;
			}
		    //}
		});



		//transform pixel to real coordinates
		//so, what is one pixel in coordinates
		//
		//console.log("active_window=" + active_window)
		//console.log(userRects[active_window])
		//console.log(Rects[active_window])
		//this is the transform factor in x (pixel to coordinates)
		var alpha = (modify_view_data.user_rects[active_window].right-modify_view_data.user_rects[active_window].left) / (modify_view_data.rects[active_window].right-modify_view_data.rects[active_window].left);
		//console.log("alpha= " + alpha)
		//this is the transform factor in y (pixel to coordinates)
		var beta = (modify_view_data.user_rects[active_window].bottom-modify_view_data.user_rects[active_window].top) / (modify_view_data.rects[active_window].bottom-modify_view_data.rects[active_window].top);
		//console.log("beta= " + beta)
		//this is the top left in real coordinates
		var x0 = modify_view_data.user_rects[active_window].left - alpha * modify_view_data.rects[active_window].left;
		//console.log("x0=" + x0)
		var y0 = modify_view_data.user_rects[active_window].top - beta * modify_view_data.rects[active_window].top;
//		//console.log("y0=" + y0)


		//on first pop up, i.e. without dragging the mouse
		    var x1 = cropperData.x;
		    //k1, k2, m1, m2 are global vars
		    k1 = x0 + x1 * alpha;
		    var x2 = cropperData.x+cropperData.width;
		    k2 = x0 + x2 * alpha;
		    var y1 = cropperData.y
		    m1 = y0 + y1 * beta;
		    var y2 = cropperData.y+cropperData.height;
		    m2 = y0 + y2 * beta;

		
		webodv_canvas_img.addEventListener('crop', function(e)  {
		    x1 = e.detail.x;
		    //k1, k2, m1, m2 are global vars
		    k1 = x0 + x1 * alpha;
		    x2 = e.detail.x+e.detail.width;
		    k2 = x0 + x2 * alpha;
		    y1 = e.detail.y
		    m1 = y0 + y1 * beta;
		    y2 = e.detail.y+e.detail.height;
		    m2 = y0 + y2 * beta;
		    
		    
		    //console.log("x1=" + x1);
		    //console.log("x2=" + x2);
		    //console.log("k1=" + k1);
		    //console.log("k2=" + k2);
		    //console.log("y1=" + y1);
		    //console.log("m1=" + m1);
		    //console.log("y2=" + y2);
		    //console.log("m2=" + m2);
		    //console.log(event.detail.action);
		});
		
	    }
	    
    	});	
    }

    


    //canvas 
    $("#wsODV_map").on("load", function(){
	//
	//$('.img_col').unblock();
	//console.log("load mediachange")
	hide_image_loading('.img_col');

	//enable pager during loading
	//$('.page-link').removeClass('pager-disabled');
	//enable_pager = true;

	if (pager_num == 1){
	    //do it with timeout
	    //setTimeout(function(){
	    //do not go into it if cruise selection is open

	    if (select_cruise_dropdown.hasClass("show")){
		//console.log("is shown")
	    } else {
		//not on first init
		if (treeview_emodnet_contaminants_connect_go == false){
		    //console.log("XXXXXXXXXXXXXXXXXXXX")
		    send_wsODV_msg('get_data_availability_information');
		}
	    }
	    //}, 40);
	}

	if (pager_num == 2){
	    //$('.show_loading_here').hide();
	    //$('.output_treeview_col').show();
	}

	
	//if page 1 check vars
	// if (pager_num == 1){
	//     console.log("image_load new -> get_data_avail")
	//     send_wsODV_msg('get_data_availability_information');
	// }


	//console.log(localStorage.getItem(storage_name))
	
	//set back to triue
	//this is used in the selectpicker x_var_sel, y_var_sel
	//it is false if the user enters page 3
	//because then modify view is triggered automatically
	//it is true if this is done, i.e. if the images has been loaded
	viz_var_trigger = true;



	
	if (left_mouse_click_on_map){
	    //console.log("image loaded")
	    //get window size
	    window_height = $(window).height();
	    window_width= $(window).width();
	    //console.log(window_height)
	    //console.log(window_width)

	    // zoom_in_offset = $("#zoom_in").offset();
	    // if (window_width<768) {
	    //     $("#left_box").css("height","300px");
	    // } else {
	    //     $("#left_box").css("height",zoom_in_offset.top+"px");
	    // }

	    //get original image size
	    orig_size_img_x = document.getElementById("wsODV_map").naturalWidth;
	    orig_size_img_y = document.getElementById("wsODV_map").naturalHeight;
	    //get actual size of scaled image
	    screen_size_img_x = $("#wsODV_map").width();
	    screen_size_img_y = $("#wsODV_map").height();
	    //console.log("screen_size_img_y="+screen_size_img_y)
	    //get offset of image relative to document	
	    img_offset = $("#wsODV_map").offset();
	    //to place canvas
	    img_offset_parent = $("#wsODV_map").position();
	    //console.log("img_offset_parent="+img_offset_parent.top)
	    //put canvas over image
	    canvas = document.createElement('canvas'); //Create a canvas element
	    //Set canvas width/height
	    canvas.style.width = screen_size_img_x;
	    canvas.id = 'webodv_canvas';
	    canvas.style.height = screen_size_img_y;
	    //Set canvas drawing area width/height
	    canvas.width = screen_size_img_x;
	    canvas.height = screen_size_img_y;
	    //Position canvas
	    canvas.style.position = 'absolute';
	    canvas.style.left = img_offset_parent.left;
	    canvas.style.top = img_offset_parent.top;
	    canvas.style.pointerEvents = 'none'; //Make sure you can click 'through' the canvas
	    //border for development
	    //canvas.style.border='4px solid #d3d3d3';
	    $(".wsODV_map_container").append(canvas);
	    //
	    htmlcanvas = document.getElementById("webodv_canvas");
	    ctx = htmlcanvas.getContext("2d");

	    //init
	    if (localStorage.getItem(storage_name) !== null){
		Xstatus = JSON.parse(localStorage.getItem(storage_name))
		if (typeof(Xstatus.odv_mouse_x) != "undefined"){
		    var left_mouse_click_options = {"odv_mouse_x":Xstatus.odv_mouse_x, "odv_mouse_y":Xstatus.odv_mouse_y};
		    //letf click
		    //console.log("image loaded: trigger left click")
		    //console.log(left_mouse_click_options)
		    send_wsODV_msg("left_mouse_click",left_mouse_click_options);
		}
	    }

	}
    });
    

    //use this function on window_resize
    //and on page change
    window.set_window_height = function(){
	//console.log("set_window_height")
	//set min height
	//get position of page start below pager
	var page_pos = $('.webodvextractor_page_'+pager_num).position();
	//var page_pos = $('.station_select_left_column').position();
	//use window.innerHeight; instead of $(document).height()
	//because this is individual for each page
	var page_height = window.innerHeight;
	var copyright_div_height = $("#copyright_div").height();
	//console.log("page_pos")
	//console.log(page_pos)
	//console.log("page_height")
	//console.log(page_height)
	//console.log("copyright_div_height")
	//console.log(copyright_div_height)


	//if (project == "EMODnet Chemistry"){

	    var min_height = Number(page_height)-Number(page_pos.top)-Number(copyright_div_height);
	//console.log("min_height")
	//console.log(min_height)
	    
	    $('.webodvextractor_page_'+pager_num).css({'min-height':min_height+'px'});

	    //find the selected_variables_vars on that page on the right column
	    //var bg_right_height = $('.webodvextractor_page_'+pager_num+'.bg-right').height();
	    var bg_right_height = $('.webodvextractor_page_'+pager_num+' .bg-right').height();
	    var bg_right_pos = $('.webodvextractor_page_'+pager_num+' .bg-right').position();
	    var selected_variables_vars_pos = $('.webodvextractor_page_'+pager_num+' .bg-right'+' .selected_variables_vars').position();

	    //console.log("pager_num")
	    //console.log(pager_num)
	    //console.log(bg_right_height)
	    //console.log(bg_right_pos)
	    //console.log(selected_variables_vars_pos)
	    //console.log($('.webodvextractor_page_'+pager_num+' .selected_variables_vars'))

	    //var selected_variables_vars_height = Number(bg_right_height)-( Number(selected_variables_vars_pos.top)-Number(bg_right_pos.top) );
	    if (typeof(selected_variables_vars_pos) != "undefined"){
		selected_variables_vars_height = Number(bg_right_height)-Number(selected_variables_vars_pos.top);
		$('.webodvextractor_page_'+pager_num+' .bg-right'+' .selected_variables_vars').children("h4").children("div").height(selected_variables_vars_height + "px");
	    }
	    //console.log("selected_variables_vars_height")
	    //console.log(selected_variables_vars_height)
	    //$(".selected_variables_vars_tab").css("height",selected_variables_vars_height);


	//}
	//make columns height until bottom
	//$('.station_select_left_column').css({'height':min_height+'px'});
	//var left_col_pos = $('.station_select_left_column').position();
	//var left_col_height = 
	//var copyright_div_pos = $("#copyright_div").position();
	//var diff_left_col = Number(copyright_div_pos.top)-Number(left_col_pos.top);
	//var new_left_col_height = Number(left_col_pos.top)+diff_left_col;
	//$('.station_select_left_column').css({'height':diff_left_col+'px'});
    }


    
    //make canvas responsive
    if (left_mouse_click_on_map){
	$( window ).resize(function() {

	    //console.log("in resize")
	    set_window_height();
	    
	    //check size if it is a large or small screen
	    //then change html and remove / add canvas
	    //checkSize(false);
	    
	    window_height = $(window).height();
	    window_width= $(window).width();
	    //console.log(window_height)
	    //console.log($(window).outerHeight())
	    //$('.bottom-div').height($(window).outerHeight());
	    //$('.bottom-div, .wsODV_map_container').outerHeight($(window).outerHeight());
	    // $('.wsODV_map').height($(window).outerHeight());
	    // var height_scale = $(window).outerHeight() / window_height * 100;
	    //console.log("height_scale="+height_scale)
	    // $('#wsODV_map').css('height',height_scale+'%');
	    
	    //console.log(window_width)

	    // zoom_in_offset = $("#zoom_in").offset();
	    // if (window_width<768) {
	    //     $("#left_box").css("height","300px");
	    // } else {
	    //     $("#left_box").css("height",zoom_in_offset.top+"px");
	    // }

	    
	    //clear canvas
	    //ctx.clearRect(0, 0, htmlcanvas.width, htmlcanvas.height);

	    //size of image has changed
	    screen_size_img_x = ($("#wsODV_map").width());
	    screen_size_img_y = ($("#wsODV_map").height());


	    //offset has changed
	    img_offset = $("#wsODV_map").offset();

	    //change size of canvas
	    canvas.style.width = screen_size_img_x;
	    canvas.style.height = screen_size_img_y;
	    canvas.width = screen_size_img_x;
	    canvas.height = screen_size_img_y;
	    
	    //draw scene again
	    draw_scene(left_click_data);
	});
    }


    function odv2screenScale() {
	//converts odv coordinate to screencoordiate
	var scale = $("#wsODV_map").width()/orig_size_img_x;
	return scale;
    }

    function draw_crosshair(x,y,scale,r) {
	//x and y are the coordnates as seen on the screen
	ctx.beginPath(); //	
	var length = Math.round(30*scale);
	x = Math.round(x*scale);
	y = Math.round(y*scale);
	ctx.strokeStyle="red";
	ctx.lineWidth=1;
	ctx.fillStyle = 'red';

	ctx.arc(x,y, r*scale, 0, 2 * Math.PI, false);

	ctx.moveTo(x-length,y);
	ctx.lineTo(x+length,y);
	ctx.moveTo(x,y-length);
	ctx.lineTo(x,y+length);



	ctx.stroke();
	ctx.fill();
	ctx.closePath(); //	
    }

    function write_text(x,y,scale,fontsize,Xtext) {
	ctx.beginPath(); //
	ctx.font = fontsize + "px sans-serif";
	ctx.fillStyle = "gray";
	ctx.textAlign = "left";
	ctx.fillText(Xtext, x*scale,y*scale); 
    	ctx.closePath(); //	

    }
    
    function draw_circle(x,y,scale,r) {
	r = r*scale;
	ctx.beginPath(); //

	ctx.arc(Math.round(x[0]*scale), Math.round(y[0]*scale), r, 0, 2 * Math.PI, false);
	
	ctx.moveTo(Math.round(x[0]*scale), Math.round(y[0]*scale));


    	for (var j=0;j<x.length-1;j++) {
    	    ctx.moveTo(Math.round(x[j]*scale), Math.round(y[j]*scale));
	    ctx.arc(Math.round(x[j+1]*scale), Math.round(y[j+1]*scale), r, 0, 2 * Math.PI, false);
    	}
	
	ctx.fillStyle = 'red';
	ctx.fill();
    	ctx.closePath(); //	

    }
    
    function draw_polyline(x,y,scale) {
	ctx.beginPath(); //
	ctx.moveTo(Math.round(x[0]*scale), Math.round(y[0]*scale));
    	for (var j=0;j<x.length-1;j++) {
    	    ctx.moveTo(Math.round(x[j]*scale), Math.round(y[j]*scale));
    	    ctx.lineTo(Math.round(x[j+1]*scale), Math.round(y[j+1]*scale));	    
    	}
	
	ctx.lineWidth = 1;
	ctx.strokeStyle = 'red';
	ctx.stroke();
    	ctx.closePath(); //	
    }

    function draw_border() {
	ctx.strokeStyle="black";
	ctx.lineWidth = 4;
	ctx.beginPath(); //
	ctx.rect(0,0,screen_size_img_x-1,screen_size_img_y-1);
	ctx.stroke(); 
	ctx.closePath(); //
    }

    
    function draw_scene(data) {
	//console.log("draw_scene")
	if (left_mouse_click_on_map){
	    ctx.clearRect(0, 0, htmlcanvas.width, htmlcanvas.height);
	    //get scale factor
	    var scale = odv2screenScale();
	    //console.log("scale= " + scale);
	    var Polylines = data.polylines;
	    //console.log("Polylines")
	    //console.log(Polylines)
	    
	    //test draw green borders around the windows/////////////////////////////////////////
	    //modify_view_data.rects[active_window].left
	    // $.each(modify_view_data.rects,function(i,e){
	    //     //console.log("e.rect.left= " + e.rect.left)
	    //     var width = (e.right-e.left)*scale;
	    //     var height = (e.bottom-e.top)*scale;
	    //     //console.log("width="+width)
	    //     //console.log("height="+height)
	    //     ctx.strokeStyle="green";
	    //     ctx.lineWidth = 4;
	    //     ctx.beginPath(); //
	    //     ctx.rect(e.left*scale,e.top*scale,width,height);
	    //     ctx.stroke(); 
	    //     ctx.closePath(); //

	    //     // write_text(e.left,e.top-10,scale,18,"Window " + i);

	    // });
	    ////////////////////////////////////////////////////////////////////////////////

	    
	    //plot crosshair
	    //for (var i=0;i<data.points.x_coords.length;i++) {
	    //clip only this window
	    //var scale = odv2screenScale();
	    //clear rects	    
	    //draw_crosshair(data.points.x_coords[i],data.points.y_coords[i],scale,2);
	    //ctx.clip();
	    //}

	    //map
	    //if Polylines exist, e.g. no clipping needed if FullScreenView
	    if (typeof(Polylines) != "undefined" && Polylines.length > 0){
		ctx.save();
		ctx.rect(modify_view_data.rects[0].left*scale,modify_view_data.rects[0].top*scale,(modify_view_data.rects[0].right-modify_view_data.rects[0].left)*scale,(modify_view_data.rects[0].bottom-modify_view_data.rects[0].top)*scale);
		//console.log("clipping")
		//console.log(data.polylines[0].rect.left*scale)
		//console.log((data.polylines[0].rect.right-data.polylines[0].rect.left)*scale)
		ctx.rect(data.polylines[0].rect.left*scale,data.polylines[0].rect.top*scale,(data.polylines[0].rect.right-data.polylines[0].rect.left)*scale,(data.polylines[0].rect.bottom-data.polylines[0].rect.top)*scale);
		ctx.clip();
		draw_crosshair(data.points.x_coords[0],data.points.y_coords[0],scale,2);
		ctx.restore();
	    } else {
		if (typeof(data.points) != "undefined"){
		    draw_crosshair(data.points.x_coords[0],data.points.y_coords[0],scale,2);
		}
	    }
	    
	    //data windows
	    if (typeof(Polylines) != "undefined"){
		for (var i=0;i<Polylines.length;i++) {
		    //save current state
		    //console.log("count="+i)
		    ctx.save();
		    //define clipping region
		    //console.log("clipping")
		    //console.log(modify_view_data.rects[i+1].left*scale)
		    //console.log(modify_view_data.rects[i+1].top*scale)
		    //console.log((modify_view_data.rects[i+1].right-modify_view_data.rects[i+1].left)*scale)
		    //console.log((modify_view_data.rects[i+1].bottom-modify_view_data.rects[i+1].top)*scale)
		    
		    ctx.rect(modify_view_data.rects[i+1].left*scale,modify_view_data.rects[i+1].top*scale,(modify_view_data.rects[i+1].right-modify_view_data.rects[i+1].left)*scale,(modify_view_data.rects[i+1].bottom-modify_view_data.rects[i+1].top)*scale);
		    //clip
		    ctx.clip();
		    //draw
		    draw_crosshair(data.points.x_coords[i+1],data.points.y_coords[i+1],scale,2);
		    draw_circle(data.polylines[i].x_coords,data.polylines[i].y_coords,scale,3);
		    draw_polyline(data.polylines[i].x_coords,data.polylines[i].y_coords,scale,2);
		    //restore
		    ctx.restore();
		}
	    }
	    //measure_end();
	}
	//place_divs();
    }

    
    //left click
    if (left_mouse_click_on_map){
	$("#wsODV_map").on("click", function(e){
	    //mouse coordinates in browser
	    screen_mouse_x = (e.pageX-img_offset.left);
	    screen_mouse_y = (e.pageY-img_offset.top);
	    //console.log("e.pageX= " + e.pageX);
	    //console.log("e.pageY= " + e.pageY);
	    //console.log("screen_mouse_x= " + screen_mouse_x);
	    //console.log("screen_mouse_y= " + screen_mouse_y);
	    //console.log("orig_size_img_x= " + orig_size_img_x);
	    //mouse coordinates in canvas
	    // screen_mouse_x = Math.round(e.pageX-img_offset.left);
	    // screen_mouse_y = Math.round(e.pageY-img_offset.top);
	    //display values
	    // $("#xval").val(screen_mouse_x);
	    // $("#yval").val(screen_mouse_y);
    	    //transform to wsODV coordinates using the original image size nat_x and nat_y
	    //	var scale = $("#qc_img").width()/orig_size_img_x; odv2screen
	    //screen to odv
    	    odv_mouse_x = Math.round(orig_size_img_x/screen_size_img_x*screen_mouse_x);
    	    odv_mouse_y = Math.round(orig_size_img_y/screen_size_img_y*screen_mouse_y);
	    //console.log("odv_mouse_x= " + odv_mouse_x);
	    //console.log("odv_mouse_y= " + odv_mouse_y);
    	    //display values
    	    // $("#odv_xval").val(odv_mouse_x);
    	    // $("#odv_yval").val(odv_mouse_y);
    	    //send message and retrieve infos

	    var left_mouse_click_options = {"odv_mouse_x":odv_mouse_x, "odv_mouse_y":odv_mouse_y};
	    send_wsODV_msg("left_mouse_click",left_mouse_click_options);


	    // var websocket_msg = {
	    // 	"sender_id" : sessionId,
	    // 	"cmds" :  [
	    // 	    {
	    // 		"cmd" : "canvas_left_click_event",
	    // 		"x" : odv_mouse_x,
	    // 		"y" : odv_mouse_y,
	    // 	    },
	    // 	    {
	    // 		"cmd" : "get_current_station_dock_metadata",
	    // 	    },
	    // 	    {
	    // 		"cmd" : "get_current_sample_dock_data",
	    // 	    },
	    // 	],
	    // };
    	    // var msg = JSON.stringify(websocket_msg);
	    // if ( websocket != null ) websocket.send( msg );
	});
    }



    //go through twice

	//on page load
    //set_window_height()
	//on page load

    	// setTimeout(function(){
        //     set_window_height();
	// }, 2);


    




    //save session
    // websocket.onmessage = function (evt) {
    // 	console.log(evt)
    // }


    
    

});
