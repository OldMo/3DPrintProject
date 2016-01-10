<?php

	set_time_limit(0);
    include 'simple_html_dom.php';
	$company = 'lulzbot';
    $startUrl = "https://www.lulzbot.com/store/filament";
    
    header("Content-type: text/html; charset=utf-8");  
    $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><ProductInfo />'); 
	$startUrlXml = $xml->addAttribute('startUrl',$startUrl);
	$companyXml = $xml->addAttribute('company',$company);
		
    $html = file_get_html($startUrl);
    $content = $html->find('.view-content');
    $index = 1;
    
	$breakFlag = 1;
	
    ob_start();//打开输出控制缓冲
    ob_end_flush();//输出缓冲区内容并关闭缓冲
    ob_implicit_flush(1);//立即输出
    foreach($content[0]->find('.views-row') as $element){
        $hrefStr = $element->find('a');
         
        // 获取材料图片
        $picAnchor = $hrefStr[0]->find('img');
        $imageUrl = $picAnchor[0]->src;
         
        //获取材料名
        $materialType = $hrefStr[1]->plaintext;
         
        //获取材料地址
        $shortLink = $hrefStr[1]->href;
        $url  = 'https://www.lulzbot.com'.$shortLink;
         
        //获取材料厂商
        $producer = $hrefStr[2]->plaintext;
         
        //获取价格
        $price = $element->find('.field-items')[0]->plaintext;
         
        $productDetailInformation = getProductDetails($url);
         
        echo 'FilaMent List:  Index ----'.$index.'<br/>';
        echo 'Picture link : '.$imageUrl.'<br/>'.'Name : '.$materialType.'<br/>'.'Link : '.$startUrl.'<br/>'
          .'Manufacture : '.$producer.'<br/>'.'Price : '.$price;
          echo '<br/>---------------------------------------------------------------------<br/>';
         
		 
		$diameter = $productDetailInformation['diameter'];
		$weight = $productDetailInformation['weight'];
		$packForm = $productDetailInformation['packForm'];
		$weightInKg = $productDetailInformation['weightInKg'];
		$colorNames = $productDetailInformation['colorNames'];
		$colorImgUrls = $productDetailInformation['colorImgUrls'];
		 
		if(count($colorNames) == 0){
			$productXml=$xml->addchild("product");
			$idXml = $productXml->addAttribute("id",$index);
			$materialTypeXml = $productXml->addchild("materialType",$materialType);
			$brandXml = $productXml->addchild("brand",'brand');
			$producerXml = $productXml->addchild("producer",$producer);
			$priceXml = $productXml->addchild("price",$price);
			$diameterXml = $productXml->addchild("diameter",$diameter);
			$colorXml = $productXml->addchild("color",'null');
			$colorImgUrlXml = $productXml->addchild("colorImgUrl",'null');
			$weightXml = $productXml->addchild("weight",$weight);
			$packFormXml = $productXml->addchild("packForm",$packForm);
			$weightInKgXml = $productXml->addchild("weightInKg",$weightInKg);
			$imageUrlXml = $productXml->addchild("imageUrl",$imageUrl);
			$urlXml = $productXml->addchild("url",$url);
			$index++;
			  
		}else{
			for($i = 0; $i < count($colorNames); $i++){
		  
				$productXml=$xml->addchild("product");
				$idXml = $productXml->addAttribute("id",$index);
				$materialTypeXml = $productXml->addchild("materialType",$materialType);
				$brandXml = $productXml->addchild("brand",'brand');
				$producerXml = $productXml->addchild("producer",$producer);
				$priceXml = $productXml->addchild("price",$price);
				$diameterXml = $productXml->addchild("diameter",$diameter);
				$colorXml = $productXml->addchild("color",$colorNames[$i]);
				$colorImgUrlXml = $productXml->addchild("colorImgUrl",$colorImgUrls[$i]);
				$weightXml = $productXml->addchild("weight",$weight);
				$packFormXml = $productXml->addchild("packForm",$packForm);
				$weightInKgXml = $productXml->addchild("weightInKg",$weightInKg);
				$imageUrlXml = $productXml->addchild("imageUrl",$imageUrl);
				$urlXml = $productXml->addchild("url",$url);
				
				$index++;
			}
		}
		
		if($breakFlag == 4){
			break;
		}
		
		$breakFlag++;
		  
        sleep(1);
        ob_flush();//输出缓冲区中的内容
        flush();//刷新输出缓冲
    }
    
	$filename = date('YmdHi', time()).'-'.$company.'.xml';
    $xml->asXml($filename); 
         
    
    function getProductDetails($productUrl){
		$html = file_get_html($productUrl);
		$widthWeightContent = $html->find('.filament-width-weight');
		$widthWeightValue = $widthWeightContent[0]->plaintext;
		$arr = explode(' , ',$widthWeightValue);
		$weigths = explode(' ',$arr[1]);
			
		$diameter = preg_replace("/\s/","", $arr[0]);
			
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
				
				$colorNames[$i] = $colorName;
				$colorImgUrls[$i] = $colorImgUrl;
				$i++;
			}
		}
        return array('diameter'=>$diameter,'weight'=>$weigths[0],'packForm'=>$weightUnit,'weightInKg'=>$weightInKg,'colorNames'=>$colorNames,'colorImgUrls'=>$colorImgUrls);
     }
?>
