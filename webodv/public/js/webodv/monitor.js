$(document).ready(function() {
    
    //csrf ajax
    $.ajaxSetup({
	headers: {
    	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
    });


    //only if we are at monitor url                                                                                                                                       
    if (typeof(monitor_process_url) != "undefined"){
	
	$('.py-4').css('background-color','#89a2b0');

	//EMODnet
	$("#marine_id_login_a").hide();


	//global vars
	var xdata = [];
	var monitor_date = "";
	var new_hour = "00:00";
	var new_date = "";
	var monitor_date_tmp = [];
	
	function monitor_process(){
	    $.ajax({
		type: "POST",
		url: monitor_process_url,
		//data: {"real_time_from":"2021-06-04 09:00:00"},
		data: {"real_time_from":monitor_date},
		dataType: "json",	    
    		cache: "false",
		func: "monitor_process_url_done",
		error:   function(data){
    		    //console.log("ERROR");
    		},
    		success: function(data){
    		    //console.log("SUCCESS");
    		},
	    });
	}

	//fire the function
	setInterval(function(){
    	    monitor_process();
	}, 30000);


	//init datepicker
	//get date from today 00:01
	var today = new Date();
	var init_date = new Date(today.getFullYear(), today.getMonth(), today.getDate());
	//console.log("init_date")
	//console.log(init_date)
	if ((today.getMonth()+1) <10){
	    var addm = "0";
	} else {
	    var addm = "";
	}
	if (today.getDate() <10){
	    var addd = "0";
	} else {
	    var addd = "";
	}

	
	var picker_init = addm + (today.getMonth()+1).toString() + '/' + addd + today.getDate().toString() + '/' + today.getFullYear().toString();
	//console.log("picker_init")
	//console.log(picker_init)
	monitor_date = today.getFullYear().toString() + '-' + addm + (today.getMonth()+1).toString() + '-' + addd + today.getDate().toString() + ' ' + new_hour + ':00';
	//init
	monitor_process();
	//console.log("monitor_date")
	//console.log(monitor_date)
	monitor_date_tmp  = picker_init.split("/");
	//console.log("monitor_date_tmp="+monitor_date_tmp[0]+monitor_date_tmp[1]+monitor_date_tmp[2])
	
	$('#date1').datepicker({
    	    uiLibrary: 'bootstrap4',
    	    showOnFocus: false,
    	    showRightIcon: true,
    	    value: picker_init,
    	    // minDate: default_dates[0],
    	    // maxDate: default_dates[1],
    	    close: function (e) {
    		//setdate();
    		//send_wsODV_msg('get_canvas_window');
            },
    	    change: function (e) {
    		new_date = $(this).datepicker().value();
		//console.log("new_date")
		//console.log(new_date)
		monitor_date_tmp  = new_date.split("/");
		monitor_date = monitor_date_tmp[2].toString() + '-' + monitor_date_tmp[0].toString() + '-' + monitor_date_tmp[1].toString() + ' ' + new_hour + ':00';
		//console.log("monitor_date = " + monitor_date)
		monitor_process();
            }
	});

	$('.input-group-append').css('background-color','white');
	

	$('#hour').on('changed.bs.select', function(){
	    new_hour = $("#hour option:selected").text();
	    //console.log("new_hour="+new_hour);
	    monitor_date = monitor_date_tmp[2].toString() + '-' + monitor_date_tmp[0].toString() + '-' + monitor_date_tmp[1].toString() + ' ' + new_hour + ':00';	
	    //console.log("monitor_date = " + monitor_date)
	    monitor_process();
	    //
	});
	//point size


	
	$(document).ajaxComplete(function(e,xhr,settings){
	    if (settings.func == "monitor_process_url_done") {
		responseText = JSON.parse(xhr.responseText);
		//console.log(responseText)
		//create array
		xdata = {"xdate":[],"xcpu":[],"xmem":[],"odvws_instances":[]};
		$.each(responseText, function(i,e){
		    xdata.xdate.push(e.created_at);
		    xdata.xcpu.push(e.cpu);
		    xdata.xmem.push(e.mem);
		    xdata.odvws_instances.push(e.odvws_instances);
		});
		//console.log(xdata)
		//plot////////////////////////////////////////
		cpu_plot = document.getElementById('cpu');
		Plotly.newPlot
		(
		    cpu_plot,
		    [
			{
			    x: xdata.xdate,
			    y: xdata.xcpu,
			}
		    ],{
			margin: { t: 0 },
		    }
		)
		////////////////////////////////////////

		//plot////////////////////////////////////////
		mem_plot = document.getElementById('mem');
		Plotly.newPlot
		(
		    mem_plot,
		    [
			{
			    x: xdata.xdate,
			    y: xdata.xmem,
			}
		    ],{
			margin: { t: 0 },
		    }
		)
		////////////////////////////////////////

		//plot////////////////////////////////////////
		odvws_instances_plot = document.getElementById('odvws_instances');
		Plotly.newPlot
		(
		    odvws_instances_plot,
		    [
			{
			    x: xdata.xdate,
			    y: xdata.odvws_instances,
			}
		    ],{
			margin: { t: 0 },
		    }
		)
		////////////////////////////////////////
		
		
	    }
	});


    }
    
    
    
})
