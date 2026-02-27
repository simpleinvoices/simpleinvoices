{{-- /*
* Script: backup_database.tpl
* 	 Database backup template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['backup_database'] ?? 'Backup Database' }}</h3>
	</div>
	<div class="card-body">
		{{ $display_block }}
	</div>
</div>
