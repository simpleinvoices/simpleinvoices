{* preload the headers (for faster browsing) *}

{include file=$path|cat:'../header.tpl'}

<div class="container">
	<form action="" method="post" id="frmLogin" name="frmLogin" class="form-signin">
		<input type="hidden" name="action" value="login" />
		
        <h2 class="form-signin-heading">{$LANG.simple_invoices}</h2>
        <input name="user" size="25" type="text" title="user" value="" class="form-control" placeholder="{$LANG.email}" autofocus>
        <input name="pass" size="25" type="password" title="password" value="" class="form-control" placeholder="{$LANG.password}">
        <!--<label class="checkbox">
		<input type="checkbox" value="remember-me"> Remember me
        </label>-->
        <button class="btn btn-lg btn-primary btn-block" type="submit" value="login">Login</button>
	</form>
	{if $errorMessage }

		<div class="alert alert-danger">{$errorMessage|outhtml}</div>
	
	{/if}

</div>

<div id="si_footer">
	<div class="si_wrap">
	    <a href="http://www.simpleinvoices.org">{$LANG.simple_invoices_powered_by}</a>
	</div>
</div>

{literal}
<script language="JavaScript">
	$(document).ready(function(){
		$('.si_box').hide();
		$('.si_box').slideDown(500);
	});
	document.frmLogin.user.focus();
</script>
{/literal}

</body>
</html>
