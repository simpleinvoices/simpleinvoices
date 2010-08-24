<h1>Connection error</h1>
<p>Could not open URL <?php echo $_url?> you've specified. </p>

<?php if ($_server_response) { ?>

<p>Server responded with:
<pre>
<?php echo $_server_response; ?>
</pre>
</p>

<?php } else { ?>

<p>No response from server</p>

<?php }; ?>