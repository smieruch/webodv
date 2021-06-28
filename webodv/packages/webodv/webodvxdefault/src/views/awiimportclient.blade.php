@extends('layouts.webodv_layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">AWI Import</div>

                <div class="card-body">
                    <form method="GET" action="{{ route("awiimportclient") }}">
			@php
			$m=1;
			@endphp
			@foreach ($paras as $key => $value)
			    <div class="form-group row">
				<label for="{{ $key }}" class="col-md-4 col-form-label text-md-right">{{ $key }}</label>
				<div class="col-md-6">
                                    <input id="{{ "field_".$m }}" type="text" class="form-control" name="{{ $key }}" value="{{ $value }}" required autocomplete="{{ $key }}" autofocus>
				</div>
                            </div>
			    @php
			    $m++
			    @endphp
			@endforeach

			

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
