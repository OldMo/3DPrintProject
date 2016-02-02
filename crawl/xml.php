<?php

  
     // header("Content-type: text/html; charset=utf-8");  
     // $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"); 
 
 
        // $startUrl = $xml->addAttribute('startUrl','startUrl');
        // $company = $xml->addAttribute('company','company');
		// $product=$xml->addchild("product");
        // $id = $product->addAttribute("id",'index');
        // $materialType = $product->addchild("materialType",'materialType');
        // $brand = $product->addchild("brand",'brand');
		// $producer = $product->addchild("producer",'producer');
		// $price = $product->addchild("price",'price');
        // $diameter = $product->addchild("diameter",'diameter');
        // $color = $product->addchild("color",'color');
        // $weight = $product->addchild("weight",'weight');
        // $packForm = $product->addchild("packForm",'packForm');
        // $weightInKg = $product->addchild("weightInKg",'weightInKg');
        // $imageUrl = $product->addchild("imageUrl",'imageUrl');
        // $url = $product->addchild("url",'url');
		
		  

    
     // echo $xml->asXml(); 
     // $xml->asXml("xml.xml"); 
	 
//echo date('YmdHi', time());

// $typeString = 'Laywoo-D3 (LayWood) ';
// if (strstr($typeString, '(') && strstr($typeString,')')) {

    // $brandText = preg_replace('/\((.*)\)/', '', $typeString);
    // preg_match_all("/(?:\()(.*)(?:\))/i",$typeString, $result);
    // $type = $result[1][0];
    // $brand = trim($brandText);

    // return array('type'=>$type,'brand'=>$brand);
    // echo $brand.':'.$type;

// } else {
    // $value = str_replace(' ','',$typeString);
    // echo $value;
    // return array('type'=>$value,'brand'=>$value);;
// }

$logName = 'logs/'.date('YmdHi', time()).'-crawl.log';
error_log("You messed up1! \r\n", 3, $logName);
error_log("You messed up2! \r\n", 3, $logName);
error_log("You messed up3! \r\n", 3, $logName);
error_log("You messed up4! \r\n", 3, $logName);

	
?>

