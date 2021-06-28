$(document).ready(function() {


    console.log("webodvawiimport.js")

    //csrf ajax
    $.ajaxSetup({
	headers: {
    	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
    });

    
   
    if (typeof(AJAXURL) !== "undefined"){
	console.log("AJAXURL="+AJAXURL)
    } else {
	console.log("no AJAXURL")
    }


    if (typeof(ImportDatasetURL) !== "undefined"){
	console.log("ImportDatasetURL="+ImportDatasetURL)

    	$.ajax({
    	    type: "POST",
	    url: AJAXURL,
	    data: {"Dataset":ImportDatasetURL},
	    dataType: "json",
    	    cache: "false",
	    func: "DatasetImport",
    	    error:   function(data){
    		//console.log("ERROR");
    	    },
    	    success: function(data){
    		//console.log("SUCCESS");
    	    },
	});


    } else {
	console.log("no ImportDatasetURL")
    }
    


    $(document).ajaxComplete(function(e,xhr,settings){
	if (settings.func=="DatasetImport") {
	    $('.loading_snippet').hide();
	    responseText = JSON.parse(xhr.responseText);
	    console.log(responseText)
	    if (responseText.success == true) {
		window.location = responseText.URL;
	    }
	}
    });
    
    


});
