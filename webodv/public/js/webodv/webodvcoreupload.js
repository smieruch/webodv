$(document).ready(function() {

    var upload_file_size = 0;
    var file_name = "";
    var delete_path_name = "";
    
    window.add_download_delete_treeview = function(){
	//console.log("webodvcoreupload")
	//console.log(pager_num)
	var lis = $('.hummingbird-end-node').parent('label').parent('li');
	$.each(lis, function(i,e){
	    //console.log(pager_num)
	    if (pager_num != disable_treeview_delete){
		$(this).append(' <i class="fa fa-download"></i> <i class="fa fa-trash"></i>');
	    } else {
		//$(this).append(' <i class="fa fa-download"></i>');
		var dummy = 1;
	    }
	});

	//download
	$('.fa-download').on('click', function(){
	    console.log("download")
	    download_collection($(this));
	});

	//delete
	//only if we are in a private treeview
	if (pager_num > 1){
	    $('.fa-trash').on('click', function(){
		console.log("trash")
		//console.log($(this).siblings('label'))
		$('#delete_collection').text($(this).siblings('label').text());
		delete_path_name = $(this).siblings('label').children('input').attr('data-id');
		$('#trashModal').modal('show');
	    });
	}	
    }

    $('#delete_collection_yes').on("click",function(){

	//console.log("delete")
	//var delete_file = $('#delete_collection').text();
	//console.log(delete_file)
	

	//$('.loading_snippet').show();

	//remove entry from treeview	
	$("#treeview").hummingbird("removeNode",{attr:"data-id",name: '"'+delete_path_name+'"'});
	//init again
	//to remove empty folders
	//remove_childless_tree_nodes
	var groups = $("#treeview").find('input:checkbox:not(".hummingbird-end-node")');
	$.each(groups,function(i,e){
	    var num_end_nodes = $(this).parent("label").parent("li").find('input:checkbox.hummingbird-end-node').length;
	    //console.log("remove_childless_tree_nodes")
	    //console.log($(this))
	    //console.log(num_end_nodes)
	    if (num_end_nodes == 0){
		// $(this).parent("label").parent("li").parent("ul").parent("label").parent("li").remove();
		// $(this).parent("label").parent("li").parent("ul").parent("label").remove();
		// $(this).parent("label").parent("li").parent("ul").remove();
		$(this).parent("label").parent("li").remove();
		console.log($(this))
	    }
	});
	//$("#treeview").hummingbird("skipCheckUncheckDone");


	
	$.ajax({
            type: "POST",
	    url: window.location.protocol + '//' + window.location.hostname + http_port + '/webodv/delete_collection',
            data: {'filepathname':delete_path_name},
    	    cache: "false",
	    func: "delete_collection",
	    error:   function(data){
    		//console.log("ERROR");
    	    },
    	    success: function(data){
    		//console.log("SUCCESS");
    	    },
	})

    });


    $(document).ajaxComplete(function(e,xhr,settings){
	if (settings.func=="delete_collection") {
	    //create_treeview_from_folder_function();
	}
	if (settings.func=="download_collection") {
	    responseText = JSON.parse(xhr.responseText);
	    //console.log(responseText)
	    //$('.loading_snippet').hide();
	    $.unblockUI();
	    //create / start download
	    $('#download_collection').attr('href',responseText.url);
	    $('#start_download').trigger('click');
	    

	    


	    // $('#download_image_a_page'+pager_num.toString()).attr("href",evt.target.result).attr("download",download_image_name);
	    // $('#download_image_button_page'+pager_num.toString()).trigger("click");

	}
    });


    function download_collection(download_icon){

	//console.log(download_icon.siblings('label').text())
	var filepathname = download_icon.siblings('label').children('input').attr('data-id');
	//console.log(filepathname)

	//$('.loading_snippet').show();
	$.blockUI({ message: '<h1><i class="fa fa-refresh fa-spin fa-fw"></i> Processing download, please wait. </h1>' });
	
	$.ajax({
            type: "POST",
	    url: window.location.protocol + '//' + window.location.hostname + http_port + '/webodv/download_collection',
            data: {'filepathname':filepathname},
    	    cache: "false",
	    func: "download_collection",
	    error:   function(data){
    		//console.log("ERROR");
    	    },
    	    success: function(data){
    		//console.log("SUCCESS");
    	    },
	})


    }
    

    //localStorage.removeItem("awiModalneveragain");
			 
    if (localStorage.getItem("awiModalneveragain") === null){
	$('#awiModal').modal('show');
    }
    var awiModalneveragain = $('#awiModal').find('#awiModalneveragain');
    awiModalneveragain.on('click', function(){
	localStorage.setItem("awiModalneveragain", true);
    });


    

});
