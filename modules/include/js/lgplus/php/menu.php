<?php
$curscript=basename($_SERVER['SCRIPT_NAME']);
$scripts=array("ex1.php","ex2.php","ex3.php","ex4.php","ex5.php","ex6.php","ex7.php","ex8.php","ex9.php","../xml/simpleGrid1.xml","../xml/simpleGrid2.xml");
print "<strong style='color:brown;'>Rico LiveGrid Plus</strong>";
print "\n<table border='0' cellpadding='5'>\n<tr>";
print "<td><a href='../'>Release Home</a></td>";
foreach ($scripts as $k => $v) {
  $k++;
  if ($v==$curscript)
    print "<td><strong style='border:1px solid brown;color:brown;'>Ex $k</strong></td>";
  else
    print "<td><a href='$v'>Ex $k</a></td>";
}
print "</tr>\n</table>";
?>
