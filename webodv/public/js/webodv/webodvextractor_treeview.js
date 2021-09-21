
var p01_count = 0;

var l04_terms = {"water": true,"sediment": true,"biota":true};
var this_collection_is = "";

var eutro_hidden_vars = [];

var removed_top_level_nodes = [];

if (dev_pass_server == "1"){
    //dev mode true
    var remove_top_level_nodes = false;
} else {
    //dev mode false
    var remove_top_level_nodes = true;
}




$(document).ready(function() {


    //check which kind of collection we have
    $.each(l04_terms, function(i,e){
	// console.log(i)
	// console.log(e)
	if (collectionName.includes(i)){
	    this_collection_is = i;
	    return true;
	}
    });

    //console.log("this_collection_is")
    //console.log(this_collection_is)

    //treeview worker
    var treeview_worker;
    var treeview_worker_path = window.location.protocol + '//' + window.location.hostname + http_port + '/js/webodv/';

    
    function startWorker() {
    	if (typeof(Worker) !== "undefined") {
    	    if (typeof(treeview_worker) == "undefined") {
    		treeview_worker = new Worker(treeview_worker_path+"treeview_worker.js");
    	    }
    	    treeview_worker.onmessage = function(event) {
    		//console.log(event.data);
    	    };
    	} else {
    	    //console.log("Sorry! No Web Worker support.")
    	}
    }

    function stopWorker() {
    	treeview_worker.terminate();
    	treeview_worker = undefined;
    }
    
    
    

    if (contaminants == true && project == "EMODnet Chemistry"){			    
	$('#set_x_var,#set_y_var').on('rendered.bs.select',function(){
	    //delete tooltip of button from variable selection if EMODnet contaminants
	    //console.log("set_x_var")
	    //console.log($(this).next("button").removeAttr("title"))
	    $(this).next("button").removeAttr("title");
	});
    }


    //------------Treeview data available and EMODnet contaminants----------------//    

    //go through only once
    window.treeview_emodnet_contaminants_connect = function(){

	treeview_emodnet_contaminants_connect_go = false;
	
	//console.log("treeview_emodnet_contaminants_connect start")

	//debugger;

	
	if (localStorage.getItem(storage_name) !== null){
	    
	    Xstatus = JSON.parse(localStorage.getItem(storage_name));
	    
	    //get pO1 names
	    if (contaminants == true && project == "EMODnet Chemistry"){			    
		p01_count = 0;
		

		//create always new !!!!!!!!!
		//if (typeof(Xstatus.p01_list) != "undefined"){
		    //if (false){
		    //console.log("already init!")
		//    p01_list = Xstatus.p01_list;
		//    p01_num = Xstatus.p01_num;
		//} else {
		    //console.log("init now!")
		    $.each(W_get_collection_information.datavar_comments, function(i,e){
			//console.log("i="+i+", e="+e)
			//console.log(e.datavar_comments)
			//
			var p01_code = e.match(/(?<=P01::).*(?= SDN:P06)/g);
			//
			var treeview_tool_tip = $(treeview_vars_id).find('input[data-id=' + p01_code[0] +  ']').parent("label").attr("data-original-title");
			//
			p01_list[p01_code[0]] = {"p01" : p01_code[0], "num":i+1, "avail":false, "tooltip":treeview_tool_tip};
			p01_num[i+1] = p01_code;
			//p01_count++;
			//console.log(p01_list[i])
			//console.log(p01_code[0])
			//console.log(typeof(p01_code[0]))
			//console.log(p01_list[p01_code[0]])
		    });
		    //console.log(p01_list["COREDIST"])
		    //
		    //console.log("length= "+p01_count)
		    //$('.num_variables_total').text(p01_count);
		    webodv_status[storage_name]['p01_num'] = p01_num;
		    webodv_status[storage_name]['p01_list'] = p01_list;
		    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));	    
		//}


		//remove top level nodes
		if (remove_top_level_nodes){
		    //get top level nodes
		    var top_level_nodes = $(treeview_vars_id).children('li').children('label'); //.attr('data-vocab');
		    // console.log("top_level_nodes")
		    // console.log(top_level_nodes)
		    $.each(top_level_nodes, function(i,e){
			//check if text includes this_collection_is
			//remove if not
			//console.log("top level node")
			//console.log($(this).text())

			var top_level_text = $(this).text();
			
			var l04_includes = false;
			$.each(l04_terms, function(i,e){
			    //console.log($(this).text() + " includes " + i)
			    //this is either water, sediment or biota
			    if (top_level_text.includes(i)){
				//console.log("includes")
				l04_includes = true;
			    }
			});
			//console.log("l04_includes")
			//console.log(l04_includes)

			//thus no variables are included that are below water, sediment or biota
			//whatever the current collection is
			
			if (l04_includes == false){
			    //find all p01s
			    //which are outside the l04_terms
			    var p01s_below = $(this).parent('li').find('input:checkbox.hummingbird-end-node');
			    //console.log("look into " + $(this).text())
			    //console.log(p01s_below)
			    $.each(p01s_below,function(i,e){
				if (typeof(p01_list[$(this).attr('data-id')]) != "undefined"){
				    removed_top_level_nodes.push(p01_list[$(this).attr('data-id')].num);
				}
			    });
			}

			if ($(this).text().includes(this_collection_is) == false){
			    $(this).parent('li').remove();
			}
		    });
		    //console.log("removed_top_level_nodes")
		    //console.log(removed_top_level_nodes)
		} else {
		    //dev mode
		    //change bg color
		    $('.bg-center').css("background-color","#97d097").children("div.emodnet-func-dark-blue").prepend('<div><h3>Development mode</h3></div>');
		    //get labels
		    var treeview_labels = $(treeview_vars_id).find('label');
		    $.each(treeview_labels, function(i,e){
		    	//var this_text = $(this).text();
		    	//console.log(this_text)
		    	var this_data_vocab = $(this).attr("data-vocab");
		    	//var this_html = $(this).html();
		    	//var split_this_html = this_html.split('>');
		    	//var new_html = split_this_html[0] + '>' + split_this_html[1] + this_data_vocab;
		    	//$(this).html(new_html);

		    	var this_input = $(this).children('input');
			//this_input.after('<span style="color:red;">'+this_data_vocab+'</span>');
			$(this).after('<span style="color:red;">'+this_data_vocab+'</span>');
			//var new_name = this_text + this_data_vocab;
		    	//$(this).text(this_text + this_data_vocab);
		    });
		    // //initialise again
		    $(treeview_vars_id).hummingbird();
		    //init_outvar_treeview();
		}
		
		
		//console.log("remove non existing nodes start")
		//var treeview_labels = $(treeview_vars_id).find('label');
		var end_nodes = $(treeview_vars_id).find('input:checkbox.hummingbird-end-node');
		$.each(end_nodes,function(i,e){
		    var end_nodes_data_id = $(this).attr('data-id');
		    //console.log(end_nodes_data_id)
		    var end_nodes_li = $(this).parent("label").parent("li");
		    //console.log(end_nodes_li)
		    //for contaminants go through the tree
		    //and remove all non-existing p01s
		    if (contaminants == true && project == "EMODnet Chemistry"){			    
			//console.log(output_vars_list)
			//console.log(output_var_data_id)
			//console.log(p01_list[output_var_data_id])
			//$("#treeview_vars").hummingbird("removeNode",{attr:"data-id",name: p01_list[i]});
			if (typeof(p01_list[end_nodes_data_id]) == "undefined"){
			    //console.log("remove " + output_var_data_id)
			    end_nodes_li.remove();
			    //$(treeview_vars_id).hummingbird("removeNode",{attr:"data-id",name: p01_list[end_nodes_data_id]});
			} else {
		     	    //console.log(output_var_data_id + " exists")
			    p01_count++;
			}
		    }
		});
		//console.log("remove non existing nodes end")
		//console.log("length= "+p01_count)
		$('.num_variables_total').text(p01_count);
		
		//webodv_status[storage_name]['num_variables_total'] = p01_count;
		localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));	    

		//console.log("remove_childless_tree_nodes start")
		remove_childless_tree_nodes();
		//console.log("remove_childless_tree_nodes end")

		//trigger
		//console.log("trigger now")
		if (typeof(Xstatus.OutVar) == "undefined"){		    
		    treeview_default();
		}
		//console.log("100 trigger on init")
		$(treeview_vars_id).trigger("CheckUncheckDone");

		//save tree
		//emodnet_contaminants_tree = $(treeview_vars_id).parent("div").html();
		//initialise again
		//init_outvar_treeview();

		//open top level
		var final_top_level_node = $(treeview_vars_id).children('li').children('label').children('input').attr('id');
		// console.log("final_top_level_node")
		// console.log(final_top_level_node)
		$(treeview_vars_id).hummingbird("expandNode",{attr:'id',name:final_top_level_node});
		
		
	    }
	}
	// console.log("treeview_emodnet_contaminants_connect end")
	// console.log("set_window_height in")
	set_window_height();
    };






    window.treeview_data_available = function(){

	//console.log("treeview_data_available")

	//debugger;
	
	//disable output vars which are not available
	var n = 0;
	var ns = "";
	var ms = "";

	var OutVar = webodv_status[storage_name]['OutVar'];

	
	//if no vars have been selected in step 2, show all for visualisation
	//if some vars have been selected then show only those

	//console.log(webodv_status[storage_name]['OutVar'])
	//console.log(webodv_status[storage_name]['OutVar'].length)
	//console.log(typeof(webodv_status[storage_name]['OutVar'].length))
	if (typeof(OutVar) != "undefined"){
	    if (OutVar.length == 0){
		use_all_vars = true;
	    } else {
		use_all_vars = false;
	    }
	} else {
	    use_all_vars = true;
	}


	//console.log("showNodes")
	//show all and change hidden to checkbox

	//console.log("restore tree, i.e. show all hidden")
	//console.log("restore tree, i.e. or enable all?")

	//do it per default and enable all
	//if (contaminants == true && project == "EMODnet Chemistry"){
	    //show all hidden
	    // var all_checkboxes = $(treeview_vars_id).find("input[type='hidden']");
	    // all_checkboxes.attr("type","checkbox"); 
	    // $(treeview_vars_id).find('li:hidden').show();
	    //init
	    //$(treeview_vars_id).hummingbird();
	    //hide_childless_tree_nodes()
	    //enable all disabled
	    var all_checkboxes = $(treeview_vars_id).find("input:checkbox:disabled");
	    all_checkboxes.prop("indeterminate",false).prop("disabled",false).css({"cursor":"pointer"}).parent("label").css({"cursor":"pointer","color":"rgb(1,46,88)"});
	    //console.log(all_checkboxes)
	//}


		//if EMODnet remove tree and create new
	//better create new method named hide and show
	// if (contaminants == true && project == "EMODnet Chemistry"){
	//     console.log("delete tree")
	//     //get parent of tree
	//     var parent_tree_div = $(treeview_vars_id).parent("div");
	//     //delete tree
	//     $(treeview_vars_id).remove();
	//     //create new tree
	//     parent_tree_div.append(emodnet_contaminants_tree);
	//     //initialise again
	//     //$(treeview_vars_id).hummingbird();
	//     init_outvar_treeview();

	//     //first uncheck all then check saved checks
	//     $(treeview_vars_id).hummingbird("uncheckAll");
	    
	//     console.log("localstorage")

	//uncheck all
	// $(treeview_vars_id).hummingbird("uncheckAll");



	//console.log("hideNodes")
	//W_get_data_availability_information
	// console.log("go through all vars and check availability start")
	// console.log(W_get_data_availability_information.station_set)

	//
	//$.each(W_get_data_availability_information.station_set,function(i,e){
	//turn it around and go through the treeview
	var treeview_data_ids = $(treeview_vars_id).find('input:checkbox.hummingbird-end-node'); 

	// console.log("treeview_data_ids")
	// console.log(treeview_data_ids)

	
	var anz = 0
	var n = 0;
	var ns = "";
	var change_node_mode = "";

	//startWorker()
	// console.log("start worker")
	// startWorker();
	//send treeview to worker
	//var treeview2worker = $(treeview_vars_id).clone(true,true);
	//var treeview2worker = $(treeview_vars_id).html();
	//console.log(treeview2worker);
	//treeview_worker.postMessage(treeview2worker);


	//start time
	    // console.log("start timer")
	    // console.time('treeview_test')

	
	$.each(treeview_data_ids,function(i){
	
	//console.log(i)
	    //console.log("i="+i+", e="+e + "type:"+typeof(e))
	    //console.log($(this).attr("data-id"))

	    //return true;
	    
	    // enable / disable treeview in step 2
	    // n = Number(i)+1;
	    // ns = n.toString();

	    //console.log(p01_num[n])


	    //console.log(p01_num[n].toString())

	    //enable only if this var is not mandatory
	    //console.log("mandatory_output_vars")
	    //console.log(mandatory_output_vars)
	    //if (mandatory_output_vars[ns] == true){
	    //console.log("var " + ns + " is mandatory")
	    //} else {

	    //console.log(W_get_collection_information.datavar_unames[i])
	    //console.log(e)
	    //console.log(p01_list[i])


	    if (typeof(mandatory_output_vars[ns]) == "undefined"){
		//console.log("baggi2")
		//$("#treeview_vars").hummingbird("enableNode",{attr:"data-id",name: ns,state:false});
	    }
	    //

	    
	    //

	    //in normal (geotraces, emodnet_eutrophication) these are the ODV variable numbers
	    var that_data_id = $(this).attr("data-id");
	    //var that_data_id_dq = '"'+that_data_id+'"';

	    //for emodnet contaminants the data-id contains the p01 term and we have to get the ODV number from
	    // console.log('p01_list[that_data_id]')
	    // console.log(p01_list[that_data_id].num)
	    if (contaminants == true && project == "EMODnet Chemistry"){
		that_data_id = p01_list[that_data_id].num.toString();
	    }



	    //this is for Geotraces, but ok for default
	    var ex = that_data_id.split('|');
	    var exLength = ex.length;
	    //console.log("Length="+exLength)
	    var one_enable = false;

	
	    $.each(ex,function(f,g){
		n = Number(g)-1;
		ns = n.toString();
		anz = W_get_data_availability_information.station_set[n];

		// console.log("anz")
		// console.log(anz)
		// console.log(typeof(anz))
		// console.log(anz=="-")
		
		
		if (anz=="-"){ // || e=="0"){
		    change_node_mode = "disableNode";
		} else {
		    change_node_mode = "enableNode";
		    one_enable = true;
		}
		//console.log(change_node_mode)
	    });
	    // if there is a GEOTRACES combined var e.g. fe: 76|154
	    // then if all of them are not available we disable
	    // if at least one is available we enable
	    if (one_enable){
	    	change_node_mode = "enableNode";
	    }
	    //
	    if (change_node_mode == "disableNode"){
		//console.log("baggi1")
		//console.log($(this))
		$(this).prop("checked",false).prop("disabled",true).css({"cursor":"not-allowed"}).parent("label").css({'color':'#c8c8c8',"cursor":"not-allowed","background-color":""});
		//$("#treeview_vars").hummingbird("skipCheckUncheckDone");
		//$("#treeview_vars").hummingbird(change_node_mode,{attr:"data-id",name: that_data_id_dq,state:false});
	    }
	    
	    //
	    //
	    // Attention, in GEOTRACES we have these combined variables 12|46|51|61 =  SALINITY_D_CONC
	    // here we do not use these combined ones, but the splitted, i.e. the original
	    //
	    //
	    //set variables dropdown in step 3
	    //if no vars have been selected in step 2, show all for visualisation
	    //if some vars have been selected then show only those
	    if (use_all_vars == true){
		if (anz!="-"){ // && e!="0"){
		    //console.log("show var " + i)
		    //if (typeof(output_vars_list[ns]) != "undefined"){
		    if (contaminants == true && project == "EMODnet Chemistry"){
			var_x_dropdown = var_x_dropdown + '<option value="' + i + '">' + p01_num[n] + '</option>';
		    } else {
			var_x_dropdown = var_x_dropdown + '<option value="' + i + '">' + W_get_collection_information.datavar_unames[i] + '</option>';
		    }
		    //}
		}
	    }

	    
	    
	});

	//tri-state
	$(treeview_vars_id).hummingbird("triState");
	
	//print time
	//console.timeEnd('treeview_test')	
	
	//console.log("go through all vars and check availability end")
	//trigger now to update Outvar etc.
	//console.log("101 trigger on init")
	treeview_mandatory();
	$(treeview_vars_id).trigger("CheckUncheckDone");

	
	//better create new methods hide and show
	if (contaminants == true && project == "EMODnet Chemistry"){
	    //remove groups with no children
	    //remove_childless_tree_nodes();
	    //console.log("hide childless nodes start")
	    //hide_childless_tree_nodes()
	    //console.log("hide childless nodes end")
	    //initialise again
	    //console.log("hide_childless_tree_nodes done")
	    //init_outvar_treeview();
	    //init
	    //$(treeview_vars_id).hummingbird();

	    //$(treeview_vars_id).hummingbird("uncheckAll");
	    //$(treeview_vars_id).hummingbird();

	    //-----check nodes from treeview_full_state
	    if (localStorage.getItem(storage_name) !== null){
		Xstatus = JSON.parse(localStorage.getItem(storage_name));
		//console.log("treeview_full_state")
		//console.log(Xstatus.treeview_full_state)

		//console.log("restore")
		//console.log(OutVarState)
		if (typeof(Xstatus.OutVarState) != "undefined"){
		    $(treeview_vars_id).hummingbird("restoreState",{restore_state:Xstatus.OutVarState});
		}
		// restore by treeview_full_state
		//var all_checkboxes = $(treeview_vars_id).find("checkbox");
		//console.log("go through treeview_full_state")
		//$.each(Xstatus.treeview_full_state.id, function(i,e) {
		    //console.log("check:"+e)
		    //console.log($('#'+e))
		    //$('#'+e).prop("checked",true);
		    //console.log($(treeview_vars_id).find("input:checkbox#"+e).prop("checked",true))
		    //$(treeview_vars_id).find("input:checkbox#"+e).prop("checked",true);
		//});
		//console.log("go through treeview_indeterminate")
		//$.each(Xstatus.treeview_indeterminate.id, function(i,e) {
		    //$(treeview_vars_id).find("input:checkbox#"+e).prop("indeterminate",true);
		//});
		//trigger $(treeview_vars_id).on("CheckUncheckDone", function(){
		//$(treeview_vars_id).trigger("CheckUncheckDone");
				
	    }	    



	    
	    // //-----check every node from state-----------//
	    // /////////////////////////////////
	    // if (localStorage.getItem(storage_name) !== null){
	    // 	Xstatus = JSON.parse(localStorage.getItem(storage_name))
	    // 	//console.log(Xstatus)
	    // 	if (typeof(Xstatus.OutVar) != "undefined"){
	    // 	    if (Xstatus.OutVar != "") {
	    // 		console.log("uncheck check OutVar, i.e. saved state start")
	    // 		$.each(Xstatus.OutVar, function(i,e) {
	    // 		    //console.log("uncheck - check")
	    // 		    //if (contaminants == true && project == "EMODnet Chemistry"){
	    // 			//console.log(Xstatus.p01_num[Number(e)])
	    // 			var p01_name = Xstatus.p01_num[Number(e)];
	    // 			//console.log(p01_name)
	    // 		    //$(treeview_vars_id).hummingbird("uncheckNode",{attr:"data-id",name: p01_name ,collapseChildren:false});
	    // 		    $(treeview_vars_id).hummingbird("checkNode",{attr:"data-id",name: p01_name ,collapseChildren:false});
	    // 		    //$(treeview_vars_id).hummingbird("disableNode",{attr:"data-id",name: p01_name});
	    // 		    //} else {
	    // 			//$(treeview_vars_id).hummingbird("checkNode",{attr:"data-id",name:'"'+e+'"' ,collapseChildren:false});
	    // 		    //}
	    // 		});
	    // 		console.log("uncheck check OutVar, i.e. saved state end")
	    // 	    }
	    // 	}
	    // }
	    // //////////////////////////////////////////////////

	    
	}
	
    }


    //------------Treeview data available and EMODnet contaminants----------------//    




    //-------------misc functions----------------------------------//

        //mandatory_output_vars
    window.treeview_mandatory = function(){
	//console.log(mandatory_output_vars)
	//console.log(mandatory_output_vars)
	//console.log(typeof(mandatory_output_vars.length))
	//if (typeof(mandatory_output_vars.length) != "undefined"){
	    $.each(mandatory_output_vars,function(i,e){
		//console.log("mandatory i="+i+" e="+e)
		//$("#treeview_vars").hummingbird("disableNode",{attr:"data-id",name: e,expandParents:false});
		//$(treeview_vars_id).hummingbird("skipCheckUncheckDone");
		$(treeview_vars_id).hummingbird("disableNode",{attr:"data-id",name: i,state:true,disableChildren:false});
	    });
	//}
    }

    window.treeview_default = function(){
	// console.log("default_output_vars= "+default_output_vars)
	// console.log("type default_output_vars= "+typeof(default_output_vars))

	$(treeview_vars_id).hummingbird("uncheckAll");
	if (default_output_vars == -1){
	    //console.log("check_all")
	    //$(treeview_vars_id).hummingbird("skipCheckUncheckDone");
	    $(treeview_vars_id).hummingbird("checkAll");
	} else {
	    //console.log("check_defaults")
	    $.each(default_output_vars,function(i,e){
		//console.log("mandatory i="+i+" e="+e)
		//console.log("treeview_default")
		//$("#treeview_vars").hummingbird("disableNode",{attr:"data-id",name: e,expandParents:false});
		$(treeview_vars_id).hummingbird("checkNode",{attr:"data-id",name: e});
	    });
	}
    }
    
    


    window.required_vars_restore = function() {
	if (localStorage.getItem(storage_name) !== null){
	    //console.log("data exists 1")
	    Xstatus = JSON.parse(localStorage.getItem(storage_name))
	    if (typeof(Xstatus.ReqVarList) != "undefined"){
		//console.log("data exists 2")
		if (Xstatus.ReqVarList != "") {
		    //console.log("data exists 3")
		    $.each(Xstatus.ReqVarList, function(i,e) {
			//console.log(e)
			//console.log(typeof(e))
			$('#required_vars_modal').find("#treeview_requested_vars").hummingbird("checkNode",{attr:"data-id",name:'"'+e+'"' ,collapseChildren:false});
		    });				
		}
	    }
	}
    }

    //remove_childless_tree_nodes
    window.remove_childless_tree_nodes = function(){
	var groups = $(treeview_vars_id).find('input:checkbox:not(".hummingbird-end-node")');
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
		//console.log($(this))
	    }
	});
	//initialise again
	//$(treeview_vars_id).hummingbird();
    }

    //hide_childless_tree_nodes
    window.hide_childless_tree_nodes = function(){
	var groups = $(treeview_vars_id).find('input:checkbox:not(".hummingbird-end-node")');	
	//console.log(hidden_checkboxes)
	$.each(groups,function(i,e){
	    //console.log("hide_childless_tree_nodes")
	    var num_end_nodes = $(this).parent("label").parent("li").find("input.hummingbird-end-node:not([type='hidden'])"); //.length;
	    //console.log(num_end_nodes)
	    //console.log(num_end_nodes.length)
	    //console.log($(this).parent("label").parent("li"))
	    //console.log(num_end_nodes)
	    if (num_end_nodes.length == 0){
		//set checkboxes to hidden		
		//$(this).parent("label").parent("li").find('input:checkbox').attr("type","hidden");
		//console.log($(this))
		//$(this).attr("type","hidden");
		//$(this).parent("label").hide();
		//

		$(treeview_vars_id).hummingbird("uncheckNode",{attr:"data-id",name: $(this).attr("data-id")});
		$(treeview_vars_id).hummingbird("hideNode",{attr:"data-id",name: $(this).attr("data-id")});

		//$(this).parent("label").parent("li").hide();
	    }
	});
	//initialise again
	//$(treeview_vars_id).hummingbird();
	if (contaminants == true && project == "EMODnet Chemistry"){
	    count_treeview_end_nodes(treeview_vars_id);
	}

	//show treeview after final rendering
	//hide_image_loading('.show_loading_here');
	//show_image_loading('.show_loading_here');
	// $('.show_loading_here').hide();
	// set_window_height();
	// $('.output_treeview_col').show();
    }


    //-------------misc functions----------------------------------//

    

    

    // //------------Required Vars----------------//    

    // $('#required_vars_modal').find('.treeview_button_apply').on('click',function(){

    // 	//console.log("apply")


    // 	$('#required_vars_button').text(required_vars_text);
	
    // 	//required_vars_mode = false;
    // 	//checkSize(true);
    // 	//$('.station_select_left_column').unblock();
    // 	//$('.station_select_left_column').find('button,input').prop('disabled',false).css('cursor','default');
    // 	// $('.station_select_left_column').show();
    // 	// $('.wsODV_map_container').show();
    // 	// $('.profile_info').show();
    // 	// $('.treeview_search_left_column').hide();
    // 	// $('.treeview_div').hide();

	
    // 	//wsODV
    // 	ReqVarEx = FilterVars_2_ReqVarEx(List.dataid);
    // 	var view_snippet = '<StationSelectionCriteria>' + '<RequiredVarExpression expr="' + ReqVarEx + '"/>'  + '</StationSelectionCriteria>';
    // 	//console.log(view_snippet)
    // 	var required_vars_option = {"view_snippet":view_snippet};
    // 	send_wsODV_msg('modify_view',required_vars_option);
    // 	show_image_loading();
    // 	//send_wsODV_msg('get_canvas_window');
    // 	var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
    // 	send_wsODV_msg('get_canvas_window',get_canvas_window_options);
	


    // 	webodv_status[storage_name]['ReqVarList'] = List.dataid;
    // 	webodv_status[storage_name]['ReqVarEx'] = ReqVarEx;
    // 	localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	
    // 	$('#required_vars_modal').modal('hide');
	
    // });

    // $('#required_vars_modal').on('hide.bs.modal',function(){
    // 	//console.log("going to hide")
    // 	//restore settings, i.e. cancel
    // 	$('#required_vars_modal').find("#treeview_requested_vars").hummingbird("uncheckAll");
    // 	required_vars_restore();
    // });

    
    // $('#required_vars_modal').find('.treeview_button_cancel').on('click',function(){
    // 	//$('#required_vars_modal').find("#treeview_requested_vars").hummingbird("uncheckAll");
    // 	//required_vars_restore();
    // 	$('#required_vars_modal').modal('hide');
    // });
    // $('#required_vars_modal').find('.treeview_button_reset').on('click',function(){
    // 	$('#required_vars_modal').find("#treeview_requested_vars").hummingbird("uncheckAll");
    // });
    // $('#required_vars_modal').find('.treeview_button_collapse').on('click',function(){
    // 	$('#required_vars_modal').find("#treeview_requested_vars").hummingbird("collapseAll");
    // 	$('#required_vars_modal').find("#treeview_requested_vars").hummingbird("expandNode",{attr:"data-id",name: "0",expandParents:false});
    // });

    // function FilterVars_2_ReqVarEx(List) {
    // 	//create chunks of single id's or "or" groups
    // 	//and connect them all with .and.

    // 	//array to store the chunks
    // 	var NewList = [];
    // 	var num = 0
	
    // 	$.each(List,function(i,e){
    // 	    //console.log("i=" + i + " e=" + e)
    // 	    //if chunk is a group
    // 	    var chunk = "";
    // 	    if (e.match(/\|/g)) {
    // 		var tmp = e.split('|');
    // 		$.each(tmp,function(j,f){
    // 		    //if this is the first entry in List
    // 		    //console.log("i=" + i + " j=" + j + " f=" + f)
    // 		    if (j==0){			    
    // 			chunk = chunk + (Number(f)-1).toString() + " ";
    // 		    }
    // 		    if (j>0){
    // 			chunk = chunk + (Number(f)-1).toString() + " .or. ";
    // 		    }		
    // 		});
    // 	    } else {
    // 		chunk = chunk + (Number(e)-1).toString() + " ";
    // 	    }
    // 	    if (i>0) {
    // 		chunk = chunk + ".and. ";
    // 	    }
    // 	    NewList[num] = chunk;
    // 	    num++;	
    // 	});
    // 	//console.log(List)
    // 	//console.log(NewList)
    // 	//
    // 	ReqVarEx = NewList.join('').trim();
    // 	//console.log("expr=" + ReqVarEx);
    // 	return ReqVarEx;

    // }


    // //------------Required Vars----------------//





    //------------Load and init treeviews----------------//


    

//    console.log("webodvextractor_treeview.js")
//ajax get treeview
    //http_port is in webodv_settings.json
    //console.log("get treeview:")
    //console.log(window.location.protocol + '//' + window.location.hostname + ':' + http_port + '/webodv/data/' + datasetname + '/service/DataExtraction/create_treeview')

    get_treeview_from_server = function(){
	if (contaminants == true && project == "EMODnet Chemistry"){
	    var get_treeview_url = '/contaminants/treeview/get';
	} else {
	    var get_treeview_url = '/' + datasetname + '/service/DataExtraction/create_treeview';
	}

	$.ajax({
    	    type: "GET",
	    url: window.location.protocol + '//' + window.location.hostname + http_port + get_treeview_url,
    	    cache: "false",
	    func: "get_treeview",
    	    error:   function(data){
    		//console.log("create_treeview ERROR");
    	    },
    	    success: function(data){
    		//console.log("create_treeview SUCCESS");
    	    },
	});
    }

    get_treeview_from_server();
    


    //ajax complete
    $(document).ajaxComplete(function(e,xhr,settings){
	if (settings.func=="dev_emodnet_variables_dev") {
	    responseText = JSON.parse(xhr.responseText);
	    //console.log(responseText)
	    if (responseText == "true"){
		//console.log("go into dev mode")
		//
		window.location.reload(true);
		//remove_top_level_nodes = false;
		//get_treeview_from_server();
		// init_outvar_treeview();
		// treeview_emodnet_contaminants_connect();
	    }
	}
	if (settings.func=="get_treeview") {
	    //console.log("build big treeview start")
	    //console.log(xhr.responseText)
	    responseText = JSON.parse(xhr.responseText);
	    //console.log(responseText)
	    //------------------Requested variables------------------------------------//
	    //on modal shown
	    //initialise only once
	    var init_required_tree = true;



	    
	    $('#required_vars_modal').on('shown.bs.modal',function(){

		if (reset_clicked) {
		    $(this).find("#treeview_requested_vars").hummingbird("uncheckAll");
		    //treeview_default();
		    reset_clicked = false;
		}



		if (init_required_tree){
		    init_required_tree = false;
		    // var doc_height = Number($(window).height());
		    // doc_height = doc_height/2;
		    // //console.log("doc_height= "+doc_height.toString())
		    // $(this).find('#treeview_container').css({'height':doc_height.toString()+'px'})
		    // $(this).find('.selected_vars').css({'height':doc_height.toString()+'px'})

		    //remove old treeview
		    if (contaminants == true && project == "EMODnet Chemistry"){
			//change id
			$(this).find('#treeview_vars').attr("id","treeview_requested_vars");
		    } else {
			$(this).find('#treeview_requested_vars').append(responseText);
		    }
		    //initialize hummingbird treeview

		    //checkboxes
		    $.fn.hummingbird.defaults.checkboxes = "enabled";
		    
		    //expand and collapse on click on parent disabled
		    $.fn.hummingbird.defaults.clickGroupsToggle= "disabled";

		    
		    //disable checking of folders
		    $.fn.hummingbird.defaults.checkboxesGroups= "disabled";
		    //allow only one group of level one to be open at a time
		    $.fn.hummingbird.defaults.singleGroupOpen = -1;

		    //hover
		    //$.fn.hummingbird.defaults.hoverItems = true;
		    //$.fn.hummingbird.defaults.hoverMode = "bootstrap";
		    $.fn.hummingbird.defaults.hoverColorBg2 = "white";	 
		    
		    $(this).find("#treeview_requested_vars").hummingbird();
		    
		    //open root folder
		    $(this).find("#treeview_requested_vars").hummingbird("expandNode",{sel:"data-id",vals: ["0"]});


		    
		    //search
		    //hide the search treeview dropdown if user enters a page
		    // if (pager_num==2){
		    // 	$('#search_output_vars').hide();
		    // }
		    // if (pager_num==1){
		    // 	$('#search_output').hide();
		    // }



		    

		    $(this).find("#treeview_requested_vars").hummingbird("search",{treeview_container:"treeview_container",search_input:"search_input",search_output:"search_output",search_button:"search_button",scrollOffset:-250,onlyEndNodes:false,dialog:'.modal-dialog'});
		    

		    //treeview event
		    $(this).find("#treeview_requested_vars").on("CheckUncheckDone", function(){
			//console.log("CheckUncheckDone")
			List = {"id" : [], "dataid" : [], "text" : []};
			$('#required_vars_modal').find("#treeview_requested_vars").hummingbird("getChecked",{list:List,onlyEndNodes:true});
			//console.log(List.dataid)
			//button text
			var L = List.dataid.length;
			if (L==1){
			    required_vars_text = L + " variable selected";
			} else {
			    required_vars_text = L + " variables selected";
			}
			webodv_status[storage_name]['ReqVarNum'] = L;
			localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));

			//$('#required_vars_button').text(msg)

			//add to list
			var required_vars_list = '<div class="table-responsive table_wrapper" style="overflow-y:scroll;"><table class="table table-sm table-striped table-condensed" style="white-space:nowrap;">';
			if (List.id != "") {
			    $.each(List.text, function(i,e) {
				//console.log(e)
				//$("#treeview").hummingbird("uncheckNode",{attr:"id",name: '"' + e + '"',collapseChildren:false});
				required_vars_list = required_vars_list + '<tr class="var_name"><td>' + e + '</td></tr>';
			    });
			}
			required_vars_list = required_vars_list + "</table></div>";
			$('#required_vars_modal').find('.h4_selected_variables').children('div').remove();
			$('#required_vars_modal').find('.h4_selected_variables').append(required_vars_list);
			
		    });

		   //console.log("required_vars_restore")
		    required_vars_restore();

		}
	    });
	    //------------------Requested variables------------------------------------//



	    

	    //------------------Output variables------------------------------------//

	    // var doc_height = Number($(window).height());
	    // doc_height = doc_height*0.7;
	    //console.log("doc_height= "+doc_height.toString())
	    // $('#treeview_container_vars').css({'height':doc_height.toString()+'px'})
	    // $('.selected_variables_vars').css({'height':doc_height.toString()+'px'})
	    //console.log(responseText)

	    //default

	    //treeview_data_available();
	    
	    if (contaminants == true && project == "EMODnet Chemistry"){
		treeview_vars_id = "#treeview2_vars";
		treeview_container_search_div = "treeview_container2";
	    } else {
		$('#treeview_vars').append(responseText);
		treeview_vars_id = "#treeview_vars";
		treeview_container_search_div = "treeview_container_vars";
	    }
	    
	    //create a linear list of the var names (used for visualization for the axes dropdowns)
	    //output_vars_list
	    var treeview_labels = $(treeview_vars_id).find('label');
	    //console.log(treeview_labels.length)
	    $.each(treeview_labels,function(i,e){
		//console.log($(this).text())


		//remove depth and temp for EMODnet eutrophication
		if (eutrophication){
		    var nodes_remove = ["Depth", "temperature", "salinity", "time","Pressure"];
		    for (var i in nodes_remove){
			//console.log("remove now")
			//console.log(nodes_remove[i])
			if ($(this).text().includes(nodes_remove[i]) !== false){
			    eutro_hidden_vars.push($(this).children('input').attr('data-id'));
			    $(this).parent('li').remove();
			}
		    }
		}
		
		//console.log($(this).children('input').attr('data-id') + " = " + $(this).text())
		var output_var_data_id = $(this).children('input').attr('data-id');
		output_vars_list[output_var_data_id] = $(this).text();
		//output_p01_list

		//for contaminants go through the tree
		//and remove all non-existing p01s
		//if (contaminants == true && project == "EMODnet Chemistry"){			    
		//console.log(output_vars_list)
		//console.log(output_var_data_id)
		//console.log(p01_list[output_var_data_id])
		//$("#treeview_vars").hummingbird("removeNode",{attr:"data-id",name: p01_list[i]});
		// if (typeof(p01_list.output_var_data_id) == "undefined"){
		// 	//$(this).parent("li").remove();
		// } else {
		// 	console.log(output_var_data_id + " exists")
		// }
		//}
		

		//treeview_data_available();
		
	    });

	    //console.log("build big treeview end")

	    //treeview_data_available();



	    init_outvar_treeview();
	    if (websocket_login){
		//console.log('get_data_availability_information from treeview')
		send_wsODV_msg('get_data_availability_information');
		//treeview_emodnet_contaminants_connect();
	    }

	    
	    //treeview_data_available();
	    
	}
    });


    window.init_outvar_treeview = function(){
	//initialize hummingbird treeview

	//console.log("init_outvar_treeview")


	//debugger;
	
	//checkboxes
	$.fn.hummingbird.defaults.checkboxes = "enabled";

	//expand and collapse on click on parent disabled
	$.fn.hummingbird.defaults.clickGroupsToggle= "disabled";

	
	//enable checking of folders
	$.fn.hummingbird.defaults.checkboxesGroups= "enabled";

	//hover
	//$.fn.hummingbird.defaults.hoverItems = true;
	//$.fn.hummingbird.defaults.hoverMode = "bootstrap";

	//allow only one group of level one to be open at a time
	$.fn.hummingbird.defaults.singleGroupOpen = -1;

	$(treeview_vars_id).hummingbird();

	//count number of vars
	window.count_treeview_end_nodes = function(the_treeview_id){
	    var TL = $(the_treeview_id).find('input:checkbox.hummingbird-end-node').length;
	    webodv_status[storage_name]['treeview_num_end_nodes'] = TL;
	    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
	    $('.num_variables_total').text(TL);
	}

	if (contaminants == true && project == "EMODnet Chemistry"){
	    if (localStorage.getItem(storage_name) !== null){
		Xstatus = JSON.parse(localStorage.getItem(storage_name));
		$('.num_variables_total').text(Xstatus.treeview_num_end_nodes);
	    }
	} else {
	    count_treeview_end_nodes(treeview_vars_id);
	}
    
	//open root folder
	$(treeview_vars_id).hummingbird("expandNode",{sel:"data-id",vals: ["0"]});

	
	$('#collapse_all').on("click", function(){
	    $(treeview_vars_id).hummingbird("collapseAll");
	});
	$('#expand_all').on("click", function(){
	    $(treeview_vars_id).hummingbird("expandAll");
	});
	$('#check_all').on("click", function(){
	    $(treeview_vars_id).hummingbird("checkAll");
	});
	$('#uncheck_all').on("click", function(){
	    $(treeview_vars_id).hummingbird("uncheckAll");
	});

	
	//serach
	//set this new on every visit to page 2
	//to prevent enter key
	//console.log("search")
	//console.log()
	$(treeview_vars_id).hummingbird("search",{treeview_container:treeview_container_search_div,search_input:"search_input_vars",search_output:"search_output_vars",search_button:"search_button_vars",scrollOffset:-250,onlyEndNodes:false});
	

	//treeview event
	$(treeview_vars_id).on("CheckUncheckDone", function(){
	    //console.log("CheckUncheckDone triggered")


	    //save state
	    OutVarState = {};
	    $(treeview_vars_id).hummingbird("saveState",{save_state:OutVarState});
	    //console.log("saveState")
	    //console.log(OutVarState)

	    
	    //if (contaminants == true && project == "EMODnet Chemistry"){
		webodv_status[storage_name]['OutVar'] = [];
	    //}

	    
	    List = {"id" : [], "dataid" : [], "text" : []};
	    // List_full = {"id" : [], "dataid" : [], "text" : []};
	    // List_indeterminate = {"id" : [], "dataid" : [], "text" : []};

	    //console.log(List)
	    
	    $(treeview_vars_id).hummingbird("getChecked",{list:List,onlyEndNodes:true});   // get now all nodes true
	    // $(treeview_vars_id).hummingbird("getChecked",{list:List_full,onlyEndNodes:false,onlyParents:false});   // get now all nodes true
	    // $(treeview_vars_id).hummingbird("getIndeterminate",{list:List_indeterminate});   // indeterminate
	    //console.log(List_indeterminate)
	    //button text
	    var L = List.dataid.length;

	    if (contaminants == true && project == "EMODnet Chemistry"){			    
		//if (page2_visit){
		// console.log("L")
		// console.log(L)
		//set this only if the tree has been thinned, i.e. if user was at least one in step 2
		if (typeof(webodv_status[storage_name]['p01_list']) != "undefined"){
		    $('.num_variables').text(L);
		}
		//}
	    } else {
		$('.num_variables').text(L);
	    }
	    
	    var p01_encoding_test = "";
	    var p01_encoding_num = "";

	    var localStorage_contaminants_exists = false;
	    if (contaminants == true && project == "EMODnet Chemistry"){
		if (localStorage.getItem(storage_name) !== null){
		    Xstatus = JSON.parse(localStorage.getItem(storage_name));
		    localStorage_contaminants_exists = true;
		}
	    }
	    
	    //add to list
	    var vars_list = '<div class="table-responsive table_wrapper" style="overflow-y:scroll; height:'+selected_variables_vars_height+';"><table class="table table-sm table-striped table-condensed" style="white-space:nowrap;">';
	    if (List.id != "") {
		$.each(List.text, function(i,e) {
		    // console.log("e")
		    // console.log(e)
		    // console.log(e.trim())
		    // console.log(typeof(e))

		    var te = e.trim();

		    //EMODnet dev mode
		    //cut out the ---p01 etc.
		    if (remove_top_level_nodes == false){
			var res = te.match(/.*(?=---)/g);
			te = res[0];
		    }

		    // console.log("te")
		    // console.log(te)

		    
		    if (localStorage_contaminants_exists){
			if (typeof(Xstatus.p01_list) != "undefined"){
			    p01_list = Xstatus.p01_list;
			    p01_num = Xstatus.p01_num;
			}
			//
			// console.log("p01_list")
			// console.log(p01_list)
			// debugger;
			//this is only for testing if we have these ---p01 etc.
			// p01_encoding_test = e.match(/.*(?=---)/g);
			// if (typeof(p01_list[p01_encoding_test[0].trim()]) != "undefined"){
			//     p01_encoding_num = p01_encoding_num + "," + p01_list[p01_encoding_test[0].trim()].num;
			// }			    
			if (typeof(p01_list[te]) != "undefined"){
			    p01_encoding_num = p01_encoding_num + "," + p01_list[te].num;
			}			    
			//this is only for testing if we have these ---p01 etc.
			//get node based on data-id
			//vars_list = vars_list + '<tr class="var_name"><td data-toggle="tooltip" data-placement="auto" title="'+  p01_list[p01_encoding_test[0].trim()].tooltip  +'">' + p01_encoding_test[0].trim() + '</td></tr>';
			vars_list = vars_list + '<tr class="var_name"><td data-toggle="tooltip" data-placement="auto" title="'+  p01_list[te].tooltip  +'">' + e + '</td></tr>';			
		    } else {
			vars_list = vars_list + '<tr class="var_name"><td>' + te + '</td></tr>';
		    }
		});

		//
		//if saved treeview states are restored, i.e. a loop goes over all
		//variables and does a checkNode
		//then the below is only needed for the last item
		//
		//try to circumvent checkNode on every node variable which is in the OutVar array !!!!!!!!!!!!!!!!!!!!!!!!!!!!
		//save all checkboxes, not only end-nodes as state and then restore in one step !!!!!!!!!!!!!!!!!!!!
		
		//console.log("List.id.length")
		//console.log(List.id.length)

		//console.log("List.dataid")
		//console.log(List.dataid)

		if (localStorage.getItem(storage_name) !== null){
		    //make array
		    var p01_encoding_num_arr = p01_encoding_num.split(',');
		    //remove first element
		    p01_encoding_num_arr.shift();
		    
		    //console.log(p01_num_arr)
		
		    if (contaminants == true && project == "EMODnet Chemistry"){
			//console.log("p01_encoding_num_arr")
			//console.log(p01_encoding_num_arr)
			webodv_status[storage_name]['OutVar'] = p01_encoding_num_arr;
		    } else {
			webodv_status[storage_name]['OutVar'] = List.dataid;
		    }
		    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));
		}
	    }
	    vars_list = vars_list + "</table></div>";
	    $('.h4_selected_variables_vars').children('div').remove();
	    $('.h4_selected_variables_vars').append(vars_list);

	    //debugger;
	    //set_window_height();
	    
	    //console.log('OutVar')
	   //console.log(webodv_status[storage_name]['OutVar'])

	    //console.log("primary_var_id")
	    //console.log(primary_var_id)
	    //console.log(typeof(primary_var_id))
	    
	    //remove primary var
	    var OutVarExStr = webodv_status[storage_name]['OutVar'];
	    var OutVarEx = "";
	    var e_num = 0;
	    //console.log("OutVarExStr")
	    //console.log(OutVarExStr)
	    $.each(OutVarExStr,function(i,e){
		e_num = Number(e)-1;
		if (e_num !== primary_var_id){
		    OutVarEx = OutVarEx + ',' + e_num;
		}
	    });
	    //remove first comma
	    OutVarEx = OutVarEx.substring(1);

	    //console.log('OutVarEx')
	    //console.log(OutVarEx)

	    // webodv_status[storage_name]['treeview_full_state'] = List_full;
	    // webodv_status[storage_name]['treeview_indeterminate'] = List_indeterminate;

	    
	    webodv_status[storage_name]['OutVarEx'] = OutVarEx;
	    webodv_status[storage_name]['OutVarState'] = OutVarState;



	    localStorage.setItem(storage_name,JSON.stringify(webodv_status[storage_name]));

	    //console.log("CheckUncheckDone:");
	    //console.log("OutVar");
	    //console.log(webodv_status[storage_name]['OutVar'])
	    
	    //if (contaminants == true && project == "EMODnet Chemistry"){
		//required or logic
		//console.log("OutVarEx")
		//show_image_loading('.img_col');

		if (treeview_mode == 0){
		    var use_or_logic = "T";
		}
		if (treeview_mode == 1){
		    var use_or_logic = "F";
		}
		//console.log("use_or_logic")
		//console.log(use_or_logic)
		//console.log(OutVarEx)

	    webodv_status[storage_name]['use_or_logic'] = use_or_logic;
	    
		var view_snippet = '<StationSelectionCriteria>' + '<RequiredVars var_ids="' + OutVarEx + '" use_or_logic="'+ use_or_logic  +'"/>'  + '</StationSelectionCriteria>';
		var required_vars_option = {"view_snippet":view_snippet};
		//console.log("OutVarEx")
		//console.log(OutVarEx)
	    if (websocket_login){
		//console.log("fire websocket")
	        send_wsODV_msg('modify_view',required_vars_option);
	    }

		if (pager_num == 2){
		    //show loading
		    //console.log("CheckUncheckDone - show_image_loading")
		    //show_image_loading('.img_col');
		    var get_canvas_window_options = {"win_id":-1,"extended_rect":false};
		    send_wsODV_msg('get_canvas_window',get_canvas_window_options);
		}
		
		//get_data_avail to build up the new 
		
	    //}


	    
	});
	//------------------output variables------------------------------------//

	//staus
	//console.log("status")
	//console.log(storage_name)
	//console.log(localStorage.getItem(storage_name))
	if (localStorage.getItem(storage_name) !== null){
	    Xstatus = JSON.parse(localStorage.getItem(storage_name));
	    //console.log("treeview_full_state")
	    //console.log(Xstatus.treeview_full_state)


	    // restore by treeview_full_state
	    if (typeof(Xstatus.OutVarState) != "undefined"){
		//console.log("restore")
		$(treeview_vars_id).hummingbird("restoreState",{restore_state:Xstatus.OutVarState});
	    }
	    //
	    //console.log("1. trigger on init")
	    $(treeview_vars_id).trigger("CheckUncheckDone");


	    
	    //check every node
	    // if (typeof(Xstatus.OutVar) != "undefined"){
	    // 	if (Xstatus.OutVar != "") {
	    // 	    $.each(Xstatus.OutVar, function(i,e) {
	    // 		//console.log(e)
	    // 		if (contaminants == true && project == "EMODnet Chemistry"){
	    // 		    //console.log(Xstatus.p01_num[Number(e)])
	    // 		    var p01_name = Xstatus.p01_num[Number(e)];
	    // 		    //console.log(p01_name)
	    // 		    //go through this time first on page 1 and then another time on page 2
	    // 		    $(treeview_vars_id).hummingbird("checkNode",{attr:"data-id",name: p01_name ,collapseChildren:false});
	    // 		} else {
	    // 		    $(treeview_vars_id).hummingbird("checkNode",{attr:"data-id",name:'"'+e+'"' ,collapseChildren:false});
	    // 		}
	    // 	    });				
	    // 	}
	    // }



	} else {
	    //console.log("baggi1")
	    //treeview_default();
	}

	//console.log("check previous nodes on first load from localstorage done")

	//treeview_mandatory();


	//get node on mousemove
	// var id = "";
	// var state = "";
	// $(treeview_vars_id).on("mousemove", function(evt){
	//     var element = $(evt.target);
	//     //console.log(element.children("input"))
	//     id = element.children("input").attr("id");
	//     state = element.children("input").prop("checked");
	//     //console.log(state)
	// });


	// $(document).keydown(function( event ) {
  	//     //console.log(event.which)
 	//     //key d and e 
	//     if (event.which == 68){
	// 	$(treeview_vars_id).hummingbird("disableNode",{attr:"id",name: id,state:state,disableChildren:true});
	//     }
	//     if (event.which == 69){
	// 	$(treeview_vars_id).hummingbird("enableNode",{attr:"id",name: id,state:state,enableChildren:true});
	//     }
	// });

	//remove hummingbird-treeview-converter
	//$('.hummingbird-treeview-converter').remove();
	//console.log("init big treeview end")

	init_outvar_treeview_done = true;
    }











})
