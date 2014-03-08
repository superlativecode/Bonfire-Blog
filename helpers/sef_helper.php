<?php
if(!function_exists('generate_seo_link')){
    function generate_seo_link($string, $replace = '-'){
    	$string = strtolower($string);
    
    	//remove query string
    	if(preg_match("#^http(s)?://[a-z0-9-_.]+\.[a-z]{2,4}#i",$string)){
    		$parsed_url = parse_url($string);
    		$string = $parsed_url['host'].' '.$parsed_url['path'];
    	}
    
    	//replace / and . with white space
    	$string = preg_replace("/[\/\.]/", " ", $string);
    
    	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    
    	//remove multiple dashes or whitespaces
    	$string = preg_replace("/[\s-]+/", " ", $string);
    
    	//convert whitespaces and underscore to $replace
    	$string = preg_replace("/[\s_]/", $replace, $string);
    
    	//limit the slug size
    	$string = substr($string, 0, 100);
    
    	//seo link is generated
    	return $string;
    }    
}

?>