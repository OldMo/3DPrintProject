<?php

	set_time_limit(0);
    include 'simple_html_dom.php';
	
	header("Content-type: text/html; charset=utf-8");   
	$xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><ProductInfo />');  
  
	$url = "https://www.lulzbot.com/store/filament";
    $html = file_get_html($url);

    $content = $html->find('.view-content');
	$index = 0;
	
	ob_start();//打开输出控制缓冲
	ob_end_flush();//输出缓冲区内容并关闭缓冲
	ob_implicit_flush(1);//立即输出
    foreach($content[0]->find('.views-row') as $element){
		$hrefStr = $element->find('a');
		
		// 获取材料图片
		$picAnchor = $hrefStr[0]->find('img');
		$picAddress = $picAnchor[0]->src;
		
		//获取材料名
		$name = $hrefStr[1]->plaintext;
		
		//获取材料地址
		$shortLink = $hrefStr[1]->href;
		$link  = 'https://www.lulzbot.com'.$shortLink;
		
		//获取材料厂商
		$manufacture = $hrefStr[2]->plaintext;
		
		//获取价格
		$price = $element->find('.field-items')[0]->plaintext;
		
		
		$DetailArr = getProductDetails($link);
		
		$index++;
		echo 'FilaMent List:  Index ----'.$index.'<br/>';
		echo 'Picture link : '.$picAddress.'<br/>'.'Name : '.$name.'<br/>'.'Link : '.$link.'<br/>'
		.'Manufacture : '.$manufacture.'<br/>'.'Price : '.$price;
		echo '<br/>---------------------------------------------------------------------<br/>';
		
		
		$item=$xml->addchild("item");  
		$item->addAttribute("ID",$index); 
		$item->addchild("Name",$name);  
		$item->addchild("Link",$link); 
		$item->addchild("Diameter",$DetailArr['diameter']); 
		$item->addchild("Weight",$DetailArr['weight']); 
		$item->addchild("Manufacture",$manufacture);  
		$item->addchild("Price",$price);  
		$item->addchild("ImgLink",$picAddress);  
		 

		 sleep(1);
		 ob_flush();//输出缓冲区中的内容
		 flush();//刷新输出缓冲
	}
	
	echo $xml->asXml();  
	$xml->asXml("products.xml");  
		
	
	function getProductDetails($productUrl){
		$html = file_get_html($productUrl);
		$widthWeightContent = $html->find('.filament-width-weight');
		//echo '直径和重量 : '.$widthWeightContent[0]->plaintext;
		$arr = explode(',',$widthWeightContent[0]->plaintext);
		
		$colorName = '';
		$colorImgLink = '';
		$colorContent = $html->find('.filament-colors');
		// if(count($colorContent) != 0){
			// $colorLi = $colorContent[0]->find('li');
			// foreach($colorLi as $color){
				// $colorName = $color->find('span')[0];
				// $colorImgLink = 'https://www.lulzbot.com/sites/default/files/'.strtolower($colorName).'_0.JPG';
				// echo '颜色 : '.$colorName.'------ 颜色图片地址: '.$colorImgLink.'<br/>';
				
				
			// }
		// }
		
		return array('diameter'=>$arr[0],'weight'=>$arr[1],'ColorName'=>$colorName,'ColorPicLink'=>$colorImgLink);
	}
?>
