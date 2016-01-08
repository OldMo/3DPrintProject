<?php
    include 'simple_html_dom.php';
	$url = "https://www.lulzbot.com/store/filament/abs";
    $html = file_get_html($url);

    $widthWeightContent = $html->find('.filament-width-weight');
	echo '直径和重量 : '.$widthWeightContent[0]->plaintext.'<br/>';
	
	$colorContent = $html->find('.filament-colors');
	if(count($colorContent) != 0){
		$colorLi = $colorContent[0]->find('li');
		foreach($colorLi as $color){
			// $colorImg = $color->find('img')[0]->src;
			$colorText = $color->find('span')[0];
			$colorImgLink = 'https://www.lulzbot.com/sites/default/files/'.strtolower($colorText).'_0.JPG';
			echo '颜色 : '.$colorText.'------ 颜色图片地址: '.$colorImgLink.'<br/>';
		}
	}
?>
