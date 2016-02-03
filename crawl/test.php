<?php



$price = '$95.00';

$priceInfo = splitPrice($price);

$priceUnit = $priceInfo['priceUnit'];
echo $priceUnit;
switch($priceUnit){
    case '$': echo 'USD';break;
    case '¥': echo 'RMB'; break;
    case '£': echo 'GBP'; break;
}


function splitPrice($priceStr){
    $priceUnit = substr($priceStr,0,1);
    $price = substr($priceStr,1,strlen($priceStr) - 1);
    return array('price'=>$price,'priceUnit'=>$priceUnit);
}

	
?>

