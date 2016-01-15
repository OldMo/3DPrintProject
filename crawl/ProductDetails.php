<?php
    // include 'simple_html_dom.php';
	// $url = "https://www.lulzbot.com/store/filament/abs-5lb-reel";
    
	// $value = new ProductDetails();
	// $value->getProductDetails($url);

class ProductDetails{
     function getProductDetails($productUrl){
		$html = file_get_html($productUrl);
		$widthWeightContent = $html->find('.filament-width-weight');
		$widthWeightValue = $widthWeightContent[0]->plaintext;
		$arr = explode(' , ',$widthWeightValue);
		$weigths = explode(' ',$arr[1]);
			
		$diameter = preg_replace("/\s/","", $arr[0]);

		//提取数字
		$weightValue = preg_replace("/[a-zA-Z]+/","", $weigths[0]);
		$weightUnit = preg_replace('/[0-9_.-]/', '', $weigths[0]);
		$weightInKg = 0;
		switch($weightUnit){
			case "g":
				$weightInKg = $weightValue / 1000;
				break;
			case "kg":
				$weightInKg = $weightValue;
				break;
			case "lb":
				$weightInKg = $weightValue * 0.45;
				break;
		}
		
		$colorNames = array();
		$colorImgUrls = array();
		$colorContent = $html->find('.filament-colors');
		if(count($colorContent) != 0){
			$colorLi = $colorContent[0]->find('li');
			$i = 0;
			foreach($colorLi as $color){
				$colorText = $color->find('img')[0];
				$colorName = $colorText->alt;
				$colorImgUrl = 'https://www.lulzbot.com'.$colorText->src;
				// echo $colorImgUrl;
				$colorNames[$i] = $colorName;
				$colorImgUrls[$i] = $colorImgUrl;
				$i++;
			}
		}
        return array('diameter'=>$diameter,'weight'=>$weigths[0],'packForm'=>$weightUnit,'weightInKg'=>$weightInKg,'colorNames'=>$colorNames,'colorImgUrls'=>$colorImgUrls);
     }
}
?>
