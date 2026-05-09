{{-- /*
* View: save (Blade)
*   Currency save template
*
* License:
*   GPL v3 or above
*/ --}}

@if(isset($saved) && $saved !== null)
@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'Currency saved' : 'Currency not saved',
	'message' => outhtml(($saved == true) ? 'Currency has been saved successfully.' : 'Failed to save currency. Please check all required fields.')
])
@if($saved == true && isset($redirect))
<meta http-equiv="refresh" content="2;URL={{ $redirect }}" />
@endif
@endif
