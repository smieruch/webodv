@extends('layouts.webodv_layout')

@section('content')
<div class="container parallax" style="background-image: url({{ url('images/ocean_bg_1s.jpg') }});">
    <div class="row justify-content-center">
        <div class="col-md-8">
	    <div class="text-center text-break">
		<h2>{{ $dataset_heading }}</h2>
	    </div>
	    <div class="text-left">
		{!! $odv_data['text'] !!}
	    </div>
	    <br>
	    <br>
	    <div class="card">
                <div class="card-header" style="background-color:#647878; color:white;">Choose one of the following services:
		</div>
                <div class="card-body text-justify pb-0">
		    @foreach ($odv_data['services_texts'] as $key => $service_text)

			<div class="jumbotron p-3" style="">
			    {!! $service_text !!}
			    <div class="mt-1">
				<a href="{{ route("wsodv_init", ['datasetname' => $odv_data['datasetname'], 'servicename' => preg_replace('/\s+/','',$key)]) }}" >
				    <button type="button" class="btn btn-block btn-emodnet start_service_button" style="font-size:18px;">{!! $key !!}
				    </button>
				</a>
			    </div>
			</div>
		    @endforeach
		</div>
	    </div>
        </div>
    </div>
    <br>
    <span>Photo by <a href="https://unsplash.com/@lastly?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Tyler Lastovich</a> on <a href="https://unsplash.com/s/photos/ocean?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Unsplash</a></span>    
		      </div>
		      <br>
		      <br>
		      <br>
		      <br>
		      <br>
@endsection
