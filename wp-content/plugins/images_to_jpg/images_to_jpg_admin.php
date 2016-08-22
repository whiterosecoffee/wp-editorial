<?php

echo "<h3>Image to JPG</h3>";
//Image size can be large enough and take extra time.
set_time_limit('0'); 
$main_dirname = "../wp-content/uploads/2014/";

$index = 0;
$jpg_array = array();

for($i=1; $i<=12; $i++){
    
    $numbering = str_pad($i, 2, 0, STR_PAD_LEFT);
    $dirname = $main_dirname . $numbering . "/";
    
    $images = glob($dirname."*.png");
    
    if( !empty($images) ){
        foreach ($images as $image_path) {
            
            //Removing the current image extension.
            $new_image_string = substr($image_path, 0, strlen($image_path)-3);
            //Adding jpg for the out put file.
            $new_image_string = $new_image_string . "jpg";
            
            $image_path = get_option('siteurl') . substr($image_path, 2, strlen($image_path));
            
            //Setting an Array
            $jpg_array[] = array("image_path"=>$image_path, "new_image_string"=>$new_image_string);
            $index++;
        }
    }
}

if( !empty($jpg_array) ){
    
    //Getting the starting point for the loop.
    $start_loop = get_option('imagetojpg_loop_start');
    if($start_loop <= 0){
        $start_loop = 0;
    }
    
    //Setting the end point, so that array index doesnt exceeds.
    $end_loop = $start_loop + 500;
    if($end_loop >= sizeof($jpg_array)){
        $end_loop = sizeof($jpg_array);
    }
    
    if($start_loop > 1 ){
        echo "<h4>$start_loop Images are already converted to JPG.</h4>";
    }
    if($start_loop == sizeof($jpg_array) ){
        echo "<h4>" . sizeof($jpg_array) . " Images are already converted to JPG.</h4>";
    }
    
    for($a=$start_loop; $a<$end_loop; $a++){
        echo '<br><br><img src="' . $jpg_array[$a]['image_path'] . '" height="20" > ' . $a . ": " . $jpg_array[$a]['image_path'];
        //Function converting png to jpg.
        func_convert_to_jpg($jpg_array[$a]['image_path'], $jpg_array[$a]['new_image_string'], "png");
    }

    //Update for the starting point of next loop.
    update_option('imagetojpg_loop_start', $end_loop);
}

//Function that converts image to jpg, on taking the new file path as input.
function func_convert_to_jpg($target, $newcopy, $ext) {

    if ( !checkRemoteFile($target) ) {
        echo '<br>Image doesn`t exist!';
        return;
    }
    
    list($w_orig, $h_orig) = getimagesize($target);
    $ext = strtolower($ext);
    $img = imagecreatefrompng($target);
    $tci = imagecreatetruecolor($w_orig, $h_orig);

    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 84);
    echo '<br>Converted JPG: ' . $newcopy;
}

//Checking the existence of an Image file via Curl.
function checkRemoteFile($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE){
        return true;
    }
    else{
        return false;
    }
}