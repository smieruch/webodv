@extends('layouts.webodv_layout')

@section('content')
    <!-- <div class="container parallax" style="background-image: url({ url('images/ocean_bg_1s.jpg') }});"> -->
    <div class="container parallax" style="background-color:#89a2b0;">
	<div class="row justify-content-center">
            <div class="col-12">
		<div class="text-center" style="color:white;">
		    <h2>webODV monitor</h2>
		</div>

		
		<div class="row justify-content-center">
		    <div class="col-md-2">
		    </div>
		    <div class="col-md-4">
			<input type="text" name="date1" class="form-control btn btn-basic btn-responsive btn-block text-center" id="date1" style="background-color:white;">
		    </div>
		    <div class="col-md-4">
			<div class="selectpicker_wrapper">
			    <select class="selectpicker form-control" id="hour" data-style="btn btn-responsive btn-block btn-outline-secondary text-center ">
				<option value="00:00" selected="selected">00:00</option>;
				@php
				for ($i=1;$i<=23;$i++){
				if ($i<10){
			        echo '<option value="0' . $i . ':00">0' . $i  .':00</option>';
				}  else {
			        echo '<option value="' . $i . ':00">' . $i  .':00</option>';
				}
				}
				@endphp
			    </select>
			</div>
		    </div>
		    <div class="col-md-2">
		    </div>
		</div>


		<br><br><br>
		



		
		<div class="" id="cpu" style="width:100%;height:250px;"></div>
		<br><br>
		<div id="mem" style="width:100%;height:250px;"></div>
		<br><br>
		<div id="odvws_instances" style="width:100%;height:250px;"></div>
		<br><br>
		


	    </div>
	</div>

	
@endsection
