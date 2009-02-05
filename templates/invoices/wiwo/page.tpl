<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>{title}</title>
  <link href="{cssfile}" rel="stylesheet" type="text/css" />
 </head>

 <body>
 <div class="ms">
  <p>You are using a browser not conforming to the standards<br />
  (probably a microsoft browser).</p>
  <p>This tool will work, but not as nicely as intended.<br /> 
  Please use a good browser!</p>
 </div>
{DEBUG}
  <div class="identifier"><a href="/v0.9/">{USERDN}</a></div>
  <div id="status" class="{STYLE}">{CODE} {STATUS}</div>
  <form method="post" action="index.php">
  <input type="checkbox" name="debug" value="1" {DBG} />Debug
{CONTENT}
  </form>
 </body>
</html>
