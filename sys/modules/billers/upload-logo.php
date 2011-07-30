<?php

$uploaddir="$app/logos/";

$uploadfile = $uploaddir . $_FILES["myfile"]["name"];

if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $uploadfile))
{
    echo "Success";
} else 	{
    echo "Error";
  	// WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
  	// Otherwise onSubmit event will not be fired
}
