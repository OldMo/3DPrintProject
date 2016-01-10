<?php
    include 'simple_html_dom.php';
	$url = "https://www.lulzbot.com/store/filament/abs-5lb-reel";
    $html = file_get_html($url);

    $widthWeightContent = $html->find('.filament-width-weight');
	$widthWeightValue = $widthWeightContent[0]->plaintext;
	$arr = explode(' , ',$widthWeightValue);
	
	$widths = preg_replace("/\s/","", $arr[0]);
	echo $widths.'<br/>';
	$weigths = explode(' ',$arr[1]);
	echo $weigths[0].'--'.$weigths[1].'<br/>';
	
	$weightValue = preg_replace("/[a-zA-Z]+/","", $weigths[0]);
	$weightUnit = preg_replace('/[0-9_.-]/', '', $weigths[0]);
	switch($weightUnit){
		case "g":
			$weight = $weightValue / 1000;
			echo $weight.'kg'.'<br/>'; 
			break;
		case "kg":
			echo $weightValue.'kg'.'<br/>'; 
			break;
		case "lb":
			$weight = $weightValue * 0.45;
			echo $weight.'kg'.'<br/>'; 
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
			
			$colorNames[$i] = $colorName;
			$colorImgUrls[$i] = $colorImgUrl;
			$i++;
			echo $colorName.'--'.$colorImgUrl.'<br/>';
		}
	}
	 echo $colorNames[0].'---'.count($colorNames).'<br/>';
	
	 echo $colorImgUrls[0].'---'.count($colorImgUrls).'<br/>';
?>
