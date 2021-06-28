@extends('layouts.webodv_layout')

@section('content')
<div class="container parallax" style="background-image: url({{ url('images/ocean_bg_1s.jpg') }});">
    <div class="row justify-content-center">
        <div class="col-md-10">
	    <div class="text-center">
	    <h2>Welcome to webODV</h2>
	    </div>
	    webODV provides online Ocean Data View (ODV, <a href="https://odv.awi.de" target="_blank">https://odv.awi.de</a>) services like
	    the extraction, analysis, exploration and visualization of
	    oceanographic and other environmental data.

	    webODV is developed by <a href="https://www.awi.de/nc/ueber-uns/organisation/mitarbeiter/sebastian-mieruch-schnuelle.html" target="_blank">Dr. Sebastian Mieruch-Schnülle</a> and <a href="https://www.awi.de/nc/ueber-uns/organisation/mitarbeiter/reiner-schlitzer.html" target="_blank">Prof. Dr. Reiner Schlitzer </a>at the Alfred Wegener Institute (<a href="http://awi.de" target="_blank">AWI</a>)
	    in Bremerhaven, Germany.
	    <br><br>
	    Use the top left breadcrumb trail for fast navigation.

	    <br>
	    <br>
	    <h4 class="text-info">{!! $index_add_text !!}</h4>
	    <br>
	    <div class="card" id="treeview_card" style="min-height:300px; opacity: 1;">
                <div class="card-header" style="background-color:#647878; color:white;">Choose one of the following datasets:
		</div>
                <div class="card-body" id="treeview_card_body" style="display:none;">
		    @if ($treeview_search)
		    <div class="row">
			<div class="col-md-12">
			    <div class="dropdown">
				<div class="input-group">
				    <input id="search_input" type="text" class="form-control" placeholder="Search" onclick="this.select()" style="border-color:rgb(160,160,160)">
				    <span class="input-group-append" style="border-color:red;">
					<button type="submit" id="search_button" style="color:white; background-color:#1e6482;border-width:0px; border-color:#ced4da; border-top-right-radius: 0.25rem; border-bottom-right-radius: 0.25rem">
					    <i class="fa fa-search"></i>
					</button>
				    </span>
				    <ul class="dropdown-menu h-scroll" id="search_output">
				    </ul>
				</div>
			    </div>
			</div>
		    </div>
		    <br>
		    @endif
		    <div style="opacity: 1;">
			{!! $treeview !!}
		    </div>
		</div>
            </div>
	    		    <br>
		    <br>

	</div>
    </div>
    <span>Photo by <a href="https://unsplash.com/@lastly?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Tyler Lastovich</a> on <a href="https://unsplash.com/s/photos/ocean?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Unsplash</a></span>

			    
			    <!-- <button type="button" class="btn btn-outline-secondary btn-block btn-lg" id="exAll">Expand All</button> -->
    <!-- <button type="button" class="btn btn-outline-secondary btn-block btn-lg" id="colAll">Collapse All</button> -->
    @auth
    @if ($upload)
    <br>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
	    <div class="row">
		<div class="col-md-4">
		    <button type="button" class="btn btn-outline-secondary btn-block btn-lg" id="upload">Upload</button>
		    <br>
		</div>
		<div class="col-md-4">
    		    <button type="button" class="btn btn-outline-secondary btn-block btn-lg" id="download">Download</button>
		    <br>
		</div>
		<div class="col-md-4">
		    <button type="button" class="btn btn-outline-secondary btn-block btn-lg" id="delete">Delete</button>
		    <br>
		</div>
	    </div>
	</div>
    </div>
    @endif
    @endauth

    <!-- Modal -->
    <div class="modal fade" id="continueModal" tabindex="-1" role="dialog" aria-labelledby="continueModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h2 class="modal-title" id="continueModalLabel">Info!</h2>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    <h3>
			Please select a dataset from the list by clicking the corresponding checkbox.
		    </h3>
		</div>
		<div class="modal-footer">
		    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
	    </div>
	</div>
    </div>


    <div class="modal fade" id="cookies_help_modal" tabindex="-1" role="dialog" aria-labelledby="cookies_helpModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="cookies_helpModalLabel">Cookies !</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    We use cookies to ensure that we give you the best
		    experience on our website. If you continue to use
		    this site we will assume that you are happy with
		    it.<br><br>
		    Please consider our <a id="privacy_from_cookie_modal" href="#"><b>Privacy Policy</b></a>.
		</div>
		<div class="modal-footer">
		    <button class="btn btn-outline-secondary" data-dismiss="modal" style="" id="cookies_help_not_show_again" >Do not show again.</button>
		    <button class="btn btn-outline-secondary" data-dismiss="modal" style="" id="cookies_help_close_button" >Close</button>
		</div>
	    </div>
	</div>
    </div>





    
    <!-- Modal -->
    <div class="modal fade" id="wsodv_allowModal" tabindex="-1" role="dialog" aria-labelledby="wsodv_allowModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h2 class="modal-title" id="wsodv_allowModalLabel">Info!</h2>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    <h5>
			You have the maximum number of {{ $allowed_wsodv_instances}} webODV
			service instances in usage. Please close a running service to
			request a new one.
		    </h5>
		</div>
		<div class="modal-footer">
		    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
	    </div>
	</div>
    </div>

    
    @auth
    @if ($upload)
    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal2" tabindex="-1" role="dialog" aria-labelledby="continueModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h2 class="modal-title" id="continueModalLabel">Data upload!</h2>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    <h3>
			Please select a folder containing <b>.odv</b> files and corresponding <b>.Data</b> folders!
		    </h3>
		    <br>
		    <form id="upload_form2"   method="POST" action="{{ url('/webodv/data_upload') }}" enctype="multipart/form-data" style="">
			<div class="custom-file">
			    <input type="file" class="custom-file-input"  name="fileToUpload" id="fileToUpload2" webkitdirectory multiple>
			    @csrf
			    <label class="custom-file-label" for="fileToUpload2">Choose folder</label>
			</div>
		    </form>
		    <!-- <input type="file" id="filepicker" name="fileList" webkitdirectory multiple /> -->
		    <!-- <ul id="listing"></ul> -->
		</div>
		<div class="modal-footer">
		    <button type="button" id="upload_modal_button2" class="btn btn-outline-success" data-dismiss="modal" >Upload</button>
		    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
		</div>
	    </div>
	</div>
    </div>
    @endauth
    @endif



       
@endsection
