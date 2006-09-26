<h1>Error: missing font metrics file</h1>
<p>
Font metric file <tt><?php echo $_filename; ?></tt> for font <strong><?php echo $_typeface?></strong> missing. You must have AFM font metric files intalled on your server 
in order to use the <b>PS</b> output method. Metric files <i>may</i> be taken from Ghostscript distribution. (Note that you do NOT need
the Ghostscript itself).
</p>
<table>
<tr class="odd"> 
<th width="20%">Problem</th><th>Solution</th>
</tr>
<tr class="even">
<td>Metric files are not installed on your server</td>
<td>
Install either ghostscript-fonts or any other Type1 font package containing metric files. Edit 
.html2ps.config and config.inc.php to point to installed metric files.
</td>
</tr>
<tr class="odd">
<td>The <tt>TYPE1_FONTS_REPOSITORY</tt> points to incorrect directory.</td>
<td>Edit config.inc.php; set the value of <tt>TYPE1_FONTS_REPOSITORY</tt> to directory where your font metric files reside.</td>
</tr>
<tr class="even">
<td>You have no requested metric file.</td>
<td>
Probably you're using non-standard font package. HTML2PS is configured to work with fonts distributed with Ghostscript out-of-the-box.
If you're using other fonts, edit .html2ps.config. <tt>metrics</tt> items should contain names of metric files you're using.
</td>
</tr>
<tr class="odd">
<td>You've forgot to register the AFM metric file for this font.</td>
<td>
Register the metric file according to the <a href="help/howto_fonts.html">HOWTO: Install custom fonts</a>.
</td>
</tr>
<tr class="even">
<td>AFM file is not readable by the script.</td>
<td>
Check if AFM file permissions allow this file to be read by all user accouts (or, at least, the user accout PHP is running on).
</td>
</tr>
<tr class="even">
<td>PHP safe_mode <a href="http://php.net/manual/features.safe-mode.php#ini.open-basedir">open_base_dir</a> restriction prevents AFM file to be opened.</td>
<td>
Copy AFM files to any available directory under the 'open_base_dir'. An alternative approach is to disable 'safe_mode'.
</td>
</tr>
</table>
