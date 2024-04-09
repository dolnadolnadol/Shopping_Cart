<?php
    if(isset($row['Photo'])){
        $imageDataEncoded = base64_encode($row['Photo']);
        echo "<img src='data:image/jpg;base64,{$imageDataEncoded}' alt='imagy' style='width:100%; height:10rem;'/>";
    }
    else{
        echo "<img src='../img/no-image.png' alt='No Image' style='width:100%; height:10rem;' />";
    }
?>