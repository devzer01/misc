<?php
    function smarty_modifier_phone_format($phone, $country_code = NULL)
    {
    	$phone = preg_replace("/[^0-9]/", "", $phone);
     
	    if(strlen($phone) == 7)
	    	return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
	    elseif(strlen($phone) == 10)
	    	return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
	    elseif($country_code != NULL) 
	    	return sprintf("+%s %s", $country_code, $phone);
	    else
	    	return $phone;
    }