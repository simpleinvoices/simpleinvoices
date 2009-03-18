{*
/*
* Script: manage.tpl
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
*/
*}
<form method="post" action="index.php?module=customFields&amp;view=manage">

Plugins:
{php}printPlugins();{/php}
Categorie: 
{php}printCategories();{/php}



	Name: <input type="text" name="name" /><br />
	Description: <input type="text" name="description" /><br />
	<input type="submit" name="save" />
	</form>



{php}printCustomFieldsList();{/php}
