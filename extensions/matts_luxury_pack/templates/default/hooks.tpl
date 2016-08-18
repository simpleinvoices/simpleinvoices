{assign var=mypos value=$smarty.template|strrpos:'/'}
{assign var=myroot value=$smarty.template|substr:1:$mypos-1}

{capture name="hook_head_end"}
{if $defaults.use_modal}
<!-- hook_head_end_add_superbox : {$LANG.Modal} -->{literal}
	<link rel="stylesheet" href="{/literal}{$myroot|cat:"/../../include/jquery/jquery.superbox.css"}{literal}" type="text/css" media="all" />
	<script type="text/javascript" src="{/literal}{$myroot|cat:"/../../include/jquery/jquery.superbox-min.js"}{literal}"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$(function(){
			$.superbox.settings = {
				closeTxt: '<img src="{/literal}{$myroot|cat:"/../../images/common/back-arrow24.png"}{literal}" />',
				loadTxt: "{/literal}{if $LANG.loading}{$LANG.loading|htmlsafe}{else}Loading{/if}{literal}..."
			};
			$.superbox();
		});
		function closeModal()
		{
			$("P.close A").click();
		}
	});
	//-->
	</script>
	<script type="text/javascript"><!--
	function inIframe ()
	{
		try {
			return window.self !== window.top;
		} catch (e) {
			return true;
		}
	}
	if (inIframe())
	{
		document.write('<link rel="stylesheet" type="text/css" href="{/literal}{$myroot|cat:"/../../modules/iframe_customers/style.css"}{literal}">');
	}
	//-->
	</script>{/literal}
<!-- end hook_head_end_add_superbox -->
{/if}
{/capture}


{* capture name="hook_head_end"}
{if $defaults.use_modal}
<!-- hook_head_end_add_nyroModal : {$LANG.Modal} -->{literal}
	<link rel="stylesheet" href="{/literal}{$myroot|cat:"/../../include/jquery/nyroModal.css"}{literal}" type="text/css" media="all" />
	<script type="text/javascript" src="{/literal}{$myroot|cat:"/../../include/jquery/jquery.nyroModal.custom.min.js"}{literal}"></script>

	<script type="text/javascript"><!--
	$(document).ready(function(){
		$(function() {
			$('.modal').nyroModal();
		});
	});
	//-->
	</script>{/literal}
<!-- end hook_head_end_add_nyroModal -->
{/if}
{/capture *}


{* capture name="hook_head_link"}
{if $defaults.use_modal}
{literal}
	<link rel="stylesheet" href="{/literal}{$myroot|cat:"/../../modules/iframe_customers/style.css"}{literal}" type="text/css" media="all" />
{/literal}
{/if}
{/capture *}


{capture name="hook_body_end"}
{if $defaults.use_modal}
{literal}
	<script type="text/javascript"><!--
/*	if (inIframe())
	{*/
		var elem = document.getElementById("cancelAddCustomer");
		if (elem.addEventListener){
			elem.addEventListener("click", function() { elem.preventDefault(); $.superbox.close(); document.getElementById("sb-close").trigger('click'); }, false);
		} else if (elem.attachEvent){
			elem.attachEvent("onclick", function() { elem.preventDefault(); $.superbox.close(); document.getElementById("sb-close").trigger('click'); });
		}

		var elem = document.getElementById("cancelEditCustomer");
		if (elem.addEventListener){
			elem.addEventListener("click", function() { elem.preventDefault(); $.superbox.close(); closeModal();/*$('#sb-close').trigger('click');*/ }, false);
		} else if (elem.attachEvent){
			elem.attachEvent("onclick", function() { elem.preventDefault(); $.superbox.close(); closeModal();/*$('#sb-close').trigger('click');*/ });
		}

		var elem = document.getElementById("cancelAddProduct");
		if (elem.addEventListener){
			elem.addEventListener("click", function() { elem.preventDefault(); /*document.getElementById*/$('#sb-close').trigger('click'); }, false);
		} else if (elem.attachEvent){
			elem.attachEvent("onclick", function() { elem.preventDefault(); /*document.getElementById*/$('#sb-close').trigger('click'); });
		}
/*	}*/
	//-->
	</script>{/literal}
{/if}
{/capture}

{*capture name="hook_head_end"}
{ *if $defaults.use_freeze* }
<!-- hook_head_end_add_CongelarFilaColumna -->{literal}
	<script src="{/literal}{$myroot|cat:"/../../include/jquery/jquery.CongelarFilaColumna.js"}{literal}"></script>
	<script type="text/javascript"><!--
		$(document).ready(function(){
			$("#manageGrid").CongelarFilaColumna();
		});
	//-->
	</script>{/literal}
{ */if* }
{/capture*}
