$(document).ready(init);
function init()
{
$.datePicker.setDateFormat('ymd','-');
$('input#date1').datePicker({startDate:'01/01/1970'});
}

