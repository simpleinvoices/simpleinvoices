{{-- /*
* View: manage (Blade)
* 	 CustomFields manage template
*
* Authors:
*	 Nicolas Ruflin
*
* Last edited:
* 	 2008-02-02
*
* License:
*	 GPL v2 or above
*/ --}}
<form method="post" action="index.php?module=customFields&amp;view=manage">

Plugins:
{php}printPlugins();{/php}
Categorie: 
{php}printCategories();{/php}



	Name: <input type="text" name="name" /><br />
	Description: <input type="text" name="description" /><br />
	<button type="submit" class="btn btn-primary" name="save"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
	</form>



{php}printCustomFieldsList();{/php}
