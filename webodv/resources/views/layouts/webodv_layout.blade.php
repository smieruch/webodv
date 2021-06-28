<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (!empty(session('dataset')))
	<title>{{ session('dataset') }}</title>
    @else
	<title>{{ config('app.name', 'Laravel') }}</title>
    @endif


    <!-- Styles -->
    <link href="{{ asset('css/webodv/webodv_vendor'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">
    <link href="{{ asset('css/webodv/webodvcore'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">
    <link href="{{ asset('css/webodv/hummingbird-treeview'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">

    
    @if ( session('service') == 'DataExtraction' )
	<link href="{{ asset('css/webodv/webodvextractor'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">
    @endif

        <!-- more Styles-->
    <link href="{{ asset('css/webodv/webodvinterface'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">
    <link href="{{ asset('css/webodv/webodvinterface_font'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">
    <link href="{{ asset('css/webodv/webodvinterface_style'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">


    <link href="{{ asset('css/webodv/emodnet'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">
    <link href="{{ asset('css/webodv/emodnet_style'.config('webodv.mode').'.css').session('js_css_update') }}" rel="stylesheet">
    
    <!-- Matomo -->
    @if ( session('matomo') )
	<script type="text/javascript">
	 var _paq = window._paq = window._paq || [];
	 /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
	 @php
	 if (Auth::check()){
	     echo '_paq.push(["setUserId","' . trim(Auth::user()->name . '_' . Auth::user()->email)  . '"]);';
	 }
	 @endphp
	 _paq.push(['trackPageView']);
	 _paq.push(['enableLinkTracking']);
	 (function() {
	     var u="//matomo.awi.de/";
	     _paq.push(['setTrackerUrl', u+'matomo.php']);
	     _paq.push(['setSiteId', '72']);
	     var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	     g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
	 })();
	</script>
    @endif
    
    
</head>
<body>
    <!-- <div class="container-fluid" style="background-color:#c8f0f0">	
	 <div class="row">
	 <div class="col-xs-1">
	 &nbsp;
	 </div>
	 <div class="col-xs-1" style="border-right-style:solid;border-color:#647878;border-width:3px;padding-right:10px;padding-top:0px;margin:20px;margin-top:10px;">
	 <a href="http://www.awi.de" target="_blank" data-toggle="tooltip" data-placement="auto" title="Go to central portal">
	 <img class="img-fluid" src="{ url('/') . '/images/AWI_Logo_Farbe_RGB.png' }}" alt="Go to central portal" style="width:340px;">
	 </a>
	 </div>
	 <div class="col-xs-9" style="margin:20px;">
	 !! session('subtitle') !!}
	 </div>
	 <div class="col-xs-11 text-right ml-auto d-none d-md-block" style="margin-top:10px;margin-right:37px">
	 <a href="#" class="privacy_a">Privacy</a> &nbsp; <a href="#" class="impressum_a">Legal Notice</a>
	 </div>	    
	 <div class="col-xs-11 text-left d-md-none" style="margin:10px;">
	 <a href="#" class="privacy_a">Privacy</a> &nbsp; <a href="#" class="impressum_a">Legal Notice</a>
	 </div>	    
	 </div>
	 </div>
       -->

    <div class="container-fluid webodv_top_nav" style="background-color:#1d4b6b;display:block;color:white;">
	<div class="row" style="padding-top:4px;padding-bottom:4px">

	    <div class="col-md-9 text-right" style="">
		@if(null !== session('project') && null !== session('service'))
		    @if (session('project') == "GEOTRACES")
			@if (session('service') == 'DataExtraction' || session('service') == 'DataExploration')
			    <div class="row" style="padding-top:0px;padding-bottom:4px;">
				<div class="col-12 text-right" style="">
				    <a href="#" id="egeotraces" class="geotraces_link top_nav_link"><i class="fa fa-picture-o"></i> eGEOTRACES</a>
				    &emsp;
				    <a href="#" id="publications" class="geotraces_link top_nav_link"><i class="fa fa-book"></i> Publications</a>
				    &emsp;
				    <a href="#" id="known_issues" class="geotraces_link top_nav_link"><i class="fa fa-flag"></i> Known Issues</a>
				</div>
			    </div>
			@endif
		    @endif
		@endif
	    </div>
	    <div class="col-md-3 text-right" style="padding-right:36px">
		<a href="#" class="privacy_a top_nav_link">Privacy</a> <span class="top_nav_link">|</span> <a href="#" class="impressum_a top_nav_link">Legal Notice</a>
	    </div>	    
	</div>
    </div>
    

    <div id="app">
	<!-- <div class="container-fluid text-right" style="background-color: white;">
	     <div class="row">
	     <div class="col-12" style="padding-bottom:4px;">
	     <a class="privacy" href="">Legal Notice</a> &nbsp;&nbsp;&nbsp; <a class="privacy" href="">Privacy Policy</a>
	     </div>
	     </div>
	     </div> -->
        <nav class="navbar navbar-expand-md navbar-dark  shadow-sm " id="navbar">

	    
            <div class="container-fluid">

		<!-- <div style="top:0; position:absolute; right:0; font-size:12px; padding-right:4px;">
		     <a id="legal" href="">Legal Notice</a> &nbsp;&nbsp;&nbsp; <a id="privacy" href="">Privacy Policy</a>
		     </div>
		   -->

		<a class="navbar-brand" href="https://github.com/webodv/webodv" style="">
		    <!-- <div class="text-center" style="width:140px; border-radius:0px; background-image: url( { '"' . asset('images/webODV_logo_v3_2_trans.png') . '"' }} ); background-repeat:no-repeat; background-size:contain; background-position:center">&nbsp;</div> -->
		    <b>webODV</b>
                </a>
		<a class="navbar-brand" href="{{ url('/') }}" style="">
                    {{ config('webodv.brand') }}
                </a>
                <button class="navbar-toggler" style="" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon" ></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="background-color:#2f7bad;z-index:10;padding-left:20px;">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
			<li class="nav-item">
			    {!! session('dataset_link') !!}
			</li>			
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
			<!-- <li class="nav-item" style="" id="hide_top_bar_a">
                             <a class="nav-link" href="#" id="hide_top_bar" data-toggle="tooltip" data-placement="auto" title="Hide/show top bar"><i class="fa fa-eye-slash help_color" style="font-size:20px;"></i></a>
                             </li> -->
			    <li class="nav-item" style="" id="contact_li">
                                <a class="nav-link" href="#" id="contact_a" data-toggle="tooltip" data-placement="auto" title="Contact"><i class="fa fa-paper-plane-o help_color" style="font-size:20px;"></i></a>
                            </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif

                        @else			    
			    <!-- only allow if not vre -->
			    @if ( session('vre_brand') === null ||  session('vre_brand') == '' )
				<!-- <li class="nav-item" style="display:none;" id="extractor_reset_all">
                                     <a class="nav-link" href="#" id="extractor_eraser" data-toggle="tooltip" data-placement="auto" title="Reset all settings!"><i class="fa fa-eraser help_color" style="font-size:20px;"></i></a>
				     </li> -->
                            <li class="nav-item" style="display:none;" id="extractor_help">
                                <a class="nav-link" href="{{ asset('documentation/webodv-data-extractor-howto.pdf') }}" target="_blank" id="extractor_help" data-toggle="tooltip" data-placement="auto" title="Help"><i class="fa fa-question-circle-o help_color" style="font-size:20px;"></i></a>
                            </li>
				

			    <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
				    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

				    <a class="dropdown-item" href="" id="profile_a">
                                        Profile
                                    </a>


                                </div>
                            </li>
			    @else
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{ Auth::user()->name }}</a>
                            </li>
			    @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 pb-0 mb-0">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/webodv/webodv_vendor'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>

    @if ( session('service') == 'DataExtraction' )

    @endif
    
    <!-- <script src="{ asset('js/webodv/webodvcore/hummingbird_treeview_options'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script> -->

    <script src="{{ asset('js/webodv/webodvcore'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>
    <script src="{{ asset('js/webodv/monitor'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>

    <!-- if monitor load plotly because it is large 7 MB -->
    @if (strpos(url()->current(),"monitor") !== FALSE)
	<script src="{{ asset('js/webodv/plotly.js') }}" ></script>
    @endif

    
    <script src="{{ asset('js/webodv/webodvawiimport'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>

    <!-- <script src="{ asset('js/webodv/webodvcoreawi'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script> -->
    <script src="{{ asset('js/webodv/webodvcoreupload'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>

    
    
    @if ( session('service') == 'DataExploration' )
	<script src="{{ asset('js/webodv/odvonline'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>
    @endif
    @if ( session('service') == 'DataExtraction' )
	<script src="{{ asset('js/webodv/hummingbird-treeview'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>
	<script src="{{ asset('js/webodv/webodvcore/hummingbird_treeview_options_extractor'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>
	<script src="{{ asset('js/webodv/webodvextractor'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>
	<script src="{{ asset('js/webodv/webodvextractor_download'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script>
	<script src="{{ asset('js/webodv/webodvextractor_treeview'.config('webodv.mode').'.js').session('js_css_update') }}" defer ></script>
    @endif

    <!-- <script src="{ asset('js/webodv/webodvgeotraces'.config('webodv.mode').'.js').session('js_css_update') }}" defer></script> -->


    @if (session('service') != 'DataExploration')
	<div class="container-fluid text-center " id="copyright_div" style="">
	    <div class="row">
		<div class="col-sm-12 pb-2 pt-2" style="background-color:#1d4b6b;display:block;color:rgb(220,220,220);">
		    <div id="copyright">&copy; {{ config('webodv.copyrights') }}</div>
		</div>
	    </div>
	</div>
	
	<!-- <div class="footer-copyright"> &copy; webODV 2017
	     </div> -->
	
    @endif
    

    <div class="modal fade" id="egeotraces_modal" tabindex="-1" role="dialog" aria-labelledby="egeotracesModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="egeotracesModalLabel">Link to the eGEOTRACES Electronic Atlas</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    The <a href="http://egeotraces.org/" target="_blank" style="color: #2f7bad;"><b>eGEOTRACES</b></a> electronic
		    atlas contains section plots and animated
		    3D scenes for a large number of
		    hydrographic parameters as well as trace
		    elements and isotopes measured along
		    GEOTRACES cruise tracks.
		</div>
		<div class="modal-footer">
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center" data-dismiss="modal" style="">Close</button>
		</div>
	    </div>
	</div>
    </div>

    <div class="modal fade" id="known_issues_modal" tabindex="-1" role="dialog" aria-labelledby="known_issuesModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="known_issuesModalLabel">Link to the GEOTRACES IDP2017 V2 Known Issues</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    See the <a href="https://www.bodc.ac.uk/data/documents/nodb/544232/" target="_blank" style="color: #2f7bad;"><b>Known Issues</b></a> regarding the current GEOTRACES IDP2017 V2.
		</div>
		<div class="modal-footer">
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center" data-dismiss="modal" style="">Close</button>
		</div>
	    </div>
	</div>
    </div>

    <div class="modal fade" id="publications_modal" tabindex="-1" role="dialog" aria-labelledby="publicationsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="publicationsModalLabel">Links to the GEOTRACES Publication Database</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    Researchers can enter new publications that should be linked to their IDP data <a target="_blank" href="https://forms.gle/tiEhHKQwpjQmscrQ9" style="color: #2f7bad;"><b>here</b></a>.<br><br>Instructions for checking whether publications are correctly listed in the IDP are available at the bottom of the <a target="_blank" href="https://www.geotraces.org/geotraces-publications-database/" style="color: #2f7bad;"><b>GEOTRACES Publication Database</b></a> web page.
		</div>
		<div class="modal-footer">
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center" data-dismiss="modal" style="">Close</button>
		</div>
	    </div>
	</div>
    </div>

    <div class="modal fade" id="contact_modal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="contactModalLabel">Contact</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">

		    <form id="Contact_form" style="" method="" action="">
			<label for="contact_name">Name</label>
			<input id="contact_name"  class="form-control" name="contact_name" required placeholder="Name"
			       @if (Auth::guest())
			       value=""
			       @else
			       value="{{ Auth::user()->name }}" disabled
			       @endif
			><b><span id="contact_name_error" style="color:#a94442"></span></b>
			<br>
			<label for="contact_email">E-Mail Address</label>
			<input id="contact_email" type="email" class="form-control" name="contact_email"  required placeholder="E-Mail Address"
			       @if (Auth::guest())
			       value=""
			       @else
			       value="{{ Auth::user()->email }}" disabled
			       @endif
			><b><span id="contact_email_error" style="color:#a94442"></span></b>
			<br>
			<label for="contact_message">Message</label>
			<textarea id="contact_message" name="contact_message" rows="5" value="" class="form-control" required placeholder="Message"></textarea><b><span id="contact_message_error" style="color:#a94442"></span></b>
			<b><span id="contact_sentSUCCESS"></span></b>
		    </form>
		    
		</div>
		<div class="modal-footer">
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center"  style="" id="contact_button_send">Send</button>
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center" data-dismiss="modal" style="">Close</button>
		</div>
	    </div>
	</div>
    </div>

    @auth
    <div class="modal fade" id="profile_modal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="profileModalLabel">Profile</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    <form id="Profile_form" style="" method="" action="">
			<div id="Change_Profile">
			    <label for="name" id="name_label">Name</label>
			    <input id="name" type="text" class="form-control" name="name" required disabled placeholder="Name"
				   @if (Auth::guest())
				   value=""
				   @else
				   value="{{ Auth::user()->name }}"
				   @endif
			    ><strong><span id="last_name_error" style="color:#a94442"></span></strong>
			    <br>
			    <label for="profile_email" id="profile_email_label">E-Mail Address</label>
			    <input id="profile_email" type="email" class="form-control" name="profile_email"  required disabled autocomplete="email"
				   @if (Auth::guest())
				   value=""
				   @else
				   value="{{ Auth::user()->email }}"	       
				   @endif
			    ><strong><span id="profile_email_error" style="color:#a94442"></span></strong>
			    <br>
			    <label for="institution" id="Institution_label">Institution</label>
			    <input id="institution" type="text" class="form-control" name="Institution" required autofocus placeholder="Institution"
				   @if (Auth::guest())
				   value=""
				   @else
				   value="{{ Auth::user()->institution }}"
				   @endif
			    ><strong><span id="institution_error" style="color:#a94442"></span></strong>
			    <br>
			    <label for="street" id="Street_label">Street</label>
			    <input id="street" type="text" class="form-control" name="Street" required autofocus placeholder="Street"
				   @if (Auth::guest())
				   value=""
				   @else
				   value="{{ Auth::user()->street }}"
				   @endif
			    ><strong><span id="street_error" style="color:#a94442"></span></strong>
			    <br>
			    <label for="city" id="City_label">City</label>
			    <input id="city" type="text" class="form-control" name="City" required autofocus placeholder="City"
				   @if (Auth::guest())
				   value=""
				   @else
				   value="{{ Auth::user()->city }}"
				   @endif
			    ><strong><span id="city_error" style="color:#a94442"></span></strong>
			    <br>
			    <label for="zipcode" id="ZipCode_label">Zip Code</label>
			    <input id="zipcode" type="text" class="form-control" name="ZipCode" required autofocus placeholder="Zip Code"
				   @if (Auth::guest())
				   value=""
				   @else
				   value="{{ Auth::user()->zipcode }}"
				   @endif
			    ><strong><span id="zipcode_error" style="color:#a94442"></span></strong>
			    <br>
			    <label for="country" id="Country_label">Country</label>
			    @php
			    $countries = file(public_path("countries.txt"))
			    @endphp
			    <div class="selectpicker_wrapper cruises">
				<select class="selectpicker form-control " id="select_country_profile" data-style="btn btn-responsive btn-outline-secondary btn-block" data-live-search="true">
				    <option value="" selected=""></option>
				    @php
				    if (Auth::check()){
				    $current_country = Auth::user()->country;
				    } else {
				    $current_country = "nocountry";
				    }
				    @endphp
				    <!-- I have checked performance
					 no difference with or without the loop -->
				    @foreach ($countries as $country)
					@if (preg_match('/'.$current_country.'/',$country))
					    <option value="{{ $country }}" selected="selected">{{ $country }}</option>
					@else
					    <option value="{{ $country }}" >{{ $country }}</option>
					@endif
				    @endforeach
				</select>
			    </div>
			    
			    <input id="country" type="hidden" class="form-control" name="Country" required autofocus placeholder="Country"
			    <strong><span id="country_error" style="color:#a94442"></span></strong>
			    <strong><span id="sentSUCCESSprofile"></span></strong>
			</div>
		    </form>		    
		    
		</div>
		<div class="modal-footer">
		    <button class="btn btn-outline-danger text-center" style="" id="delete_account">Delete</button>
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center"  style="" id="profile_button_send">Change</button>
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center" data-dismiss="modal" id="profile_button_close" style="">Close</button>
		</div>
	    </div>
	</div>
    </div>
    

    <div class="modal fade" id="delete_account_modal" tabindex="-1" role="dialog" aria-labelledby="delete_accountModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		<div class="modal-header">
		    <h4 class="modal-title" id="delete_accountModalLabel">Delete account</h4>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		    </button>
		</div>
		<div class="modal-body">
		    Do you really want to delete your account?
		    <br><br>
		    <form id="delete_account_form" style="" method="" action="">
			<label for="delete_account_password"><b>Password</b></label>
			<input id="delete_account_password"  type="password" class="form-control" name="contact_name" required placeholder="Password">
			<b><span id="delete_account_password_error" style="color:#a94442"></span></b>
			<br>
			<b><span id="delete_account_sentSUCCESS"></span></b>
		    </form>

		</div>
		<div class="modal-footer">
		    <button class="btn btn-outline-danger text-center" id="delete_account_yes_button" style="">Yes</button>
		    <button class="btn btn-outline-emodnet emodnet-orange-border text-center" data-dismiss="modal" style="">Close</button>
		</div>
	    </div>
	</div>
    </div>
    @endauth

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
		    {!! config('webodv.cookie_text') !!}
                </div><div class="modal-footer">
                    <button class="btn btn-emodnet emodnet-orange-border cookie_modal" data-dismiss="modal" style="" id="cookies_help_not_show_again" >Do not show again.</button>
                    <button class="btn btn-emodnet-grey" data-dismiss="modal" style="" id="cookies_help_close_button" >Close</button>
                </div>
            </div>
        </div>
    </div>
		
    

    @php
    include public_path('impressum.html');
    include public_path('privacy.html');
    @endphp


    

</body>
</html>
