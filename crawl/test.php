<?php



$price = '$95.00';

splitPrice($price);

function splitPrice($priceStr){
    $price = substr($priceStr,0,1);
    $priceUnit = substr($priceStr,1,strlen($priceStr) - 1);
    return array('price'=>$price,'priceUnit'=>$priceUnit);
}

	
?>

