<?php

  
     header("Content-type: text/html; charset=utf-8");  
     $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><ProductInfo />'); 
 
 
         
	$item=$xml->addchild("item"); 
	$ID = $item->addAttribute("ID",'1');
	$Name = $item->addchild("Name",'2'); 
	$Link = $item->addchild("Link",'3');
	$Diameter = $item->addchild("Diameter",'4');
	$Weight = $item->addchild("Weight",'5');
	$Manufacture = $item->addchild("Manufacture",'6'); 
	$Price = $item->addchild("Price",'7'); 
	$ImgLink = $item->addchild("ImgLink",'8'); 
	$Color = $item->addchild("Color");
	$Black = $Color->addchild("Black",'b');
	$BlackAttribu = $Black->addAttribute("url",'www');
	$Red = $Color->addchild("Red",'r');
	$Green = $Color->addchild("Green",'g');
         
    
     echo $xml->asXml(); 
     $xml->asXml("xml.xml"); 
	 

         
?>
