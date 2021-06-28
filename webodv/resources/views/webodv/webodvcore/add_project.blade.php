@extends('layouts.webodv_layout')

@section('content')
<div class="container parallax" style="background-image: url({{ url('images/ocean_bg_1s.jpg') }});">
    <div class="row justify-content-center">
        <div class="col-md-10">
	    <div class="text-center">
	    <h2>Projects</h2>
	    </div>	    
	    <div class="card" id="treeview_card" style="min-height:300px; opacity: 1;">
		<div class="card-header" style="background-color:#647878; color:white;">Add or remove projects.
		</div>
                <div class="card-body" id="treeview_card_body" style="display:none;">
		    <div class="row">
			<div class="col-md-12">
			    <div class="table-responsive table_wrapper" style="overflow-y:scroll; max-height:400px;">
				<table class="table table-sm table-striped table-condensed">
				    @if (isset($priv_workspaces))
					@foreach($priv_workspaces as $item)
					    <tr class="">
						<td>{{ $item->name }} &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-trash priv_workspace" style="cursor:pointer;"></i></td>
					    </tr>
					@endforeach
				    @endif
				</table>
			    </div>
			</div>
			<br><br><br><br>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			    <form method="POST" action="{{ route('add_project_post') }}">
				@csrf
				<div class="form-group row">			    
				    <input id="add_project_input" name="add_project_input" type="text" class="form-control" style="" placeholder="Project Name" required>
				</div>
				<div class="form-group row">			    
				    <button type="submit" class="btn btn-emodnet btn-block" id="add_project_button">Add</button>
				</div>
			    </form>			    
			</div>
			<div class="col-md-4">
			</div>			
		    </div>
		</div>
            </div>
	</div>
    </div>
    <span>Photo by <a href="https://unsplash.com/@lastly?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Tyler Lastovich</a> on <a href="https://unsplash.com/s/photos/ocean?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Unsplash</a></span>

		      </div>




       
@endsection
