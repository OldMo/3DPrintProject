<?php

	set_time_limit(0);
    include 'simple_html_dom.php';
	include 'ProductDetails.php';
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
         
        //获取材料类型和品牌
        $materialTypeText = $hrefStr[1]->plaintext;
		$typeAndBrand = getBrandAndType($materialTypeText);
		$materialType = $typeAndBrand['type'];
		$brand = $typeAndBrand['brand'];

        //获取材料地址
        $shortLink = $hrefStr[1]->href;
        $url  = 'https://www.lulzbot.com'.$shortLink;
         
        //获取材料厂商
        $producer = $hrefStr[2]->plaintext;
         
        //获取价格
        $priceInfo = $element->find('.field-items')[0]->plaintext;
		$priceValue = splitPrice($priceInfo);
		$price = trim($priceValue['price']);
		$priceFlag = $priceValue['priceUnit'];

		$priceUnit;
		switch($priceFlag){
			case '$': $priceUnit = 'USD';break;
			case '¥': $priceUnit = 'RMB'; break;
			case '£': $priceUnit = 'GBP'; break;
			default: $priceUnit = 'USD';break;
		}


		//调用产品信息类
		$productDetail = new ProductDetails();
		$productHtml = $productDetail->getHtml($url);

//        $productDetailInformation = $productDetail->getProductDetails($url);
         
        echo 'FilaMent List:  Index ----'.$index.'<br/>';
        echo 'Picture link : '.$imageUrl.'<br/>'.'Name : '.$materialType.'<br/>'.'Link : '.$startUrl.'<br/>'
          .'Manufacture : '.$producer.'<br/>'.'Price : '.$price;
          echo '<br/>---------------------------------------------------------------------<br/>';
         
		 $diameterIndex = $productDetail->setDiameterIndex($url);
		$diameterInfo = $productDetail->getDiameters($productHtml,$diameterIndex);
		$weightInfo = $productDetail->getWeight($productHtml);
		$colorInfo = $productDetail->getColor($productHtml);
		$description = $productDetail->getFeatures($productHtml);

		$diameter = $diameterInfo['diameter'];
		$diameterUnit = $diameterInfo['diameterUnit'];
		$weight = $weightInfo['weight'];
		$weightUnit = $weightInfo['weightUnit'];
		$packForm = $weightInfo['packForm'];
		$weightInKg = $weightInfo['weightInKg'];
		$colorNames = $colorInfo['colorNames'];
		$colorImgUrls = $colorInfo['colorImgUrls'];

		//没有颜色
		if(count($colorNames) == 0){
			$productXml=$xml->addchild("product");
			$idXml = $productXml->addAttribute("id",$index);
			$materialTypeXml = $productXml->addchild("materialType",$materialType);
			$matherialSubTypeXml = $productXml->addchild("matherialSubType","");
			$brandXml = $productXml->addchild("brand","");
			$producerXml = $productXml->addchild("producer",$producer);
			$ingredientXml = $productXml->addchild("ingredient","");
			$priceXml = $productXml->addchild("price",$price);
			$priceUnit = $productXml->addChild("priceUnit",$priceUnit);
			$diameterXml = $productXml->addchild("diameter",$diameter);
			$diameterUnit = $productXml->addchild("diameterUnit",$diameterUnit);
			$colorXml = $productXml->addchild("color",'');
			$colorImgUrlXml = $productXml->addchild("colorImgUrl",'');
			$weightXml = $productXml->addchild("weight",$weight);
			$weightUnit = $productXml->addchild("weightUnit",$weightUnit);
			$packFormXml = $productXml->addchild("packForm",$packForm);
			$weightInKgXml = $productXml->addchild("weightInKg",$weightInKg);
			$imageUrlXml = $productXml->addchild("imageUrl",$imageUrl);
			$urlXml = $productXml->addchild("url",$url);
			$descriptionXml = $productXml->addchild("description",$description);
			$sellerXml = $productXml->addchild("seller","Lulzbot");
			$sellerWebXml = $productXml->addchild("sellerWeb","Lulzbot.com");

			$index++;
			  
		}else{
			for($i = 0; $i < count($colorNames); $i++){
		  
				$productXml=$xml->addchild("product");
				$idXml = $productXml->addAttribute("id",$index);
				$materialTypeXml = $productXml->addchild("materialType",$materialType);
				$matherialSubTypeXml = $productXml->addchild("matherialSubType","");
				$brandXml = $productXml->addchild("brand","");
				$producerXml = $productXml->addchild("producer",$producer);
				$ingredientXml = $productXml->addchild("ingredient","");
				$priceXml = $productXml->addchild("price",$price);
				$priceUnit = $productXml->addChild("priceUnit",$priceUnit);
				$diameterXml = $productXml->addchild("diameter",$diameter);
				$diameterUnit = $productXml->addchild("diameterUnit",$diameterUnit);
				$colorXml = $productXml->addchild("color",$colorNames[$i]);
				$colorImgUrlXml = $productXml->addchild("colorImgUrl",$colorImgUrls[$i]);
				$weightXml = $productXml->addchild("weight",$weight);
				$weightUnit = $productXml->addchild("weightUnit",$weightUnit);
				$packFormXml = $productXml->addchild("packForm",$packForm);
				$weightInKgXml = $productXml->addchild("weightInKg",$weightInKg);
				$imageUrlXml = $productXml->addchild("imageUrl",$imageUrl);
				$urlXml = $productXml->addchild("url",$url);
				$descriptionXml = $productXml->addchild("description",$description);
				$sellerXml = $productXml->addchild("seller","Lulzbot");
				$sellerWebXml = $productXml->addchild("sellerWeb","Lulzbot.com");

				$index++;
			}
		}
		
//		if($breakFlag == 4){
//			break;
//		}
		
		$breakFlag++;
		  
        sleep(1);
        ob_flush();//输出缓冲区中的内容
        flush();//刷新输出缓冲
    }
    
	$filename = 'xml/'.date('YmdHi', time()).'-'.$company.'.xml';
    $xml->asXml($filename);


	/**
	 * 从类型字符串中提取类型和品牌
	 * @param $typeString
	 * @return array
	 */
	function getBrandAndType($typeString){
		if (strstr($typeString, '(') && strstr($typeString,')')) {

			$brandText = preg_replace('/\((.*)\)/', '', $typeString);
			preg_match_all("/(?:\()(.*)(?:\))/i",$typeString, $result);
			$type = $result[1][0];
			$brand = trim($brandText);

			return array('type'=>$type,'brand'=>$brand);

		} else {
			$value = str_replace(' ','',$typeString);
			return array('type'=>$value,'brand'=>$value);;
		}
	}

	/**
	 * 将价格和价格单位分割
	 * @param $priceStr
	 * @return array
	 */
	function splitPrice($priceStr){
		$priceUnit = substr($priceStr,0,1);
		$price = substr($priceStr,1,strlen($priceStr) - 1);

		return array('price'=>$price,'priceUnit'=>$priceUnit);
	}

	/**
	 * 添加颜色到颜色库
	 * @param $colorName
	 */
	function colorLibraryOperate($colorName){
		if (file_exists('test.xml'))
		{
		  $xml = simplexml_load_file('test.xml');
		  var_dump($xml);
		}

		else
		{
			$xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Colors />'); 
			$filename = date('YmdHi', time()).'-'.'color.xml';
			$xml->asXml($filename);
		}
	}
	
	/**
	*判断颜色库中是否存在该颜色
	* @param $colorName  颜色名
	* @param $xml  加载的xml文件信息
	*/
	function isColorExist($colorName,$xml){
			$items = $xml->product;
		$flag = false;
		foreach($items as $it){
			$color = $it->color;
			
			if(strcasecmp($color,$colorName) == 0){
				echo $colorName.'    exsits';
				$flag = true;
				return $flag;
			}
		}
		var_dump($flag);
		return $flag;
	}
	
	/**
	*添加颜色到颜色库
	* @param $colorName 颜色名称
	* @param $xml 加载的xml信息
	* @param $fileName xml文件名
	*/
	function addColor($colorName,$xml,$filename){
		$xml->addchild('color',$colorName);
		$xml->asXml($filename);
	}
?>
