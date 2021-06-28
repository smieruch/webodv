@extends('layouts.webodv_maintenance')

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
		<br>
		<div class="card" id="treeview_card" style="min-height:300px; opacity: 1;">
		    <div class="card-header" style="background-color:#647878; color:white;">Choose one of the following datasets:
		    </div>
                    <div class="card-body" id="treeview_card_body" style="display:none;">
			    <div class="row">
				<div class="col-md-12">
				    <div class="dropdown">
					<div class="input-group">
					</div>
				    </div>
				</div>
			    </div>
			    <br>
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



			  

			  
@endsection
