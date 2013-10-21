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
{if $use_captcha }
				<tr>
					<td colspan="2">
    <img id="siimage" style="border: 1px solid #000; margin-right: 15px" 
		 src="library/securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left">
    <object type="application/x-shockwave-flash" 
		data="library/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=library/securimage/images/audio_icon.png&amp;audio_file=library/securimage/securimage_play.php" 
		height="32" width="32">
    <param name="movie" 
		value="library/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=library/securimage/images/audio_icon.png&amp;audio_file=library/securimage/securimage_play.php" />
    </object>
    &nbsp;
    <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" 
		onclick="document.getElementById('siimage').src = 'library/securimage/securimage_show.php?sid=' + Math.random(); this.blur(); return false">
	<img src="library/securimage/images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a><br />
	<strong>CAPTCHA *</strong>
					</td>
				</tr>
				<tr>
					<td colspan="2">
    <input type="text" name="ct_captcha" size="12" maxlength="16" />
					</td>
				</tr>
{/if}
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
