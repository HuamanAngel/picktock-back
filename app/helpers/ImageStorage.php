<?php
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;

if (! function_exists('getUrlImage')) {
    function getUrlImage($image_name,$width = 250,$height = 250)
    {
        Cloudder::upload($image_name, null, array(
            "folder" => "categories",  "overwrite" => FALSE,
            "resource_type" => "image", "responsive" => TRUE, "transformation" => array("quality" => "70", "width" => "250", "height" => "250", "crop" => "scale")
        ));        
        $width = 250;
        $height = 250;
        $image_url = Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height" => $height, "crop" => "scale", "quality" => 70, "secure" => "true"]);
        return $image_url;
    }
}


if (! function_exists('getUrlAudio')) {
    function getUrlAudio($audio_name)
    {
        $audio_url = Cloudder::uploadVideo($audio_name, null, array(
            "folder" => "categories",  "overwrite" => FALSE,
            "resource_type" => "audio", "responsive" => TRUE
        ));
        return $audio_url->getResult()['secure_url'];
    }
}


if (! function_exists('getArrayAudio')) {
    function getArrayAudio($audio_name)
    {
        $audio_url = Cloudder::uploadVideo($audio_name, null, array(
            "folder" => "categories",  "overwrite" => FALSE,
            "resource_type" => "audio", "responsive" => TRUE
        ));
        return $audio_url->getResult();
    }
}

if (! function_exists('deleteAudio')) {
    function deleteAudio($public_id)
    {
        Cloudder::delete($public_id, array("resource_type" => "video"));
        return true;
    }
}


