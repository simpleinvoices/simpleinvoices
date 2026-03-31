<div class="card">
	<div class="card-body">
		@if($saved === true)
			<div class="alert alert-success" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($display_block ?? '') !!}
			</div>
		@elseif($saved === false)
			<div class="alert alert-warning" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($display_block ?? '') !!}
			</div>
		@endif
	</div>
</div>
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
