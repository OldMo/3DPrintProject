<?php
    include 'simple_html_dom.php';
	$url = "https://www.lulzbot.com/store/filament/alloy-910";
    $html = file_get_html($url);

    $widthWeightContent = $html->find('.filament-width-weight');
	echo '直径和重量 : '.$widthWeightContent[0]->plaintext;
	
	$colorName = array();
	$colorImgUrl = array();
	$colorContent = $html->find('.filament-colors');
	if(count($colorContent) != 0){
		$colorLi = $colorContent[0]->find('li');
		$i = 0;
		foreach($colorLi as $color){
			$colorText = $color->find('span')[0]->plaintext;
			$colorName[$i] = $colorText;
			$i++;
		}
		
		$colorImgContent = $html->find('.gallery-item');
		if(count($colorImgContent) != 0){
			for($j = 1; $j < count($colorImgContent); $j++){
				$colorImg = $colorImgContent[$j]->find('a')[0]->href;
				 echo '颜色图片地址: '.$colorImg.'<br/>';
				$colorImgUrl[$j-1] = $colorImg;
			}
		}
	}
	echo $colorName[0].'---'.count($colorName).'<br/>';
	
	echo $colorImgUrl[0].'---'.count($colorImgUrl).'<br/>';
?>
