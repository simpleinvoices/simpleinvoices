{literal}
<script type="text/javascript">

/**

jquery stuff for tab_menu extension

**/

$(document).ready(function(){

  	//TODO - grab the active page and put in here - so correct tab is open for that page
	$("#tabmenu > ul").tabs("select", '{/literal}{$active_tab} {literal}');
/*	
console.log('{/literal}{$active_tab}{literal}');
	$active = '{/literal}{$active_tab}{literal}';
	console.log($active);
	if($active == '#setting') { 
		//$("#tabmenu > ul").tabs({ selected: null }); 
//		$("#tabmenu > ul").tabs("select", '{/literal}{$active_tab} {literal}');
//		$("#tabmenu > ul").tabs({ selected: "#money" }); 
	} else {
		//$("#tabmenu > ul").tabs({ selected: null }); 
	//	$("#tabmenu > ul").tabs("select", null);
	}
*/
 });

 </script>
 {/literal}
 
