function selectItem(li) {
//      document.frmpost.js_total.value = "That's " + li.extra[0] + " you picked."
if (li.extra) {
        document.getElementById("js_total").innerHTML= " " + li.extra[0] + " "
//              alert("That's '" + li.extra[0] + "' you picked.")
}
}
function formatItem(row) {
return row[0] + "<br><i>" + row[1] + "</i>";
}
$(document).ready(function() {
$("#ac_me").autocomplete("auto_complete_search.php", { minChars:1, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1 });
});

