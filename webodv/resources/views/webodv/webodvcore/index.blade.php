@extends('layouts.webodv_layout')

@section('content')
<div class="container parallax" style="background-image: url({{ url('images/ocean_bg_1s.jpg') }});">
    <div class="row justify-content-center">
        <div class="col-md-10">
	    <div class="text-center">
		<h2>{{ config('webodv.index_heading') }}</h2>
	    </div>
	    webODV provides online Ocean Data View (ODV, <a href="https://odv.awi.de" target="_blank">https://odv.awi.de</a>) services like
	    the extraction, analysis, exploration and visualization of
	    oceanographic and other environmental data.

	    webODV is developed by <a href="https://www.awi.de/nc/ueber-uns/organisation/mitarbeiter/sebastian-mieruch-schnuelle.html" target="_blank">Dr. Sebastian Mieruch-Schnülle</a> and <a href="https://www.awi.de/nc/ueber-uns/organisation/mitarbeiter/reiner-schlitzer.html" target="_blank">Prof. Dr. Reiner Schlitzer </a>at the Alfred Wegener Institute (<a href="http://awi.de" target="_blank">AWI</a>)
	    in Bremerhaven, Germany.
	    <br><br>
	    If you use webODV, please cite: <a href="https://github.com/webodv/webodv" target="_blank"><b>https://github.com/webodv/webodv</b></a>
	    <br><br>

	    {!! config('webodv.index_add_text') !!}
	    
	    <br><br>

	    @if (!empty($priv_workspaces))
	    <!-- ##################### pager ######################## -->
	    <!-- only show at md and above: d-none d-md-block -->
	    <div class="d-none d-md-block" id="pager_large">
		<ul class="pagination justify-content-center" style="margin:10px 0">
		    <li id="pager_large_0" class="page-item webodv_pager_left" ><a class="page-link" href="#"><i class="fa fa-arrow-left"></i>&nbsp;</a></li>
		    <li id="pager_large_1" class="page-item webodv_pager"><a class="page-link" href="#">1. Private</a></li>
			@foreach ($priv_workspaces as $spaces)			    
			    <li id="pager_large_{{ $spaces->id }}" class="page-item webodv_pager private_pager"><a class="page-link" href="#">{{ $spaces->id }}. {{ $spaces->name }}</a></li>
			@endforeach
		    <li id="pager_large_{{ $number_of_pages_id }}" class="page-item webodv_pager_right"><a class="page-link" href="#">&nbsp;<i class="fa fa-arrow-right"></i></a></li>
		</ul>
	    </div>
	    <!-- only show below md: d-md-none -->
	    <div class="d-md-none" id="pager_small" style="">
		<ul class="pagination justify-content-center" style="margin:10px 0">
		    <li id="pager_small_0" class="page-item webodv_pager_left"><a class="page-link" href="#"><i class="fa fa-arrow-left"></i>&nbsp;</a></li>
		    <li id="pager_small_1" class="page-item webodv_pager" data-toggle="tooltip" data-placement="auto" title="Private"><a class="page-link" href="#">1.</a></li>
			@foreach ($priv_workspaces as $spaces)			    
			    <li id="pager_small_{{ $spaces->id }}" class="page-item webodv_pager private_pager" data-toggle="tooltip" data-placement="auto" title="{{ $spaces->name }}"><a class="page-link" href="#">{{ $spaces->id }}.</a></li>
			@endforeach
		    <li id="pager_small_5" class="page-item webodv_pager_right"><a class="page-link" href="#">&nbsp;<i class="fa fa-arrow-right"></i></a></li>
		</ul> 	    
	    </div>
	    <!-- ##################### pager ######################## -->
	    <br>
	    @endif

	    <div class="card" id="treeview_card" style="min-height:300px; opacity: 1;">

    @auth
    @if ($upload)
	<br>
	<div class="row justify-content-center">
        <div class="col-md-8">
	    <div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
		    <button type="button" class="btn btn-emodnet btn-block" id="upload">Upload</button>
		    <br>
		</div>
		<div class="col-md-4">
		</div>
		<!-- <div class="col-md-4">
    		     <button type="button" class="btn btn-emodnet btn-block" id="download">Download</button>
		     <br>
		     </div>
		     <div class="col-md-4">
		     <button type="button" class="btn btn-emodnet btn-block" id="delete">Delete</button>
		     <br>
		     </div> -->
	    </div>
	</div>
    </div>
    @endif
    @endauth

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
		    <div style="opacity: 1;" class="create_treeview_from_folder">
			<!-- {! $treeview !!} -->
		    </div>
		</div>
            </div>
	    <br>
	    <br>	    
	</div>
    </div>
    <span>Photo by <a href="https://unsplash.com/@lastly?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Tyler Lastovich</a> on <a href="https://unsplash.com/s/photos/ocean?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Unsplash</a></span>

		      </div>
		      <br>
		      <br>
		      <br>



	
    @auth
    @if ($upload)
    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal2" tabindex="-1" role="dialog" aria-labelledby="continueModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="continueModalLabel">Data upload!</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    Please select a <b><span style="color:red">.zip file</span></b> containing <b>.odv</b> files and corresponding <b>.Data</b> folders!
		    <br>
		    <br>
		    <form id="upload_form2"   method="POST" action="{{ url('/webodv/data_upload') }}" enctype="multipart/form-data" style="">
			<div class="custom-file">
			    <!-- <input type="file" class="custom-file-input"  name="fileToUpload" id="fileToUpload2" webkitdirectory multiple> -->
			    <input type="file" class="custom-file-input"  name="fileToUpload" id="fileToUpload2" accept=".zip">
			    @csrf
			    <label class="custom-file-label" for="fileToUpload2">.zip</label>
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




    <!-- Trash Modal -->
    <div class="modal fade" id="trashModal" tabindex="-1" role="dialog" aria-labelledby="trashModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="trashModalLabel">Delete collection!</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    Do you really want to delete <b><span id="delete_collection" style="color:red"></span></b> ?
		</div>
		<div class="modal-footer">
		    <button type="button" id="delete_collection_yes" class="btn btn-outline-success" data-dismiss="modal" >Yes</button>
		    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">No</button>
		</div>
	    </div>
	</div>
    </div>



    

    <!-- AWI Modal -->
    <div class="modal fade" id="awiModal" tabindex="-1" role="dialog" aria-labelledby="awiModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="awiModalLabel">Welcome ... </h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    ... to AWI's webODV app. webODV can be used here with private <b><span style="color:red">ODV collections</span></b>.
		    You have now access to your <b><span style="color:#264755">personal data</span></b> (Isilon
		    online data) via webODV. More infos how to access
		    your personal data are given <b><a href="https://spaces.awi.de/pages/viewpage.action?spaceKey=HELP&title=Recommended+storage+locations" target="_blank">here</a></b>.
		    If you continue, you can use webODV to upload, download, change and delete your ODV collections.
		    <br><br>
		    <b><span style="color:red">IMPORTANT:</span></b>
		    <ul>
			<li> The AWI-connected webODV is still in a
			    prototype state, maintenance interruptions can
			    occur.
			</li>
			<li>
			    The system is at the moment not optimized
			    for big data, i.e. we recommend to use only
			    datasets smaller than 500 MB.
			</li>
			<li>
			    Be aware that your personal workspace is limited to 100 GB.
			</li>
			<li>
			    We are happy to receive your feedback via the above contact form.
			</li>
		    </ul>
		</div>
		<div class="modal-footer">
		    <button type="button" id="awiModalagree" class="btn btn-outline-success" data-dismiss="modal" >I Agree</button>
		    <button type="button" id="awiModalneveragain" class="btn btn-outline-secondary" data-dismiss="modal">Agree and not show again</button>
		</div>
	    </div>
	</div>
    </div>


    @endauth
    @endif





    

    <a id="download_collection" href='#' download style="display:none">
	<button id="start_download"></button>
    </a>

       
@endsection
