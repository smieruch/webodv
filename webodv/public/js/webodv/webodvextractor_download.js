$(document).ready(function() {

        //download
    var allow_download = false;
    $('.myDownloadButton').on('click',function(){
	//console.log("download")
	//check if stations and variables have been selected

	var num_stations_download = Number($('.num_stations').text());
	var num_variables_download = Number($('.num_variables').text());


	if (num_stations_download>0 && num_variables_download>0){
	    allow_download = true;
	    var download_modal_text = "We are preparing the download in the background. As soon as the data are ready we will inform you by email.";
	}

	if (num_stations_download==0 && num_variables_download>0){
	    allow_download = false;
	    var download_modal_text = "Data extraction not possible. Please go to page <b>1. Select stations</b> and select at least one station.";
	}

	if (num_stations_download>0 && num_variables_download==0){
	    allow_download = false;
	    var download_modal_text = "Data extraction not possible. Please go to page <b>2. Select variables</b> and select at least one variable.";
	}

	if (num_stations_download==0 && num_variables_download==0){
	    allow_download = false;
	    var download_modal_text = "Data extraction not possible. Please go to page <b>1. Select stations</b> and select at least one station and go to page <b>2. Select variables</b> and select at least one variable.";
	}
	


	//console.log("direct_downloads="+direct_downloads)
	//show pop-up if download is not possible
	var download_modal_body = $('#download_help_modal').find('div.modal-body').html(download_modal_text);
	
	if (!allow_download){
	    $('#download_help_modal').modal('show');
	}


	if (allow_download){
	    if (!direct_downloads){
		$('#download_help_modal').modal('show');
	    } else {
		$.blockUI({message: '<h1><i class="fa fa-refresh fa-spin fa-fw"></i> Please wait, we are preparing your data download ...</h1>'});
	    }

	    var file_format_tmp = $(this).attr('id').split('_');
	    var file_format = file_format_tmp[1];
	    //console.log("file_format= "+file_format)

	    //console.log(localStorage.getItem(storage_name))

	    webodv_status[storage_name]['file_format'] = file_format;
	    webodv_status[storage_name]['datasetname'] = datasetname;

	    if (typeof(webodv_status[storage_name]['date1']) == "undefined"){
		webodv_status[storage_name]['date1'] = '01/01/-99999';
		webodv_status[storage_name]['date2'] = '12/31/99999';
	    }

	    
	    //test
	    //webodv_status[storage_name]['coords'][0] = 'hallo';
	    //console.log(webodv_status[storage_name])
	    
	    var download_data = (webodv_status[storage_name]);
	    //console.log(download_data)
	    
	    $.ajax({
    		type: "POST",
    		url: window.location.protocol + '//' + window.location.hostname + ':' + window.location.port + '/webodv/data/' + datasetname + '/service/Download',
		data: download_data,
		dataType: "json",
    		cache: "false",
		func: "download_ajax",
    		error:   function(data){
    		    console.log("ERROR");
    		},
    		success: function(data){
    		    console.log("SUCCESS");
    		},
	    });



	    $(document).ajaxComplete(function(e,xhr,settings){
		if (settings.func=="download_ajax") {
		    //console.log("download_finished")
		    $.unblockUI();
		    //trigger download
		    responseText = JSON.parse(xhr.responseText);
		    //console.log(responseText)
		    
		    // allow_download = false;
		    // accept_agreement = false;		
		    
		    //unbind the click from the button
		    //otherwise every click event will be added
		    //and multiple downloads will be tried
		    //geotraces_start_download_button.unbind( "click" );
		    
		    $('#geotr_download').attr("href",responseText.output_file_url).attr("download",responseText.file_name);
		    $('#start_download_input').trigger("click");
		    
		}
	    });



	}
	

	
    });


	






});
