#!/usr/bin/perl
#to execute the lang check run the below commanf and view the lang_check.html file in your browser
#perl lang_check.pl > lang_check.html

@langs= (
	"castellano_spanish.inc.php",
	"catala_catalan.inc.php",
	"cestina_czech.inc.php",
	"deutsch_german.inc.php",
	"english_UK.inc.php",
	"francais_french.inc.php",
	"galego_galician.inc.php",
	"nederlands_dutch.inc.php",
	"portugues_portuguese.inc.php",
	"romana_romanian.inc.php",
	"suomi_finnish.inc.php",
);
print "
<html>
<style>
td {
        border-style: none none none none;
        border-color: white white white white;
        background-color: #F5F5F5;
        -moz-border-radius: 0px 0px 0px 0px;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
}
th {
        border-style: none none none none;
        border-color: white white white white;
        background-color: #F8F8F8;
        -moz-border-radius: 0px 0px 0px 0px;
        font-style: normal;
        font-weight: strong;
        text-decoration: none;
}


</style>
";
print "<table> \n";
print "<tr><th>Language file</th><th>Total variables</th><th>Variables translated</th><th>Percentage</th></th> \n";

foreach $langs (@langs) {

open MYFILE, $langs or die "Can't open $langs: $! \n";
$count=1;
$count_translated=1;
while (<MYFILE>) {
if (/=/) { $count++; }
if (/\/\/1/) { $count_translated++; }
}
$percentage = ($count_translated / $count) * 100;
print "<tr><td>$langs</td>";
print "<td>$count</td>";
print "<td>$count_translated</td>";
print "<td>$percentage%</td><tr>\n";


}


print "</table> \n";
