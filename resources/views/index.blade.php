<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>Framgia Goal</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<base href="{{ asset('/') }}" />		
		<link rel="stylesheet" type="text/css" href="/css/app.css">
		<link href="{{ asset('bower_components/fgoal-assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('bower_components/fgoal-assets//vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('bower_components/fgoal-assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="{{ asset('img/fgoal-favicon.ico') }}" />
	</head>
    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
        <div id="app"></div>

		<script src="{{ asset('js/app.js') }}"></script>

		<!--begin::Web font -->
		<script src="{{ asset('bower_components/fgoal-assets/app/js/webfont.js') }}"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>
		<!--end::Web font -->
		
        <script src="{{ asset('bower_components/fgoal-assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('bower_components/fgoal-assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('bower_components/fgoal-assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('bower_components/fgoal-assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('bower_components/fgoal-assets/demo/default/custom/crud/datatables/advanced/multiple-controls.js') }}" type="text/javascript"></script>
	</body>
</html>
