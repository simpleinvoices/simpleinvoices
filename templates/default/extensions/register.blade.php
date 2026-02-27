<h2>About to <i>{{ $action ?? '' }}</i>: {{ $name ?? '' }}</h2>


<form name="frmpost" action="index.php?module=extensions&view=save" method="post" onsubmit="return frmpost_Validator(this)">

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['extension'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['name'] ?? '' }}</label>
			<input type="text" name="name" readonly="readonly" value="{{ $name ?? '' }}" class="form-control" />
			<input type="text" size="3" name="id" value="{{ $id ?? '' }}" readonly="readonly" class="form-control d-inline-block w-auto" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['description'] ?? '' }}</label>
			<input type="text" name="description" value="{{ $description ?? '' }}" class="form-control" />
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['save'] ?? '' }}">
			<i class="ti ti-check"></i>{{ $LANG['save'] ?? '' }}
		</button>
		<a href="./index.php?module=extensions&view=manage" class="btn btn-outline-secondary">
			<i class="ti ti-x"></i>{{ $LANG['cancel'] ?? '' }}
		</a>
	</div>
</div>

@if(($action=="unregister" & $count > 0))
<div class="alert alert-warning mt-3">
	<strong>WARNING:</strong> All {{ $count ?? '' }} extension-specific settings will be deleted!
</div>
@endif

<input name="action" value="{{ $action ?? '' }}" type="hidden" />
</form>
