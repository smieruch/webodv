
//number_of_pages get from Controller



var pager_num = 1;

if (typeof(index_page) != "undefined"){
    if (index_page == "TRUE"){
	if (typeof(localStorage.getItem("pager_num")) != "undefined"){
	    if (localStorage.getItem("pager_num") != null){
		var pager_num = localStorage.getItem("pager_num");
	    }
	}	
    }
}


// console.log("pager_num")
// console.log(pager_num)




$(document).ready(function() {

    //csrf ajax
    $.ajaxSetup({
	headers: {
    	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
    });



    
    // console.log("fire")
    // console.log(create_treeview_from_folder_ajax_url)
    var loading_snippet = '<div class="loading_snippet"><h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1></div>';
    var progress_snippet = '<div class="progress" style="height:40px;">'+
	'<div class="progress-bar bg-info" role="progressbar" aria-valuenow="0"'+
	'aria-valuemin="0" aria-valuemax="100" style="width:0%;height:40px;font-size:18px;" id="progress">0 %</div>'+
	'</div>';

    
    $('.create_treeview_from_folder').append(loading_snippet);
    //$('.create_treeview_from_folder').append(progress_snippet);

    
    window.create_treeview_from_folder_function = function(){
	if (typeof(create_treeview_from_folder_ajax_url) != "undefined"){

	    //$('.create_treeview_from_folder').append(loading_snippet);
	    $('.loading_snippet').show();

	    $('#upload').prop('disabled',true).css('cursor','not-allowed');
	    
	    console.log("create_treeview_from_folder !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!11")
	    $.ajax({
		type: "POST",
		url: create_treeview_from_folder_ajax_url,
		data: {"pager_num":pager_num},
		dataType: "json",		
    		cache: "false",
		func: "create_treeview_from_folder_ajax",
		error:   function(data){
    		    console.log("ERROR");
		    console.log(data)
    		},
    		success: function(data){
    		    console.log("SUCCESS 123");
    		},
	    });	
	}
    }

    create_treeview_from_folder_function();
    
    //check if this is a return from odv-online and within the iframe then kill
    var isInIFrame = (window.location != window.parent.location);
    if(isInIFrame==true){
    	//console.log("in iframe")
    	var odvonline_iframe = $(parent.document).find('#odvonline_iframe');
    	window.parent.location = window.location.protocol + '//' + window.location.hostname;
    	odvonline_iframe.remove();
    }
    else {
    	//console.log("no iframe")
    }
    

    function set_window_height_core(){
    	var page_height = $(document).height();
	//console.log(page_height)
    	var copyright_div_height = $("#copyright_div").height();
    	var diff = Number(page_height)-Number(copyright_div_height);
    	//console.log("diff="+diff)
    	$('#copyright_div').offset({'top':diff});	
    }

    //not in extractor
    if (typeof(wsUri) == "undefined"){
    	$( window ).resize(function() {
    	    set_window_height_core();
    	});
    
    	set_window_height_core();
    }
    

    
    
    //top bar
    if (typeof(localStorage.getItem("hide_top_bar")) != "undefined"){
	if (localStorage.getItem("hide_top_bar") == "none"){
	    $('.webodv_top_nav').hide();
	} else {
	    $('.webodv_top_nav').show();
	}
    } else {
	//$('.webodv_top_nav').show();
    }
    //
    $('#hide_top_bar').on('click', function(){
	$('.webodv_top_nav').toggle();
	localStorage.setItem("hide_top_bar", $('.webodv_top_nav').css("display"));
	//console.log($('.webodv_top_nav').css("display"))
    });



    if (typeof(user_allowed_wsodv) != "undefined"){
	if (!user_allowed_wsodv){
	    //open modal
	    $('#wsodv_allowModal').modal('show');
	}
    }

    //tracking
    if (typeof(http_port) != "undefined"){
    	$.ajax({
	    type: "GET",
   	    url: window.location.protocol + '//' + window.location.hostname + http_port + '/webodv/stats/tracking',
	    func: "trackingSUCCESS",
	    cache: "false",
	    error:   function(data){
		console.log("tracking ERROR!!!!!!!!!!!!!");
            },
            success: function(data){
		console.log("tracking SUCCESS!!!!!!!!!!!!!");
	    }
	});
    }


    //localStorage.removeItem("cookies_help_not_show_again");
    //console.log("cookies")
    //console.log(localStorage.getItem("cookies_help_not_show_again"))

    if (typeof(localStorage.getItem("cookies_help_not_show_again")) != "undefined"){
	if (localStorage.getItem("cookies_help_not_show_again") === null){
	    $('#cookies_help_modal').modal('show');
	}
    } else {
	$('#cookies_help_modal').modal('show');
    }
    var cookies_help_not_show_again = $('#cookies_help_modal').find('#cookies_help_not_show_again');
    //console.log(cookies_help_not_show_again)
    $('#cookies_help_modal').on("shown.bs.modal", function(){
	$('#cookies_help_not_show_again').on('click', function(){
	    console.log("set cookies")
	    localStorage.setItem("cookies_help_not_show_again", true);
	});
    });


    $('#legal,.impressum_a').on('click', function(e){
	e.preventDefault();
	$('#legalmodal').modal('show');
    });

    $('#privacy,#privacy_from_cookie_modal,#privacy_from_register,.privacy_a').on('click', function(e){
	e.preventDefault();
	$('#privacymodal').modal('show');
    });

    $('.geotraces_link').on("click", function(e){
	e.preventDefault();
	//console.log("click")
	$('#'+$(this).attr("id")+'_modal').modal('show');	
    });


    //EMODnet
    $('#sextantURL_a').on('click', function(e){
	e.preventDefault();
	$('#sextant_modal').modal('show');
    });

    
    //-------------Contact-----------------------//
    var Contact = {'name':'','email':'','message':''};
    var sentSUCCESS = false;
    //this is used to "disable the send button"
    var mailSent = false;
    //
    //
    $('#contact_a').on('click', function(e){
	e.preventDefault();
	$('#contact_modal').modal('show');
    });



    $('#contact_button_send').on('click',function(){
	//check entries 
	//console.log("check contact form")
	Contact.name = $("#contact_name").val();
	Contact.email = $("#contact_email").val();
	Contact.message = $("#contact_message").val();
	
	var allow_send = 0;
	// \s is regex for whitespace and g is global, means match all
	//check if entries are empty or whitespace

	var error_text = "Please fill out this field.";
	
	$.each(Contact, function(i,e) {
	    //console.log(i + " " + e);
	    if (e.replace(/\s/g, '') == "") {
		$("#contact_" + i + "_error").text(error_text);
		allow_send = 0;
	    } else {
		$("#contact_" + i + "_error").text("");
		allow_send++;
	    }
	});

	//validate email very simple
	var patt = /\S+@\S+\.\S+/;
	if (Contact.email.replace(/\s/g, '') != "") {
	    if (patt.test(Contact.email) == false) {
		$("#contact_email_error").text("Please enter a valid E-Mail address.");
		allow_send = 0;
	    }
	}

	

	if (allow_send == 3) {
    	    //close dialog
    	    //Contact_dialog.close();

	    //mailSent = true: disables the Send button, is set to false on dialog hide
	    mailSent = true;

	    //no cancel during processing
	    processing = true;
	    
	    //disable send button, this is only visual not functional
	    $(this).prop("disabled",true).css('cursor','not-allowed');

	    //display sending info, incl. animation
	    $("#contact_sentSUCCESS").html('<i class="fa fa-refresh fa-spin fa-fw"></i> Please wait, we are now sending your message.');

	    //call AJAX
	    sendContact();	
	}
    });



    //this is the AJAX function, which sends the data
    function sendContact() {
	$.ajax({
	    type: "POST",
   	    url: window.location.protocol + '//' + window.location.hostname + http_port + '/webodv/get/contact',
	    data: Contact,
	    dataType: "json",
	    func: "showSUCCESS",
	    cache: "false",
	    error:   function(data){
		//console.log(data);
		sentSUCCESS = false;
		//console.log("receiveContact ERROR!!!!!!!!!!!!!");
            },
            success: function(data){
		//console.log(data);
		sentSUCCESS = true;
		//console.log("receiveContact SUCCESS!!!!!!!!!!!!!");
	    }
	}); 
    }

    $(document).ajaxComplete(function(e,xhr,settings){
	//display success message
	if (settings.func == "showSUCCESS") {
	    //allow cancel/done again
	    //processing = false;
	    //change Cancel button to "Done"
	    //$('.modal-dialog #Contact_cancel_button').text("Done");
	    
	    if (sentSUCCESS == true) {
		$("#contact_sentSUCCESS").text("Your email has been sent successfully.");
	    } else {
		$("#contact_sentSUCCESS").text("Sorry, your email could not been sent.");
	    }
	}

	if (settings.func == "create_treeview_from_folder_ajax"){
	    responseText = JSON.parse(xhr.responseText);
	    //console.log(responseText)
	    //console.log("hallo123");
	    $('#upload').prop('disabled',false).css('cursor','pointer');
	    $('#treeview_container').remove();
	    $('.create_treeview_from_folder').append(responseText);
	    //now init treeview
	    $('.hummingbird-treeview-converter').hide();
	    //debugger;
	    init_index_treeview();
	}
	//
	//profile
	if (settings.func == "showSUCCESSprofile") {
	    responseText = JSON.parse(xhr.responseText);
	    //console.log(responseText)
	    //update Profile_ini
	    Profile_init = responseText;
	    $("#sentSUCCESSprofile").text("Your profile has been updated successfully.");	    
	}
	//
	//delete account	
	if (settings.func == "showSUCCESSdelete") {
	    responseText = JSON.parse(xhr.responseText);
	    if (responseText == "true"){
	    	$("#delete_account_sentSUCCESS").html('Your webODV account has been deleted and we logged you out from this session.');
		setTimeout(function(){
		    window.location = window.location.protocol + '//' + window.location.hostname + http_port;
		}, 4000);
	    } else {
		$("#delete_account_sentSUCCESS").html('Sorry. your account could not be deleted.');
		$('#delete_account_yes_button').prop("disabled",false).css('cursor','pointer');
	    }
	}
	
    });

    //reset form, only message and error and infos
    $('#contact_modal').on('hidden.bs.modal',function(){
	sentSUCCESS = false;
	$.each(Contact, function(i,e) {
	    $("#contact_" + i + "_error").text("");	    
	});
	$("#contact_message").val("");	    
	$('#contact_button_send').prop("disabled",false).css('cursor','pointer');
	$("#contact_sentSUCCESS").text("");
	
    });



    //get user info first
    var Profile_mailSent = false;
    var Profile_init = {'institution':'','street':'','city':'','zipcode':'','country':''};
    var Profile = {'institution':'','street':'','city':'','zipcode':'','country':''};
    var Profile_Passw_Mode = "Profile";    // or "Passw"
    var sentSUCCESSprofile = false;
    var ProfileAnswer = {};

    Profile_init.institution = $('#institution').val();
    Profile_init.street = $('#street').val();
    Profile_init.city = $('#city').val();
    Profile_init.zipcode = $('#zipcode').val();
    Profile_init.country = $('#country').val();
    

    $('#profile_a').on('click', function(e){
	e.preventDefault();
	$('#profile_modal').modal('show');
    });

    $('#delete_account').on('click', function(e){
	e.preventDefault();
	$('#profile_modal').modal('hide');
	$('#delete_account_modal').modal('show');
    });


    $('#profile_button_send').on('click', function(){
	//remove text
	$("#sentSUCCESSprofile").html('');
	Profile.institution = $("#institution").val();
	Profile.street = $("#street").val();
	Profile.city = $("#city").val();
	Profile.zipcode = $("#zipcode").val();
	Profile.country = $("#country").val();
	
	var allow_send = 0;
	// \s is regex for whitespace and g is global, means match all
	//check if entries are empty or whitespace

	var error_text = "Please fill out this field.";
	var alphanumeric = "Please use only these characters and numbers (a-zA-Z_@-.0-9) and whitespace.";


	//check empty
	$.each(Profile, function(i,e) {
	    if (e.replace(/\s/g, '') == "") {
		$("#" + i + "_error").text(error_text);
		//$("#" + i + "_label").css('color','#a94442');
		allow_send = 0;
	    } else {
		//if not empty check alphanumeric
    		if (/[^a-zA-Z0-9\-\_\@\. ]/.test( e ) ) {
		    //if (true == false) {
    		    $("#" + i + "_error").text(alphanumeric);
    		    //$("#" + i + "_label").css('color','#a94442');
    		    allow_send = 0;
    		} else {
		    $("#" + i + "_error").text("");
		    //$("#" + i + "_label").css('color','#636b6f');
		    allow_send++;
		}
	    }
	});

	if (allow_send == 5) {
	    Profile_mailSent = true;
	    //no cancel during processing
	    processing = true;
	    //disable send button, this is only visual not functional
	    $('#profile_button_send').prop("disabled",true).css('cursor','not-allowed');
	    $('#delete_account').prop("disabled",true).css('cursor','not-allowed');	    


	    //display sending info, incl. animation
	    $("#sentSUCCESSprofile").html('<i class="fa fa-refresh fa-spin fa-fw"></i> Please wait, we are now updating your profile.');

	    //call AJAX
	    sendProfile();	
	} else {
	    $("#sentSUCCESSprofile").html('<span style="color:#a94442"><br>Your profile could not be updated, check errors above.</span>');
	}
    });



    //reset form, only message and error and infos
    $('#profile_modal').on('hidden.bs.modal',function(){
	$.each(Profile_init, function(i,e) {
	    $("#" + i + "_error").text("");
	    $("#" + i).val(e);
	});
	$('#profile_button_send').prop("disabled",false).css('cursor','pointer');
	$('#delete_account').prop("disabled",false).css('cursor','pointer');
	$("#sentSUCCESSprofile").text("");	
    });

    




    //this is the AJAX function, which sends the data
    function sendProfile() {
	//console.log("sendProfile")
	//console.log(window.location.protocol + '//' + window.location.hostname + http_port + '/profile')
	//console.log(Profile)
	$.ajax({
	    type: "POST",
   	    url: window.location.protocol + '//' + window.location.hostname + http_port + '/webodv/change/profile',
	    data: Profile,
	    dataType: "json",
	    func: "showSUCCESSprofile",
	    cache: "false",
	    error:   function(data){
		//console.log(data);
		sentSUCCESSprofile = false;
		console.log("receiveProfile ERROR!!!!!!!!!!!!!");
            },
            success: function(data){
		//console.log(data);
		sentSUCCESSprofile = true;
		console.log("receiveProfile SUCCESS!!!!!!!!!!!!!");
		//console.log(JSON.stringify(data));
		ProfileAnswer = data;
	    }
	}); 
    }
    
    var delete_passwd = "";
    $('#delete_account_yes_button').on('click', function(){
	delete_passwd = $('#delete_account_password').val();
	//check
	if (delete_passwd.replace(/\s/g, '') == "") {
	    $("#delete_account_password_error").text("Please fill out this field.");
	} else {
	    $("#delete_account_password_error").text("");
	    $(this).prop("disabled",true).css('cursor','not-allowed');
	    $("#delete_account_sentSUCCESS").html('<i class="fa fa-refresh fa-spin fa-fw"></i> Please wait, we are now deleting your profile.');
	    deleteAccount();
	}
	
    });

    $('#delete_account_modal').on('hidden.bs.modal',function(){
	$('#delete_account_yes_button').prop("disabled",false).css('cursor','pointer');
	$("#delete_account_sentSUCCESS").text('');
	$("#delete_account_password_error").text("");
	$('#delete_account_password').val("");
    });


    function deleteAccount() {
	$.ajax({
	    type: "POST",
   	    url: window.location.protocol + '//' + window.location.hostname + http_port + '/webodv/delete/account',
	    data: {'password':delete_passwd},
	    dataType: "json",
	    func: "showSUCCESSdelete",
	    cache: "false",
	    error:   function(data){
		//console.log(data);
		//console.log("delete ERROR!!!!!!!!!!!!!");
            },
            success: function(data){
		//console.log(data);
		//console.log("delete SUCCESS!!!!!!!!!!!!!");
		//console.log(JSON.stringify(data));
	    }
	}); 
    }
    
    

    //--------------------treeview---------------------//


    function init_index_treeview(){

	$.getScript(hummingbird_treeview_js_path).done(function(){

	    $.getScript(hummingbird_treeview_options_path).done(function(){

		//console.log("webodvcore load treeview")

		$(document).ready(function() {
		    //console.log("treeview_jump = " + treeview_jump)
		    
		    var List = {"id" : [], "dataid" : [], "text" : []};
		    
		    //init
		    $("#treeview").hummingbird();

		    //call webodvcoreawi
		    if (typeof add_download_delete_treeview === 'function') {
			add_download_delete_treeview();			
		    }
		    
		    //filter vre username
		    // if (typeof(project) != "undefined"){
		    // 	if (project == "vre"){
		    // 	    if (typeof(username) != "undefined"){
		    // 		$("#treeview").hummingbird("filter",{str:username, caseSensitive: true, box_disable:false, onlyEndNodes:false, filterChildren:false});
		    // 		//change name of node
		    // 		//$("ul[data-slide='" + current +"']");
		    // 		//var that_node = $("#treeview").find('input[data-id="'+username+'"]');
		    // 		//var that_label = that_node.parent("label");
		    // 		//that_label.text("workspace");
		    // 		//console.log(that_node)
		    // 		//console.log(that_label)
		    // 		//open workspace and files
		    // 		$("#treeview").hummingbird("expandNode",{attr:"data-id",name: '"' + username + '/files'  + '"',expandParents:true});
		    // 	    }
		    // 	}
		    // }
		    

		    //open base folder
		    //$("#treeview").hummingbird("expandNode",{sel:"id",vals: ["item_1"]});

		    //open GEOTRACES
		    //$("#treeview").hummingbird("expandNode",{attr:"data-id",name: "GEOTRACES",expandParents:false});
		    // $("#treeview").hummingbird("expandNode",{attr:"data-id",name: '"GEOTRACES"',expandParents:false});
		    // $("#treeview").hummingbird("expandNode",{attr:"data-id",name: '"GEOTRACES/CURRENT_VERSION"',expandParents:false});

		    //open EMODnet
		    // if (typeof(project) != "undefined"){
		    // 	if (project == "EMODnet Chemistry"){
		    // 	    $("#treeview").hummingbird("expandNode",{attr:"data-id",name: '"eutrophication"',expandParents:false});
		    // 	}
		    // }
		    
		    //open root folder always, i.e. disableToggle
		    //$("#treeview").hummingbird("disableToggle",{attr:"data-id",name: "EMODnet"});
		    //$("#treeview").hummingbird("disableToggle",{attr:"id",name: "item_2"});


		    //open treeview_jump
		    if (typeof(treeview_jump) != "undefined"){
			$("#treeview").hummingbird("expandNode",{sel:"data-id",vals: [treeview_jump]});
		    }

		    
		    // $('#exAll').on("click",function(){
		    // 	$("#treeview").hummingbird("expandAll");
		    // });
		    // $('#colAll').on("click",function(){
		    // 	$("#treeview").hummingbird("collapseAll");
		    // });


		    //search
		    $("#treeview").hummingbird("search",{treeview_container:"treeview_container",search_input:"search_input",search_output:"search_output",search_button:"search_button",scrollOffset:-250,onlyEndNodes:false});


		    //treeview event
		    var againChecking = false;
		    $("#treeview").on("CheckUncheckDone", function(){
			console.log("CheckUncheckDone")
			//------------------get ids and uncheck old selection---------------//
			//------------------to restrict selection to one item---------------//


			if (againChecking == true){
			    return;
			}
			if (List.id != "") {
			    againChecking = true;	 
			    $.each(List.id, function(i,e) {
				//console.log(e)
				$("#treeview").hummingbird("uncheckNode",{attr:"id",name: '"' + e + '"',collapseChildren:false});
			    });
			    againChecking = false;
			}
			//------------------------------------------------------------------//
			//------------------------------------------------------------------//
			List = {"id" : [], "dataid" : [], "text" : []};
			$("#treeview").hummingbird("getChecked",{list:List,onlyEndNodes:true});
			//console.log(List.dataid)
			//send user to next url
			var file_path_name = String(List.dataid).replace(/\//g,'>');
			//console.log("send user to:")
			//console.log(continue_url + '/' + file_path_name.replace('.odv',''))

			//------redirect to services-------//
			// console.log("redirect to service")
			// console.log(continue_url + '/' + file_path_name.replace('.odv',''))
			window.location = continue_url + '/' + file_path_name.replace('.odv','');



			//window.location = '/' + file_path_name.replace('.odv','');
			
			// if (workspace){
			//     if (EMODnet){
			// 	//EMODnet
			// 	$.blockUI({ message: '<h1>Loading ...</h1>' });
			// 	//window.location = continue_url + '/' + String(List.text).replace('.odv','').trim() + '/service/DataExtraction';
			// 	//console.log(String(List.dataid).replace(/\//g,'>'))
			// 	var file_path_name = 'EMODnet>'+String(List.dataid).replace(/\//g,'>');
			// 	window.location = continue_url + '/' + file_path_name.replace('.odv','') + '/service/DataExtraction';
			//     } else {
			// 	window.location = continue_url + '/' + String(List.text).replace('.odv','').trim();
			//     }
			//     if (typeof(project) != "undefined"){
			// 	if (project == "vre"){
			// 	    //var file_path_name = String(List.dataid).replace(username,'workspace');
			// 	    var file_path_name = String(List.dataid).replace(/\//g,'>');
			// 	    window.location = continue_url + '/' + file_path_name.replace('.odv','');
			// 	}
			//     }
			// } else {
			//     if (EMODnet){
			//     //EMODnet
			// 	$.blockUI({ message: '<h1>Loading ...</h1>' });
			// 	window.location = continue_url + '/' + List.dataid + '/service/DataExtraction';		
			//     } else {
			// 	window.location = continue_url + '/' + List.dataid;
			//     }
			// }
			
			//set link to service
			//$('#service_link').attr('href',continue_url + '?file_num=' + List.dataid);
			//activate/deactivate button
			// if (List.id.length == 1){
			//     $('#continue').css({'cursor':'pointer'}).removeClass('disabled');
			// } else {
			//     $('#continue').css({'cursor':'not-allowed'}).addClass('disabled');
			// }
			//send event so that the List can be retrieved
			//from the iframe, also in case of same-origin-policy
			//parent.postMessage(List,"*");
		    });


		    //filter
		    //if (treeview_controls.filter){
		    //console.log('filter=' + treeview_controls.filter)
		    //------------------filtering---------------------------------------//
		    //$("#treeview").hummingbird("filter",{str:treeview_controls.filter,box_disable:false,caseSensitive:treeview_controls.caseSensitive,onlyEndNodes:false,filterChildren:false});
		    //------------------filtering---------------------------------------//
		    //}



		    
		    // collapse and expand buttons
		    // $('#collapse').on('click', function(){
		    // 	$("#treeview").hummingbird("collapseAll");
		    // });

		    // $('#expand').on('click', function(){
		    // 	$("#treeview").hummingbird("expandAll");
		    // });
		    
		    //fire event that js is done
		    //parent.postMessage('init_done',"*");

		    //--------------------treeview---------------------//
		    //$('.hummingbird-treeview-converter').show();
		    $('.loading_snippet').hide();
		    //check if treeview is empty, i.e. no .odv files
		    if ($('.hummingbird-base').html() == ""){			
			$('.create_treeview_from_folder').append('<div class="loading_snippet" style="width:360px;height:120px"><h1><i class="fa fa-cog fa-spin fa-fw"></i> No .odv files found ! </h1></div>');	
		    }
		});
	    });
	});
    }
    

    //$('#treeview_card').unblock();    
    $('#treeview_card_body').show();


    $('.start_service_button').on('click', function(){
	$.blockUI({ message: '<h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1>' });
    });

    //upload download delete
    $('#delete').on('click',function(){
	//console.log(List.text)
    });
    $('#upload').on('click',function(){
	//$(this).prop('disabled',true);
        $('#uploadModal2').modal('show');
    });

    //if first upload modal is finished open the second

    $('#upload_modal_button').on('click', function(){
	$('#upload_form').submit();
    });

    $('#upload_form').on('submit', function(e){
	// $.blockUI({ message: '<h1>Uploading ...</h1>'+
	// 	  });
	//$('.create_treeview_from_folder').append(loading_snippet);
	//console.log("upload form")
	e.preventDefault();
	var formData = new FormData($(this)[0]);
	$.ajax({
            type: "POST",
            url: $('#upload_form').attr('action'),
            data: formData,
    	    cache: "false",
	    contentType: false,
	    enctype: 'multipart/form-data',
	    processData: false,
	    func: "uploading_done",
	    error:   function(data){
    		//console.log("ERROR");
    	    },
    	    success: function(data){
    		//console.log("SUCCESS");
    	    },
        });	
    });


    $(".custom-file-input").on("change", function() {
	//console.log($(this)[0].files[0].name)
	//var fileName = $(this)[0].files[0].webkitRelativePath.split("/");
	if (typeof($(this)[0].files[0].name) != "undefined"){
	    var fileName = $(this)[0].files[0].name;
	    // var fileName = $(this).val().split("\\").pop();
	    //$(this).siblings(".custom-file-label").addClass("selected").html(fileName[0]);
	    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	}
    });

    $(document).ajaxComplete(function(e,xhr,settings){
	if (settings.func=="uploading_done") {
	    //$.unblockUI();
	    $('.loading_snippet').hide();
	    //open next modal
	    $('#uploadModal2').modal('show');
	}
	if (settings.func=="uploading_done2") {
	    $.unblockUI();
	    //$('.loading_snippet').hide();
	    $('.progress').hide();
	    //reload page to update treeview
	    //location.reload();
	    //update treeview
	    create_treeview_from_folder_function();
	}
    });


    $('#upload_modal_button2').on('click', function(){
	$('#upload_form2').submit();
    });

    $('#upload_form2').on('submit', function(e){
	$.blockUI({ message: '<h1 id="uploading_anim"><i class="fa fa-refresh fa-spin fa-fw"></i> Uploading </h1>'+progress_snippet });
	//$('.create_treeview_from_folder').append(loading_snippet);
	// $('.loading_snippet').show();
	// $('.progress').show();
	//upload_progress();
	

	//console.log("upload form")
	e.preventDefault();
	//console.log($('#fileToUpload2'))
	var files = $('#fileToUpload2');
	var files = files[0].files;
	var formData = new FormData();
	$.each(files,function(i,e){
	    //console.log(i)
	    //console.log(e.webkitRelativePath)
	    formData.append('fileToUpload[]',e,e.name);
	    formData.append('filePaths[]',e.webkitRelativePath);
	});

	$.ajax({
	    xhr: function() {
                var xhr = new window.XMLHttpRequest();         
                xhr.upload.addEventListener("progress", function(element) {
		    //console.log(element)
		    if (element.lengthComputable) {
                        var percentComplete = ((element.loaded / element.total) * 100);
                        // $("#file-progress-bar").width(percentComplete + '%');
                        // $("#file-progress-bar").html(percentComplete+'%');
			//progress bar
			//var width = 50;
			$("#progress").css("width",Math.round(percentComplete).toString() + '%');
			$("#progress").text(Math.round(percentComplete).toString() + '%');
			if (percentComplete >= 100){
			    //console.log("100")
			    $('#uploading_anim').html('<i class="fa fa-refresh fa-spin fa-fw"></i> Processing, please wait a few seconds!');
			}
		    }
                }, false);
                return xhr;
            },
            type: "POST",
            url: $('#upload_form2').attr('action'),
            data: formData,
    	    cache: "false",
	    contentType: false,
	    enctype: 'multipart/form-data',
	    processData: false,
	    func: "uploading_done2",
	    error:   function(data){
    		//console.log("ERROR");
    	    },
    	    success: function(data){
    		//console.log("SUCCESS");
    	    },
	})
    });
		



    //register
    //on dropdown change put the value into the
    //hidden country input field in register.blade.php
    $('#select_country').on('changed.bs.select', function(){
	var title = $(this).siblings("button").prop('title');
	if (title == "Nothing selected"){
	    title = "";
	}
	$('#register_country').val(title);
    });

    //profile
    $('#select_country_profile').on('changed.bs.select', function(){
	var title = $(this).siblings("button").prop('title');
	if (title == "Nothing selected"){
	    title = "";
	}	
	$('#country').val(title);
    });



    //------------pager-------------------------------------------------//
    //init
    $('#pager_large_'+pager_num+',#pager_small_'+pager_num).addClass('active');

    //---function
    //var number_of_pages = 4; //5;
    activate_pager = function(){
	$(".page-item").removeClass('active');
	if (pager_num < 1){
	    pager_num=1;
	}
	if (pager_num > number_of_pages){
	    pager_num=number_of_pages;
	}
	$('#pager_large_'+pager_num.toString()+',#pager_small_'+pager_num.toString()).addClass('active');
	//
	// new treeview
	create_treeview_from_folder_function();

	if (pager_num > 1){
	    $('#upload_div').show();
	} else {
	    $('#upload_div').hide();
	}
	localStorage.setItem("pager_num", pager_num);
    }


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

    


    //private workspace
    $(".priv_workspace").on("click", function(){

	var name = $(this).parent("td").text();
	console.log(name)
	
	$.ajax({
	    type: "POST",
	    url: remove_project_url,
	    data: {"name":name},
	    dataType: "json",		
    	    cache: "false",
	    func: "remove_project_ajax_done",
	    error:   function(data){
    		//console.log("ERROR");
    	    },
    	    success: function(data){
    		//console.log("SUCCESS");
    	    },
	});	

    });


    $(document).ajaxComplete(function(e,xhr,settings){
	if (settings.func == "remove_project_ajax_done"){
	    responseText = JSON.parse(xhr.responseText);
	    //console.log(responseText)
	    location.href = responseText.add_project_url;
	}
    });

});
