{{-- /*
* Script: database_sqlpatches.tpl
* 	 Database sqlpatches template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card" id="si_page_updates">
	<div class="card-body">
		<div class="alert alert-info mb-3">{{ $page['message'] }}</div>

		{{ $page['html'] }}

{{-- makes rows ######################## --}}
@if(isset($page['rows']) && count($page['rows']) > 0)
		<div class="mt-4">
			<ul class="list-group list-group-flush">
			@foreach(($page['rows'] ?? []) as $row)
				<li class="list-group-item li_{{ $row['result'] }}">{{ $row['text'] }}</li>
			@endforeach
			</ul>
		</div>
@endif
	</div>
</div>

{{-- Refresh ######################## --}}
@if(!empty($page['refresh']))
	<meta http-equiv="refresh" content="{{ $page['refresh'] }}0;url=index.php">
@endif


{{-- bye bye $display_block --}}
