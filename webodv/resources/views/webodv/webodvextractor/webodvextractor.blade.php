@extends('layouts.webodv_layout')

@section('content')

    
    <div class="container-fluid webodvextractor_main">

	<!-- <div class="loading_snippet">
	     <h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1>
	     </div>
	   -->
	
	<!-- ##################### pager ######################## -->
	<!-- only show at md and above: d-none d-md-block -->
	<div class="d-none d-md-block" id="pager_large">
	    <ul class="pagination justify-content-center" style="margin:10px 0">
		<li id="pager_large_0" class="page-item webodv_pager_left" ><a class="page-link" href="#"><i class="fa fa-arrow-left"></i>&nbsp;</a></li>
		<li id="pager_large_1" class="page-item webodv_pager"><a class="page-link" href="#">1. Select Cruises / Domain / Time Range</a></li>
		<li id="pager_large_2" class="page-item webodv_pager"><a class="page-link" href="#">2. Select variables</a></li>
		<!-- <li id="pager_large_3" class="page-item webodv_pager"><a class="page-link" href="#">3. Data overview</a></li> -->
		<li id="pager_large_3" class="page-item webodv_pager"><a class="page-link" href="#">3. Download</a></li>
		<li id="pager_large_4" class="page-item webodv_pager"><a class="page-link exit-pager" href="#">4. Exit</a></li>
		<li id="pager_large_5" class="page-item webodv_pager_right"><a class="page-link" href="#">&nbsp;<i class="fa fa-arrow-right"></i></a></li>
	    </ul>
	</div>
	<!-- only show below md: d-md-none -->
	<div class="d-md-none" id="pager_small" style="">
	    <ul class="pagination justify-content-center" style="margin:10px 0">
		<li id="pager_small_0" class="page-item webodv_pager_left"><a class="page-link" href="#"><i class="fa fa-arrow-left"></i>&nbsp;</a></li>
		<li id="pager_small_1" class="page-item webodv_pager" data-toggle="tooltip" data-placement="auto" title="Select Cruises / Domain / Time Range"><a class="page-link" href="#">1.</a></li>
		<li id="pager_small_2" class="page-item webodv_pager" data-toggle="tooltip" data-placement="auto" title="Select variables"><a class="page-link" href="#">2.</a></li>
		<!-- <li id="pager_small_3" class="page-item webodv_pager" data-toggle="tooltip" data-placement="auto" title="Visualization"><a class="page-link" href="#">3.</a></li> -->
		<li id="pager_small_3" class="page-item webodv_pager" data-toggle="tooltip" data-placement="auto" title="Download"><a class="page-link" href="#">3.</a></li>
		<li id="pager_small_4" class="page-item webodv_pager" data-toggle="tooltip" data-placement="auto" title="Exit"><a class="page-link exit-pager" href="#">4.</a></li>
		<li id="pager_small_5" class="page-item webodv_pager_right"><a class="page-link" href="#">&nbsp;<i class="fa fa-arrow-right"></i></a></li>
	    </ul> 	    
	</div>
	<!-- ##################### pager ######################## -->


	

	<!-- ##################### page1 ######################## -->
	<div class="webodvextractor_page webodvextractor_page_1 pb-0 mb-0">
	    <div class="row">
		<div class="col-md-2 text-center">
		</div>
		<div class="col-md-8 text-center">
		    Click <i>Zoom in</i> to define a sub-region, <i>Apply</i> to select the sub-region, or <i>Zoom out</i> to return to full domain. Select <i>Time Range</i> in the format mm/dd/yyyy. 
		    <br><br>
		</div>
		<div class="col-md-2 text-center">
		</div>
	    </div>

	    <div class="row">
		<div class="col-md-4 col-lg-3 text-center station_select_left_column order-2 order-md-1 bg-left pt-3 pb-1">	    
		    <div class="d-lg-none">
			<div class="emodnet-func-dark bottom-three order-1">
			    Selection status
			</div>
			<div class="text-emodnet-blue num_stations_div text-left border border-primary rounded pl-2" style="">
			    Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			    <br>
			    Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
			</div>
			<br>
			<div class="emodnet-func-dark bottom-three order-2">
			    Export image 
			</div>
			<button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center download_image" style="padding-right:14px;" id="">Download</button>
			<a class="" href="#" style="display:none;" id="download_image_a_page1" download="image.gif">
			    <button id="download_image_button_page1"></button>		    
			</a>			    
			<br>
			<div class="emodnet-func-dark bottom-three text-center">
			    Data preview
			</div>
			<button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center data_preview" style="padding-right:14px;" id="">
			    <img src="{{ asset('images/scatter_icon.png') }}" width="64px" />
			</button>
			<br>						
			@if ($extractor_info['station_info_show'])
			    <div class="emodnet-func-dark bottom-three">
				Station info <i id=""  class="fa fa-question-circle-o help_color station_info_help" style="cursor:pointer;"></i>
			    </div>
			    <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center station_info" style="padding-right:14px;">Cruise: <span style="float:right;"><i class="fa fa-caret-down"></i></span></button>
			    <div class="get_dock_metavar_labels_list" style="display:none; overflow-y: scroll; overflow-x: visible; height: 200px; z-index: 1000; position: relative; background-color: white;"></div>
			    <br>
			@endif
		    </div>
		    @if ($extractor_info['cruises_show'])
			<div>
			    <div class="emodnet-func-dark-blue bottom-three">
				Cruises
			    </div>
			    <div class="selectpicker_wrapper cruises text-center">
				<select class="selectpicker form-control " id="select_cruise" data-style="btn-emodnet-grey btn-outline-secondary text-center" data-live-search="true" multiple data-selected-text-format="count" data-actions-box="true" data-size="6" data-virtualScroll="600">
				    @foreach ($extractor_info['cruises'] as $cruise)
					<option value="{{ $cruise }}" selected="selected" >{{ $cruise}}</option>
				    @endforeach
				</select>
			    </div>
			</div>
			<br>
		    @endif
		    @if ($extractor_info['zoom_show'])
			<div>
			    <div class="emodnet-func-dark-blue bottom-three">
				Map domain
			    </div>
			    <div class="row Yhide">
				<div class="d-lg-none col-md-12 mb-2">    <!-- show below lg and add margin-bottom-2-->
				    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border zoom_in text-center " value="Zoom mode">
				</div>
				<div class="d-none d-lg-block col-lg-6"> <!-- show above lg with no margin -->
				    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border zoom_in text-center " value="Zoom mode">
				</div>
				<div class="col-lg-6 col-md-12 mb-2"> 
				    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border zoom_out text-center " value="Zoom out">
				</div>
			    </div>
			    <div class="row Yhide">
				<div class="d-lg-none col-md-12 mb-2">    <!-- show below lg and add margin-bottom-2-->
				    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border zoom_full_range text-center " value="Full range">
				</div>
				<div class="d-none d-lg-block col-lg-6"> <!-- show above lg with no margin -->
				    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border zoom_full_range text-center " value="Full range">
				</div>
				<div class="col-lg-6 col-md-12 mb-2"> 
				    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border zoom_global text-center " value="Global">
				</div>
				<!-- <div class="col-md-12"> 
				     <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border zoom_full_range text-center " value="Full range">
				     </div> -->
			    </div>
			</div>
			<br>
		    @endif
		    @if ($extractor_info['date_show'])
			<div>
			    <div class="emodnet-func-dark-blue bottom-three">
				Time Range <i id="date_help"  class="fa fa-question-circle-o help_color" style="cursor:pointer;"></i> <i id="date_ban"  data-toggle="tooltip" data-placement="top" title="Disable" class="fa fa-ban help_color" style="cursor:pointer;display:none;"></i> <i id="date_enable"  data-toggle="tooltip" data-placement="top" title="Enable" class="fa fa-check-circle-o help_color" style="cursor:pointer;"></i>
			    </div>
			    <!-- <div class="input-group mb-2"> -->
			    <!-- from: mm/dd/yyyy -->
			    <div class="show_time_range" style="opacity:0.3">
				<div class="row bottom-three">
				    <div class="col-sm-3" style="padding-top:6px; padding-left:25px;">
					from:
				    </div>
				    <div class="col-sm-9">
					<input type="text" name="date1" class="form-control btn btn-responsive btn-block btn-outline-secondary text-center" id="date1">
				    </div>
				</div>
				
				<!-- <div class="input-group">
				     to: mm/dd/yyyy
				   -->
				<div class="row">
				    <div class="col-sm-3" style="padding-top:6px; padding-left:25px;">
					to:
				    </div>
				    <div class="col-sm-9">
					<input type="text" name="date2" class="form-control btn btn-responsive btn-block btn-outline-secondary text-center" id="date2">
				    </div>
				</div>
			    </div>
			</div>
			<br>
		    @endif
		    @if ($extractor_info['required_variables_show'])
			<div>
			    <div class="emodnet-func-dark-blue bottom-three">
				Required variables <i id="required_variables_help"  class="fa fa-question-circle-o help_color" style="cursor:pointer;"></i>
			    </div>
			    <button class="btn btn-responsive btn-block btn-emodnet-grey text-center text-uppercase" id="required_vars_button">0 variables selected</button>
			</div>
			<br>
		    @endif
		    @if ($extractor_info['reset_show'])
			<div>
			    <div class="emodnet-func-dark-blue bottom-three">
				Reset
			    </div>
			    <div class="row">
				<div class="d-lg-none col-md-12 mb-2">    <!-- show below lg and add margin-bottom-2-->
				    <button class="btn btn-responsive btn-block btn-emodnet-grey text-center text-uppercase reset" id="" data-toggle="tooltip" data-placement="auto" title="Resets Map, Date and Required variables to default values.">Page</button>
				</div>
				<div class="d-lg-none col-md-12 mb-2">    <!-- show below lg and add margin-bottom-2-->
				    <button class="btn btn-responsive btn-block btn-emodnet-grey text-center text-uppercase  extractor_eraser" id="" data-toggle="tooltip" data-placement="auto" title="Resets the complete extractor for this dataset.">Global</button>
				</div>
				<div class="d-none d-lg-block col-lg-6"> <!-- show above lg with no margin -->
				    <button class="btn btn-responsive btn-block btn-emodnet-grey text-center text-uppercase  reset" id="" data-toggle="tooltip" data-placement="auto" title="Resets Map, Date and Required variables to default values.">Page</button>				    
				</div>
				<div class="d-none d-lg-block col-lg-6"> <!-- show above lg with no margin -->
				    <button class="btn btn-responsive btn-block btn-emodnet-grey text-center text-uppercase  extractor_eraser" id="" data-toggle="tooltip" data-placement="auto" title="Resets the complete extractor for this dataset.">Global</button>
				</div>
			    </div>
			</div>
			<br>
		    @endif
		    @if ($extractor_info['point_size_show'])
			<div>
			    <!-- <h4>Point size</h4> -->
			    <h5 class="text-emodnet">Point size</h5>
			    <div class="selectpicker_wrapper">
				<select class="selectpicker form-control bg-left" id="point_size" data-style="btn btn-responsive btn-block btn-emodnet emodnet-orange-border ">
				    <option value="0.1">0.1</option>
				    <option value="0.5">0.5</option>
				    <option value="0.7">0.7</option>
				    <option value="1">1</option>
				    <option value="1.5">1.5</option>
				    <option value="2">2</option>
				    <option value="2.5">2.5</option>
				    <option value="3">3</option>
				    <option value="3.5">3.5</option>
				    <option value="4">4</option>
				    <option value="5">5</option>
				    <option value="6">6</option>
				    <option value="7">7</option>
				    <option value="8">8</option>
				</select>
			    </div>
			</div>		    
			<br>
		    @endif			
		</div>
		
		<div class="bottom-div col-md-8 col-lg-6 text-center swipe_on img_col order-1 order-md-2 bg-center pt-3 pb-1"> <!-- d-none d-md-block -->
		    <div class="wsODV_map_container"> <!-- show only above md -->		    
			<br>
			<div class="loading_snippet" id="init_loading"><h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1></div>
			<img class="img-fluid wsODV_map" id="wsODV_map" alt="" style="">
			<br>
			<br>
		    </div>
		    @if ($extractor_info['station_info_show'])
			<div class="emodnet-func-dark bottom-three">
			    Station info <i id=""  class="fa fa-question-circle-o help_color station_info_help" style="cursor:pointer;"></i>
			</div>
			<!-- <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center station_info" style="padding-right:14px;" id="">Cruise: <span style="float:right;"><i class="fa fa-caret-down"></i></span></button> -->
			<!-- <div id="" class="get_dock_metavar_labels_list" style="display:none; overflow-y: scroll; overflow-x: visible; height: 280px; z-index: 1000; position: relative; background-color: white;"></div> -->
			<div id="" class="get_dock_metavar_labels_list" style="">
			</div>			
			<br>
		    @endif		    
		</div>

		<div class="bottom-div col-lg-3 text-center order-1 order-md-2 d-none d-lg-block bg-right pt-3 pb-1"> 
		    <div class="emodnet-func-dark bottom-three order-1">
			Selection status
		    </div>
		    <div class="text-emodnet-blue container num_stations_div text-left  border border-primary rounded ml-0" style="margin-left:0px;">
			Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			<br>
			Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
		    </div>
		    <br>
		    <div class="emodnet-func-dark bottom-three order-2">
			Export image 
		    </div>
		    <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center download_image" style="padding-right:14px;" id="">Download</button>
		    <a class="" href="#" style="display:none;" id="download_image_a_page1" download="image.gif">
			<button id="download_image_button_page1"></button>		    
		    </a>			    
		    <br>
<div class="emodnet-func-dark bottom-three text-center">
			Data preview
		    </div>
		    <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center data_preview" style="padding-right:14px;" id="">
		    </button>
		    <br>
		    <div class="emodnet-func-dark bottom-three text-center">
			    Selected variables (<span class="treeview_mode_at_selected_variables"> OR </span>) <i class="fa fa-question-circle-o help_color selected_vars_info_help" style="cursor:pointer;"></i>
		    </div>
		    <div class="selected_variables_vars" style="height: 200px;">
			<h4 class="h4_selected_variables_vars"></h4>
		    </div>		    
		    <br>					    
		    <!-- if ($extractor_info['station_info_show']) -->
		    @if (false)
			<div class="emodnet-func-dark bottom-three">
			    Station info <i id=""  class="fa fa-question-circle-o help_color station_info_help" style="cursor:pointer;"></i>
			</div>
			<!-- <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center station_info" style="padding-right:14px;" id="">Cruise: <span style="float:right;"><i class="fa fa-caret-down"></i></span></button> -->
			<!-- <div id="" class="get_dock_metavar_labels_list" style="display:none; overflow-y: scroll; overflow-x: visible; height: 280px; z-index: 1000; position: relative; background-color: white;"></div> -->
			<div id="" class="get_dock_metavar_labels_list" style="">
			</div>			
			<br>
		    @endif
		</div>		    
	    </div>
	</div>

	<!-- required_vars modal -->
	<div class="modal fade" id="required_vars_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="exampleModalLabel">Required variables</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			<div class="treeview_div" style="">
			    <div class="row">
				<div class="col-lg-8">

				    <div class="container-fluid d-lg-none">
					<div class="row">
					    <div class="col-lg-3">
					    </div>
					    <div class="col-lg-3">
						<button class="btn btn-emodnet emodnet-orange-border btn-block text-center treeview_button_apply" style="" id="treeview_button_apply" >Apply</button>
						<br>
					    </div>
					    <div class="col-lg-3">
						<button class="btn btn-emodnet-grey btn-block text-center treeview_button_cancel" style="" id="treeview_button_cancel" >Cancel</button>
						<br>
					    </div>
					    <!-- <div class="col-lg-3">
						 <button class="btn btn-emodnet emodnet-orange-border btn-block text-center treeview_button_collapse" style="" id="treeview_button_collapse" >Collapse</button>
						 <br>
						 </div> -->
					    <div class="col-lg-3">
						<button class="btn btn-emodnet emodnet-orange-border btn-block text-center treeview_button_reset" style="" id="treeview_button_reset" >Deselect all</button>
						<br>
					    </div>
					</div>
				    </div>
				    <!-- search -->
				    <div class="dropdown">
					<div class="input-group">
					    <input id="search_input" type="text" class="form-control" placeholder="Search" onclick="this.select()" style="">
					    <span class="input-group-append" style="border-left:0">
						<button type="submit" id="search_button" style="background-color:#f8b334">
						    <i class="fa fa-search"></i>
						</button>
					    </span>
					    <ul class="dropdown-menu h-scroll" id="search_output">
					    </ul>
					</div>
				    </div>
				</div>
				<div class="col-lg-4 d-none d-lg-block">
				    <h4>Selected variables</h4>
				</div>
				<div class="col-lg-8">
				    <br>
				    <div class="treeview_container">
					@if ($project == "EMODnet Chemistry" && $contaminants == true)
					    <div class="treeview_container">
						{!! $contaminants_treeview !!}
					    </div>
					@else
					    <div id="treeview_container" class="hummingbird-treeview text-left" style="overflow-y: scroll; height: 400px;">
    						<ul id="treeview_requested_vars" class="hummingbird-base">
						</ul>
					    </div>
					@endif
				    </div>
				</div>
				<div class="col-lg-4 text-left selected_vars" style="height:200px;">
				    <div class="d-lg-none">
					<h4>Selected variables</h4>
				    </div>
				    <h4 class="h4_selected_variables"></h4>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="modal-footer">
			<div class="container-fluid d-none d-lg-block ">
			    <div class="row">
				<div class="col-lg-3">
				</div>
				<div class="col-lg-3">
				    <button class="btn btn-emodnet emodnet-orange-border btn-block text-center treeview_button_apply" style="" id="treeview_button_apply" >Apply</button>
				    <br>
				</div>
				<div class="col-lg-3">
				    <button class="btn btn-emodnet-grey btn-block text-center treeview_button_cancel" style="" id="treeview_button_cancel" >Cancel</button>
				    <br>
				</div>
				<!-- <div class="col-lg-3">
				     <button class="btn btn-emodnet emodnet-orange-border btn-block text-center treeview_button_collapse" style="" id="treeview_button_collapse" >Collapse</button>
				     <br>
				     </div> -->
				<div class="col-lg-3">
				    <button class="btn btn-emodnet emodnet-orange-border btn-block text-center treeview_button_reset" style="" id="treeview_button_reset" >Deselect all</button>
				    <br>
				</div>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	</div>
	


	<div class="modal fade" id="required_vars_help_modal" tabindex="-1" role="dialog" aria-labelledby="required_vars_helpModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="required_vars_helpModalLabel">Select required variables</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Select one or more variables from the <i>Required
			variables</i> tree to exclude stations not
			containing data for these variables. Only
			stations containing data for all selected <i>Required variables</i> are
			accepted and shown on the map. <span style="color:red;"><b>Warning:</b></span> Selecting too many
			required variables easily leads to zero matching stations.
			    @empty($output_var_extra_text)
			    @else
			{!! $output_var_extra_text !!}
			@endempty
			
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal"" style="" id="required_vars_help_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>

	<div class="modal fade" id="station_info_help_modal" tabindex="-1" role="dialog" aria-labelledby="station_info_helpModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="station_info_helpModalLabel">Station info</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Click on the map to select a station. Info about that station is shown below.
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal"" style="" id="station_info_help_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>

	<div class="modal fade" id="extractor_help_modal" tabindex="-1" role="dialog" aria-labelledby="extractor_helpModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="extractor_helpModalLabel">Help!</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Help
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal"" style="" id="extractor_help_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>

	<div class="modal fade" id="scatterplot_modal" tabindex="-1" role="dialog" aria-labelledby="scatterplotModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="scatterplotModalLabel">Processing time !</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Note that the station selection comprises <b><span id="scatterplot_num_samples"></span></b> samples
			for the creation of a scatterplot. This may take
			long.<br><br>
			Do you really want to continue?
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet emodnet-orange-border text-center"  style="" id="scatterplot_yes_button" >Yes</button>
			<button class="btn btn-emodnet emodnet-orange-border text-center" data-dismiss="modal" style="" id="scatterplot_no_button" >No</button>
		    </div>
		</div>
	    </div>
	</div>


	<div class="modal fade" id="extractor_eraser_modal" tabindex="-1" role="dialog" aria-labelledby="extractor_eraserModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="extractor_eraserModalLabel">Reset all settings!</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			This will reset all personal settings for <b>this dataset</b> like <i>Map domain</i>, <i>Required variables</i>, <i>Output variables</i>,
			visualization settings, etc. Do you really
			want to continue?
		    </div>
		    <div class="modal-footer">
			<div class="container-fluid d-none d-lg-block ">
			    <div class="row">
				<div class="col-lg-6">
				</div>
				<div class="col-lg-3">
				    <button class="btn btn-emodnet btn-block emodnet-orange-border text-center" data-dismiss="modal" style="" id="extractor_eraser_apply_button" >Apply</button>
				</div>
				<div class="col-lg-3">
				    <button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="extractor_eraser_close_button" >Cancel</button>
				</div>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	</div>

	<div class="modal fade" id="date_help_modal" tabindex="-1" role="dialog" aria-labelledby="date_helpModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="date_helpModalLabel">Select date</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Type a date in the format mm/dd/yyyy or use
			the calender widget. Range:
			01/01/1850-12/31/2021. Click on the widget's
			top line to switch between years, click again
			to switch between decades and so on.

		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="date_help_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>
	
	
	<div class="modal fade" id="inactivity_modal" tabindex="-1" role="dialog" aria-labelledby="inactivityModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="inactivityLabel">Session closed due to inactivity!</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Your session has been closed due to 1 h of inactivity. Please reload or leave the page.
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="inactivity_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>

	<div class="modal fade" id="zero_stations_modal" tabindex="-1" role="dialog" aria-labelledby="zero_stationsModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="zero_stationsLabel">No stations selected!</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Please go back to step <b>1. Select Cruises / Domain / Time Range</b> and <i>Zoom out</i> or click on <i>Reset</i> to
			select at least one station. Or go to step <b>2. Select Variables</b> and change your selection.</div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="inactivity_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>

	<!-- ##################### page1 ######################## -->



	

	<!-- ##################### page2 ######################## -->
	<div class="webodvextractor_page webodvextractor_page_2" style="display:none;">
	    <div class="row">
		<div class="col-md-2 text-center">
		</div>
		<div class="col-md-8 text-center">
		    Select variables from the treeview.
		    <br>
		</div>
		<div class="col-md-2 text-center">
		</div>
	    </div>
	    <br>
	    <div class="row">
		<div class="col-md-4 col-lg-3 text-center bg-left order-2 order-md-1 pt-3 pb-1">
		    <div class="d-lg-none">
			<div class="emodnet-func-dark bottom-three">
			    Selection status
			</div>
			<div class="text-emodnet-blue num_stations_div text-left  border border-primary rounded pl-2" style="">
			    Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			    <br>
			    Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
			</div>
			<br>
			<div class="emodnet-func-dark bottom-three text-center">
			    Data preview
			</div>
			<button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center data_preview" style="padding-right:14px;" id=""></button>
			<br>					    
			<div class="emodnet-func-dark bottom-three text-center">
			    Selected variables (<span class="treeview_mode_at_selected_variables"> OR </span>) <i class="fa fa-question-circle-o help_color selected_vars_info_help" style="curso\
r:pointer;"></i>
			</div>
			<div class="selected_variables_vars" style="height: 200px;">
			    <h4 class="h4_selected_variables_vars"></h4>
			</div>
			<br>
		    </div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Logic <i id=""  class="fa fa-question-circle-o help_color treeview_logic_help" style="cursor:pointer;"></i>
			</div>
			<div class="row">
			    <div class="col-md-1">
			    </div>
			    <div class="col-5">
				<input class="form-check-input" type="radio" name="treeview_mode" id="treeview_mode_or" value="0" checked>
				<label class="form-check-label" for="treeview_mode_or">
				    OR
				</label>
			    </div>
			    <div class="col-5">
				<input class="form-check-input" type="radio" name="treeview_mode" id="treeview_mode_and" value="1">
				<label class="form-check-label" for="treeview_mode_and">
				    AND
				</label>
			    </div>
			    <div class="col-md-1">
			    </div>
			</div>
			<br>
		    <div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Treeview
			</div>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center" id="collapse_all">Collapse all</button>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center" id="expand_all">Expand all</button>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center" id="check_all">Check all</button>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center" id="uncheck_all">Uncheck all</button>			    			    
		    </div>
		    <br>
		    <div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Reset
			</div>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center extractor_eraser" id="" data-toggle="tooltip" data-placement="auto" title="Resets the complete extractor for this dataset.">Global</button>			    
		    </div>
		    <br>
		    
		    <!-- <button class="btn btn-emodnet emodnet-orange-border btn-block text-left" style="" id="treeview_button_collapse_vars" >Collapse</button> -->
		    <!-- <div class="emodnet-func-dark-blue bottom-three">
			 Select all
			 </div>
			 <button class="btn btn-emodnet emodnet-orange-border btn-block text-center" style="" id="treeview_button_reset_vars" data-toggle="tooltip" data-placement="auto" title="Select all available variables.">Select all</button>
			 <br> -->
		</div>
		<div class="col-md-5 col-lg-6 col-md-8 order-1 order-md-2 text-left bg-center pt-3 pb-1">
		    <div class="emodnet-func-dark-blue bottom-three text-center">
			Variables <i class="fa fa-question-circle-o help_output_var" style="cursor:pointer;"></i>			
		    </div>
		    
		    <div class="row img_col">
			<div class="col-4 page2_map_left">
			</div>
			<div class="col-4 wsODV_map_container align-self-center text-center page2_map pt-2">
			    <!-- <br> -->
			    <img class="img-fluid wsODV_map" id="wsODV_map_viz" alt="" style="">
			    <!-- <br> -->
			</div>
			<div class="col-4 align-self-center text-center page2_map_right pl-0">
			    <button class="btn btn-responsive btn-emodnet emodnet-orange-border text-center map_small" data-toggle="tooltip" data-placement="auto" title="Increase image size" style="padding-right:14px;" id="page2_button_enlarge"><i class="fa fa-expand"></i></button>
			</div>
		    </div>
		    <br>

		    <div class="col-md-8 offset-md-2 show_loading_here text-center" style="display:none">
			<br><br><br><br>
			<div style="border: 3px solid rgb(170, 170, 170); background-color:white;">
			    <h1><i class="fa fa-refresh fa-spin fa-fw"></i> Loading </h1>
			</div>
		    </div>
		    <div class="output_treeview_col" style="">
			<div class="dropdown">
			    <div class="input-group">
				<input id="search_input_vars" type="text" class="form-control " placeholder="Search" onclick="this.select()" style="">
				<span class="input-group-append" style="border-left:0">
				    <button type="submit" id="search_button_vars" style="color:black; background-color:#f8b334;">
					<i class="fa fa-search"></i>
				    </button>
				</span>
				<ul class="dropdown-menu h-scroll" id="search_output_vars">
				</ul>
			    </div>
			</div>
			<br>
			<!-- contamination test -->
			@if ($project == "EMODnet Chemistry" && $contaminants == true)
			    <div class="treeview_div_vars">
				{!! $contaminants_treeview !!}
			    </div>
			@else
			    <!-- default -->		    
			    <div class="treeview_div_vars">
				<div id="treeview_container_vars" class="hummingbird-treeview text-left" style="overflow-y: scroll; height: 400px;">
    				    <ul id="treeview_vars" class="hummingbird-base">
				    </ul>
				</div>
			    </div>
			@endif
			<br>
		    </div>
		</div>
		<div class="col-md-3 text-left bg-right  order-1 order-md-2 d-none d-lg-block pt-3 pb-1">
		    <div class="emodnet-func-dark bottom-three text-center">
			Selection status
		    </div>
		    <div class="text-emodnet-blue num_stations_div text-left  border border-primary rounded pl-2" style="">
			Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			<br>
			Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
		    </div>
		    <br>
		    <div class="emodnet-func-dark bottom-three text-center">
			Data preview
		    </div>
		    <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center data_preview" style="padding-right:14px;" id=""></button>
		    <br>
		    <div class="emodnet-func-dark bottom-three text-center">
			    Selected variables (<span class="treeview_mode_at_selected_variables"> OR </span>) <i class="fa fa-question-circle-o help_color selected_vars_info_help" style="curso\
r:pointer;"></i>
		    </div>
		    <div class="selected_variables_vars" style="height: 200px;">
			<h4 class="h4_selected_variables_vars"></h4>
		    </div>
		</div>
	    </div>


	    <div class="modal fade" id="treeview_logic_help_modal" tabindex="-1" role="dialog" aria-labelledby="treeview_logic_helpModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
			<div class="modal-header">
			    <h4 class="modal-title" id="treeview_logic_helpModalLabel">Station filter - Availability</h4>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			    </button>
			</div>
			<div class="modal-body">
			    <span style="color:#0a71b4;"><b>"OR" option</b></span>: Show all stations, which have data for one <b>OR</b> more of the selected variables available.
			    <br><br>
			    <span style="color:#0a71b4;"><b>"AND" option</b></span>: Show all stations, which have data for <b>ALL</b> of the selected variables available.
			    <br><br>
			    <!-- Note that the so called ODV <i>primary variable</i> (mostly depth, pressure, or altitude) is left out here, because it exists per definition for all stations. -->
			</div>
			<div class="modal-footer">
			    <button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal"" style="" id="" >Close</button>
			</div>
		    </div>
		</div>
	    </div>

	    <div class="modal fade" id="treeview_AND_help_modal" tabindex="-1" role="dialog" aria-labelledby="treeview_AND_helpModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
			<div class="modal-header">
			    <h4 class="modal-title" id="treeview_AND_helpModalLabel">AND - mode !</h4>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			    </button>
			</div>
			<div class="modal-body">
			    <span style="color:red;"><b>Warning:</b></span> Selecting too many
			    variables in <b>"AND"</b> mode easily leads to zero matching stations.
			</div>
			<div class="modal-footer">
			    <button class="btn btn-emodnet emodnet-orange-border text-center AND_mode_info" data-dismiss="modal" style="" id="AND_mode_not_show_again" >Do not show again.</button>
			    <button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal"" style="" id="" >Close</button>
			</div>
		    </div>
		</div>
	    </div>

	    

	    <div class="modal fade" id="output_vars_help_modal" tabindex="-1" role="dialog" aria-labelledby="output_vars_helpModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
			<div class="modal-header">
			    <h4 class="modal-title" id="output_vars_helpModalLabel">Select variables</h4>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			    </button>
			</div>
			<div class="modal-body">
			    <ul>
				<li>
				    Select one or more variables from
				    the treeview for download.
				</li>

				<li>
				    Important: Selecting variables changes the selected stations in the map.
				</li>
				<li>
				    Choose one of the options (left under LOGIC):
				    <br>
				    <span style="color:#0a71b4;"><b>"OR" option</b></span>: Show all stations, which have data for one <b>OR</b> more of the selected variables available.
				    <br>
				    <span style="color:#0a71b4;"><b>"AND" option</b></span>: Show all stations, which have data for <b>ALL</b> of the selected variables available.
				</li>

				<li>
				    A non-checked grayed
				    variable means that it is not available in the
				    current map.
				</li>
				<li>
				    A checked grayed
				    variable means that it is mandatory for download.
				</li>
			    @empty($output_var_extra_text)
			    @else
				<li>
				    {!! $output_var_extra_text !!}
				</li>
				@endempty
				@if ($project == "EMODnet Chemistry" && $contaminants == true)
				<li>
				    Dev-mode: <input type="password" id="variables_dev_mode" class="" placeholder="Password">
				</li>
				@endif
			</div>
			<div class="modal-footer">
			    <button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal"" style="" id="output_vars_help_close_button" >Close</button>
			</div>
		    </div>
		</div>
	    </div>



	</div>
	<!-- ##################### page2 ######################## -->



	<div class="modal fade" id="emodnet_download_reason_modal" tabindex="-1" role="dialog" aria-labelledby="emodnet_download_reasonModalLabel" aria-hidden="true">
	    <div class="modal-dialog  modal-lg" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h5 class="modal-title" id="emodnet_download_reasonModalLabel">Please share with us the reason for downloading this data.</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			<form id="data_usage_form" style="" action="">
			    <!-- first -->
                            <div class="form-group row">
				<label for="select_data_usage" class="col-md-3 col-form-label text-md-right"><b>Data usage:</b></label>
				<div class="col-md-9">
				    <div class="input-group-btn btn-block">
					<div class="selectpicker_wrapper data_usage pb-2">
					    <select class="selectpicker form-control" id="select_data_usage" data-style="btn-emodnet emodnet-orange-border btn-block btn btn-responsive"  data-selected-text-format="count" title="Select one of the following ...">
						<option value="1">Commercial/Industry</option>
						<option value="2">Education/Research</option>
						<option value="3">Personal use</option>
						<option value="4">Policy making</option>
						<option value="5">Other</option>
						<option data-divider="true"></option>
					    </select>
					</div>
				    </div>	    
				    <div class="form-group row pl-3 pr-3">
					<input type="text" style="display:none;" id="data_usage_input" class="form-control" aria-label="Data usage" placeholder="Please specify">
				    </div>
				    <div class="text-center" id="data_usage_error" style="color:red;font-weight:bold;">
				    </div>
				</div>			    
			    </div>
			    <!-- next -->
			    <div class="form-group row pb-4">
				<label for="select_orga_input" class="col-md-3 col-form-label text-md-right"><b>Organization name:</b></label>
				<div class="col-md-9">
				    <input type="text" style="" id="select_orga_input" class="form-control" aria-label="Data usage" placeholder="Please specify" disabled>
				</div>
			    </div>
			    <!-- next -->
			    <div class="form-group row">
				<label for="select_orga_input" class="col-md-3 col-form-label text-md-right"><b>Type of organization:</b></label>
				<div class="col-md-9">
				    <div class="input-group-btn btn-block">
					<div class="selectpicker_wrapper data_usage pb-2">
					    <select class="selectpicker form-control " id="select_orga" data-style="btn-emodnet emodnet-orange-border btn-block btn btn-responsive"  data-selected-text-format="count" title="Select one of the following ...">
						<option value="1">Academia/Research</option>
						<option value="2">Government/Public Administration</option>
						<option value="3">Business and Private Company</option>
						<option value="4">NGOs/Civil Society</option>
						<option value="5">Other</option>
						<option data-divider="true"></option>
					    </select>
					</div>
				    </div>
				    <div class="text-center" id="select_orga_error" style="color:red;font-weight:bold;">
				    </div>			    				    
				</div>
			    </div>
			</form>
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet emodnet-orange-border"  style="" id="emodnet_download_reason_start_download" >Start processing</button>
			<button class="btn btn-emodnet-grey" data-dismiss="modal" style="" id="emodnet_download_reason_close_button" >Cancel</button>
		    </div>
		</div>
	    </div>
	</div>



	
	<!-- ##################### page3 ######################## -->
	<!-- <div class="webodvextractor_page webodvextractor_page_3" style="display:none;"> -->
	<div class="data_preview_page webodvextractor_page_99" style="display:none;">
	    <div class="row">
		<div class="col-md-2 text-center">
		</div>
		<div class="col-md-8 text-center">
		    <button class="btn btn-emodnet emodnet-orange-border text-center data_preview_go_back" style="" id="" >Go back</button>
		    <br>
		    Select <i>Axes variables</i> and <i>Axes ranges</i>.
		    <br>
		</div>
		<div class="col-md-2 text-center">
		</div>		
	    </div>
	    <br>
	    <div class="row">
		<div class="col-md-4 col-lg-3 text-center order-2 order-md-1 bg-left pt-3 pb-1">	    
		    <div class="d-lg-none">
			<div class="emodnet-func-dark bottom-three">
			    Selection status
			</div>
			<div class="text-emodnet-blue num_stations_div text-left  border border-primary rounded pl-2" style="">
			    Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			    <br>
			    Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
			</div>
			<br>
			<div class="emodnet-func-dark bottom-three">
			    Export image 
			</div>
			<button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center download_image" style="padding-right:14px;" id="">Download</button>
			<a class="" href="#" style="display:none;" id="download_image_a_page99" download="image.gif">
			    <button id="download_image_button_page99"></button>		    
			</a>			    
			<br>
		    </div>
		    <div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Axes variables <i class="fa fa-question-circle-o help_color axes_variables_question" style=""></i>
			</div>
			<!-- <div class="text-left">X-Variable</div> -->
			<!-- <button class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border text-center" id="set_x_var">X-Variable</button> -->
			<div class="row bottom-three flex-nowrap">
			    <div class="col-sm-2" style="padding-top:6px; padding-left:25px;">
				X:
			    </div>
			    <div class="col-sm-8">
				<div class="selectpicker_wrapper text-center">
				    <select class="selectpicker form-control text-center change_viz bg-left" id="set_x_var" data-style="btn text-center btn-responsive btn-block btn-emodnet-grey" data-live-search="true" data-size="4">
					<option value="0.1">ITS-90 water temperature</option>
					<option value="0.5">Water body salinity</option>
					<option value="0.7">...</option>
				    </select>
				</div>
			    </div>
			    <div class="col-sm-2 text-center" data-toggle="tooltip" data-placement="auto" title="Reverse axis!">
				<input type="checkbox" name="reversed_axis_checkbox" class="form-control reversed_axis_checkbox" id="reversed_axis_checkbox_x">				
			    </div>			    			    
			</div>
			<!-- <br> -->
			<!-- <div class="text-left">Y-Variable</div> -->
			<!-- <button class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border text-center" id="set_y_var">Y-Variable</button> -->
			<div class="row  flex-nowrap">
			    <div class="col-sm-2 " style="padding-top:6px; padding-left:25px;">
				Y:
			    </div>
			    <div class="col-sm-8">
				<div class="selectpicker_wrapper">
				    <select class="selectpicker form-control bg-left" id="set_y_var" data-style="btn btn-responsive btn-block btn-emodnet-grey" data-live-search="true" data-size="4">
					<option value="0.1">Depth</option>
					<option value="0.1">ITS-90 water temperature</option>
					<option value="0.5">Water body salinity</option>
					<option value="0.7">...</option>
				    </select>
				</div>
			    </div>
			    <div class="col-md-2 text-center" data-toggle="tooltip" data-placement="auto" title="Reverse axis!">
				<input type="checkbox" name="reversed_axis_checkbox" class="form-control reversed_axis_checkbox" id="reversed_axis_checkbox_y">				
			    </div>			    
			    
			</div>		
			<br>
			<!-- <div class="text-left">X-Range </div> -->
			<div class="emodnet-func-dark-blue bottom-three">
			    Axes ranges <i class="fa fa-question-circle-o help_color axes_ranges_question" style=""></i>
			</div>
			<div class="row  flex-nowrap">
			    <div class="col-sm-2" style="padding-top:30px; padding-left:25px;">
				X:
			    </div>
			    <div class="col-md-5 mb-2 text-center">
				left:
				<input type="text" name="xx" class="form-control btn btn-responsive btn-block btn-outline-secondary text-center range" id="x_range_from" value="0">
				<!-- data-toggle="tooltip" data-placement="auto" title="Set range with maximum three decimal places." -->
			    </div>
			    <div class="col-md-5 mb-2 text-center">
				right:
				<input type="text" name="xx" class="form-control btn btn-responsive btn-block btn-outline-secondary text-center range" id="x_range_to" value="20">				
			    </div>
			</div>
			<!-- <br> -->
			<!-- <div class="text-left">Y-Range <i id="required_variables_help"  class="fa fa-question-circle-o help_color" style="" data-toggle="tooltip" data-placement="auto" title="Set range with maximum three decimal places."></i></div>			 -->
			<div class="row  flex-nowrap">
			    <div class="col-sm-2" style="padding-top:30px; padding-left:25px;">
				Y:
			    </div>
			    <div class="col-md-5 mb-2 text-center order-1" id="y_minimum">
				<span id="y_minimum_text">bottom:</span>
				<input type="text" name="xx" class="form-control btn btn-responsive btn-block btn-outline-secondary text-center range" id="y_range_from" value="0">
			    </div>
			    <div class="col-md-5 mb-2 text-center order-2" id="y_maximum">
				<span id="y_maximum_text">top:</span>
				<input type="text" name="xx" class="form-control btn btn-responsive btn-block btn-outline-secondary text-center range" id="y_range_to" value="150">
			    </div>
			</div>			
		    </div>
		    <br>
		    <div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Full range <i class="fa fa-question-circle-o help_color axes_full_range_question" style=""></i>
			</div>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center" id="reset_p3">Full range</button>
		    </div>
		    <br>
		    <div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Outliers <i class="fa fa-question-circle-o help_color" id="outliers_help"></i>
			</div>
			<div class="" style="background-color:#f2f2f2; ;border-style:solid; border-color:#333333; border-radius:0.25rem; border-width:1px; padding-top:8px;padding-bottom:8px;">
			    <div class="form-check">
				<input class="form-check-input" type="checkbox" value="" id="outliers">
				<label class="form-check-label" for="outliers">
				    Hide Outliers
				</label>
			    </div>
			</div>			    
			<!-- <button class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border text-center" id="outliers">Outliers</button> -->
		    </div>
		    <br>
		    <div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Reset
			</div>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center extractor_eraser" id="" data-toggle="tooltip" data-placement="auto" title="Resets the complete extractor for this dataset.">Global</button>			    
		    </div>
		    <br>		    
		    <!-- endif -->
		</div>
		<div class="bottom-div col-md-8 col-lg-6 text-center swipe_on img_col order-1 order-md-2 bg-center pt-3 pb-1"> 
		    <div class="wsODV_map_container">
			<br>
			<img class="img-fluid wsODV_map" id="wsODV_map_viz" alt="" style="">
			<br>
		    </div>
		</div>

		<div class="bottom-div col-lg-3 text-center order-1 order-md-2 d-none d-lg-block bg-right pt-3 pb-1">
		    <div class="emodnet-func-dark bottom-three">
			Selection status
		    </div>
		    <div class="text-emodnet-blue num_stations_div text-left  border border-primary rounded pl-2" style="">
			Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			<br>
			Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
		    </div>
		    <br>
		    <div class="emodnet-func-dark bottom-three">
			Export image 
		    </div>
		    <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center download_image" style="padding-right:14px;" id="">Download</button>
		    <a class="" href="#" style="display:none;" id="download_image_a_page99" download="image.gif">
			<button id="download_image_button_page99"></button>		    
		    </a>			    
		    <br>
		    <div class="emodnet-func-dark bottom-three text-center">
			Selected variables (<span class="treeview_mode_at_selected_variables"> OR </span>) <i class="fa fa-question-circle-o help_color selected_vars_info_help" style="curso\
r:pointer;"></i>
		    </div>
		    <div class="selected_variables_vars" style="height: 200px;">
			<h4 class="h4_selected_variables_vars"></h4>
		    </div>
		    
		</div>

		
	    </div>
	</div>
	<!-- ##################### page3 ######################## -->



	<!-- ##################### page4 ######################## -->
	<div class="webodvextractor_page webodvextractor_page_3" style="display:none;">
	    <div class="row">
		<div class="col-md-2 text-center">
		</div>
		<div class="col-md-8 text-center">
		    Download data in different formats. <!-- You will be notified by email when the .zip file containing the data in the format of your choice is ready. -->
		    <br>
		</div>
		<div class="col-md-2 text-center">
		</div>
	    </div>
	    <br>
	    <div class="row">
		<div class="col-md-4 col-lg-3 text-center order-2 order-md-1 bg-left pt-3 pb-1">	    		
		    <div class="d-lg-none">
			<div class="emodnet-func-dark bottom-three">
			    Selection status
			</div>
			<div class="text-emodnet-blue num_stations_div text-left  border border-primary rounded pl-2" style="">
			    Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			    <br>
			    Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
			</div>
			<br>
			<div class="emodnet-func-dark bottom-three text-center">
			    Data preview
			</div>
			<button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center data_preview" style="padding-right:14px;" id=""></button>
			<br>			
		    </div>
		    <div>
			<div class="emodnet-func-dark-blue bottom-three">
			    Download
			</div>
			<div class="form-group" id="download_group">
			    <a id="geotr_download" target="_blank" download>
				<input type="button" style="display: none;" class="btn" id="start_download_input">
			    </a> 
			    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center" id="sel_txt" value="ASCII Spreadsheet (.txt)">		    
			</div>
			@if ($project == "EMODnet Chemistry" && $contaminants == true)
			<div class="form-group">
			    <a download>
				<input type="button" style="display: none;" class="btn" id="">
			    </a> 
			    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center" id="sel_trans" value="Transp. Spreadsheet (.txt)">
			</div>
			@endif
			<div class="form-group">
			    <a download>
				<input type="button" style="display: none;" class="btn" id="selodv">
			    </a> 
			    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center" id="sel_odv" value="ODV Collection (.odv)">
			</div>
			<div class="form-group">
			    <a download>
				<input type="button" style="display: none;" class="btn" id="selnc">
			    </a> 
			    <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center" id="sel_nc" value="netCDF (.nc)">
			</div>
			<br>
		    </div>
		    <div>			
			<div class="emodnet-func-dark-blue bottom-three">
			    Reset
			</div>
			<button class="btn btn-responsive btn-block btn-emodnet-grey text-center extractor_eraser" id="" data-toggle="tooltip" data-placement="auto" title="Resets the complete extractor for this dataset.">Global</button>			    
		    </div>
		    <br>
		</div>
		<br>

		<!-- <div class="bottom-div  order-1 order-md-2 bg-center pt-3 pb-1">  -->

		<!-- <div class="col-md-1 text-left">
		     </div>
		   -->

		<div class="col-md-8 col-lg-6 text-center bottom-div  img_col order-1 order-md-2 bg-center pt-3 pb-1">


		    			<img class="img-fluid wsODV_map" id="wsODV_map" alt="" style="">
		    
		    <!-- <div class="row">
			 <div class="col-md-1">
			 </div>
			 <div class="col-md-10">
			 <div class="emodnet-func-dark-blue bottom-three text-center">
			 Download
			 </div>

			 <div class="form-group" id="download_group">
			 <a id="geotr_download" target="_blank" download>
			 <input type="button" style="display: none;" class="btn" id="start_download_input">
			 </a> 
			 <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center" id="sel_txt" value="ASCII Spreadsheet (.txt)">		    
			 </div>
			 <div class="form-group">
			 <a download>
			 <input type="button" style="display: none;" class="btn" id="selodv">
			 </a> 
			 <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center" id="sel_odv" value="ODV Collection (.odv)">
			 </div>
			 <div class="form-group">
			 <a download>
			 <input type="button" style="display: none;" class="btn" id="selnc">
			 </a> 
			 <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center" id="sel_nc" value="netCDF (.nc)">
			 </div>
			 </div>
			 <div class="col-md-1">
			 </div>
			 </div> -->
		    <!-- <div class="form-group">
			 <a download>
			 <input type="button" style="display: none;" class="btn" id="selcsv">
			 </a> 
			 <input type="button" class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border myDownloadButton text-center"  id="sel_csv" value="WHP Exchange">
			 </div> -->
		    <!-- </div> -->
		    <!-- <div class="col-md-1 text-left">
			 </div> -->
		</div>

		<div class="bottom-div col-lg-3 text-center order-1 order-md-2 d-none d-lg-block bg-right pt-3 pb-1">
		    <div class="emodnet-func-dark bottom-three">
			Selection status
		    </div>
		    <div class="text-emodnet-blue num_stations_div text-left  border border-primary rounded pl-2" style="">
			Stations:  &nbsp; <span class="num_stations"></span> of <span class="num_stations_total"></span>
			<br>
			Output variables:  &nbsp; <span class="num_variables"></span><span class="the_of"> of </span><span class="num_variables_total"></span>
		    </div>
		    <br>
		    <div class="emodnet-func-dark bottom-three text-center">
			Data preview
		    </div>
		    <button class="text-emodnet-blue btn btn-responsive btn-block btn-emodnet-grey text-center data_preview" style="padding-right:14px;" id=""></button>
		    <br>
		    <div class="emodnet-func-dark bottom-three text-center">
			Selected variables (<span class="treeview_mode_at_selected_variables"> OR </span>) <i class="fa fa-question-circle-o help_color selected_vars_info_help" style="curso\
r:pointer;"></i>
		    </div>
		    <div class="selected_variables_vars" style="height: 200px;">
			<h4 class="h4_selected_variables_vars"></h4>
		    </div>
		</div>
		
		<div class="modal fade" id="download_help_modal" tabindex="-1" role="dialog" aria-labelledby="download_helpModalLabel" aria-hidden="true">
		    <div class="modal-dialog" role="document">
			<div class="modal-content">
			    <div class="modal-header">
				<h4 class="modal-title" id="download_helpModalLabel">Download</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				</button>
			    </div>
			    <div class="modal-body">
				
			    </div>
			    <div class="modal-footer">
				<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="download_help_close_button" >Close</button>
			    </div>
			</div>
		    </div>
		</div>

		<div class="modal fade" id="auth_help_modal" tabindex="-1" role="dialog" aria-labelledby="auth_helpModalLabel" aria-hidden="true">
		    <div class="modal-dialog" role="document">
			<div class="modal-content">
			    <div class="modal-header">
				<h4 class="modal-title" id="auth_helpModalLabel">Info !</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				</button>
			    </div>
			    <div class="modal-body">
				Downloading EMODnet Chemistry data via <i>webODV</i> requires authentication by Marine-ID. The authentication procedure
				starts on clicking the download button. By following the authentication, you allow <i>webODV</i> to
				access your name and email address, provided by Marine-ID, to create a <i>webODV</i> account. </br></br>
				Additionally Marine-ID will place a login cookie in your browser. Please consider the
				Marine-ID's <a href="https://www.marine-id.org/"><span style="color:blue;">terms of use</span></a> for more details.
			    </div>
			    <div class="modal-footer">
				<button class="btn btn-emodnet emodnet-orange-border text-center" data-dismiss="modal" style="" id="auth_help_not_show_again" >Do not show again.</button>
				<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="auth_help_close_button" >Close</button>
			    </div>
			</div>
		    </div>
		</div>
		
		<!-- <iframe src="" id="marine_id_iframe" name="marine_id_iframe" height="100%" width="100%" style="" scrolling="yes"></iframe> -->
		
		<div class="modal fade" id="marine_id_modal" tabindex="-1" role="dialog" aria-labelledby="marine_idModalLabel" aria-hidden="true">
		    <div class="modal-dialog" role="document">
			<div class="modal-content">
			    <div class="modal-header">
				<h4 class="modal-title" id="marine_idModalLabel">Authenticate with Marine-ID</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				</button>
			    </div>
			    <div class="modal-body">
				<div class="text-center" id="marine_id_modal_loading" style="width:100%;">
				</div>
				<a class="" href="{{ $marine_id_login }}" style="display:none;" id="marine_id_a" target="marine_id_iframe">
				    <button id="marine_id_button" style="display:non;">link</button>
				</a>
				<iframe src="" id="marine_id_iframe" name="marine_id_iframe" height="200px" width="100%" style="border:none;" scrolling="yes"></iframe>
				<!-- https://users.marine-id.org/login?service=http://localhost -->
			    </div>
			    <div class="modal-footer">
				<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="download_help_close_button" >Close</button>
			    </div>
			</div>
		    </div>
		</div>


	    </div>
	</div>



	<div class="modal fade" id="hide_outliers_modal" tabindex="-1" role="dialog" aria-labelledby="hide_outliersModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="hide_outliersModalLabel">Outliers !</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Activate the check box to hide outliers, which
			have been identified / flagged in the ODV
			collection as "bad" or "probably bad" data.
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="hide_outliers_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>


	<div class="modal fade" id="axes_ranges_modal" tabindex="-1" role="dialog" aria-labelledby="axes_rangesModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="axes_rangesModalLabel">Axes ranges</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Set range with maximum three decimal places.
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="axes_ranges_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>

	<div class="modal fade" id="axes_variables_modal" tabindex="-1" role="dialog" aria-labelledby="axes_variablesModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="axes_variablesModalLabel">Axes variables</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Select axes variables. Only variables which
			have been selected in step "2. Select
			variables" are listed, in addition to the
			primary variable, which is mostly depth, pressure, altitude or time.
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="axes_variables_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>
	
	<div class="modal fade" id="axes_full_range_modal" tabindex="-1" role="dialog" aria-labelledby="axes_full_rangeModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="axes_full_rangeModalLabel">Full range</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			Set X and Y axes of the selected variables to cover the full data range.
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="axes_full_range_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>


	<div class="modal fade" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="errorModalLabel">Error !</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			An error occurred, please reload the page.
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal" style="" id="error_close_button" >Close</button>
		    </div>
		</div>
	    </div>
	</div>


	@if ($project == "GEOTRACES")
	<div class="modal fade " id="geotraces_download_help_modal" tabindex="-1" role="dialog" aria-labelledby="geotraces_download_helpModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<h4 class="modal-title" id="geotraces_download_helpModalLabel">Download</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
		    </div>
		    <div class="modal-body">
			<form id="data_usage_form" style="" action="">
			    <h4>Please describe intended data usage</h4>
			    <!-- <div class="input-group"> -->

			    <div class="input-group">
				<div class="input-group-btn">
				    <div class="selectpicker_wrapper data_usage">
					<select class="selectpicker form-control " id="select_data_usage" data-style="btn-outline-secondary btn-block btn btn-responsive"  multiple data-selected-text-format="count">
					    <option value="">Compare with my data</option>
					    <option value="">Create compilation with other data for the same element or isotope</option>
					    <option value="">Do cross correlations among different elements or isotopes</option>
					    <option value="">Modelling</option>
					    <option value="">Use of data for biological studies</option>
					    <option value="">Use of data for paleoproxy research</option>
					    <option value="">Teaching</option>
					    <option data-divider="true"></option>
					    <option  disabled value="">Other? Please specify</option>				    
					</select>
				    </div>
				</div>
				<input type="text" id="data_usage_input" class="form-control" aria-label="Data usage" placeholder="Specify data usage or select from dropdown">
			    </div>

			    
			    <!-- </div> -->
			    <br>
			    <h4>Please describe your funding source</h4>
			    <input  class="form-control" type="text" value="" name="funding_source" id="funding_source" placeholder="Funding source" required autofocus>
			    <br>
			    <h4>Please accept the Data Usage Agreement</h4>
			    <div class="jumbotron" style="padding: 5px">
    				@php
				require_once public_path() . '/GEOTRACES_usage_agreement_2017.txt';
				@endphp
			    </div>
			    <div class="text-center" style="">
				<h4>
				    <label class="" style="">
					<input id="data_agree" name="data_agree"  type="checkbox" value="1" required>
					<span style="">I Agree</span>
					<br>
					<div id="data_usage_form_error" style="color:a94442;"></div>
				    </label>
				</h4>
			    </div>
			</form>
			
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-outline-success text-left"  style="" id="geotraces_start_download" >Start download</button>
			<button class="btn btn-outline-secondary text-left" data-dismiss="modal" style="" id="geotraces_download_help_close_button" >Cancel</button>
		    </div>
		</div>
	    </div>
	</div>
	@endif


	<!-- ##################### page3 ######################## -->

	<!-- ##################### page4 ######################## -->
	<div class="webodvextractor_page webodvextractor_page_4 swipe_on" style="display:none;">
	    <div class="row">
		<div class="col-md-2 text-center">
		</div>
		<div class="col-md-8 text-center">
		    Leave the <i>Data Extractor</i>. The current selections will be saved and restored once you login again.
		    <br>
		</div>
		<div class="col-md-2 text-center">
		</div>
	    </div>
	    <br>
	    <div class="row">
		<div class="col-md-4  text-center order-2 order-md-1 bg-left pt-3 pb-1">	    		
		</div>
		<div class="col-md-4 text-center bottom-div order-1 order-md-2 bg-center pt-3 pb-1">
		    <a href="{{ url('/') }}">
			<button class="btn btn-responsive btn-block btn-emodnet emodnet-orange-border" style="" id="exit_button">Exit</button>
		    </a>
		    <br>
		</div>
		<div class="bottom-div col-md-4 text-center order-1 order-md-2 bg-right pt-3 pb-1">
		</div>
	    </div>
	</div>
	<!-- ##################### page4 ######################## -->



	    <div class="modal fade" id="and_or_help_modal" tabindex="-1" role="dialog" aria-labelledby="and_or_helpModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
			<div class="modal-header">
			    <h4 class="modal-title" id="and_or_helpModalLabel">Data - Availability</h4>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			    </button>
			</div>
			<div class="modal-body">
			    Variable availability is in the <b><span class="treeview_mode_at_selected_variables"> OR </span></b> mode.
			    Choose the mode on page "2. Select
			    variables". Stations on the map are shown
			    that have either data for all variables
			    (ALL mode) or data for one or more variables (OR mode).
			</div>
			<div class="modal-footer">
			    <button class="btn btn-emodnet-grey btn-outline-secondary text-center" data-dismiss="modal"" style="" id="" >Close</button>
			</div>
		    </div>
		</div>
	    </div>


    </div>
@endsection


