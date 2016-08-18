{* preload the headers (for faster browsing) *}

{include file=$path|cat:'../header.tpl'}

<div class="si_wrap">

<form action="" method="post" id="frmLogin" name="frmLogin">
	<input type="hidden" name="action" value="login" />	
	
	<div class="si_box">
		<h1>{$LANG.simple_invoices}</h1>
	
		<div class="si_box_auth_pad">
			<table>
				<tr>
					<th>{$LANG.email}</th>
					<td>
						<input name="user" size="25" type="text" title="user" value="" />
					</td>
				</tr>       
				<tr>
					<th>{$LANG.password}</th>
					<td>
						<input name="pass" size="25" type="password" title="password" value="" />
					</td>
				</tr>
				<tr>
					<th></th>
					<td class='td_error'>


{if $errorMessage }

		<div class="si_error_line">{$errorMessage|outhtml}</div>
	
{/if}

					</td>
				</tr>       
			</table>
		
			<div class="si_toolbar">
					<button type="submit" value="login">Login</button>
			</div>
		</div>
	</div>

</form>

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
