<?php

// session and form related functions
function clean($data){
	$data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
	$data = strip_tags($data);
    return $data;
}
