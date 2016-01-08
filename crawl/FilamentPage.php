

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
          $ID = $item->addAttribute("ID",$index);
          $Name = $item->addchild("Name",$name); 
          $Link = $item->addchild("Link",$link);
          $Diameter = $item->addchild("Diameter",$DetailArr['diameter']);
          $Weight = $item->addchild("Weight",$DetailArr['weight']);
          $Manufacture = $item->addchild("Manufacture",$manufacture); 
          $Price = $item->addchild("Price",$price); 
          $ImgLink = $item->addchild("ImgLink",$picAddress); 
		  
		  $colorNames = $DetailArr['ColorName'];
          $ColorName = $item->addchild("ColorName"); 
		  if(count($colorNames) == 0){
			  $ColorName->addchild("name",'null');
		  }else{
			  foreach($colorNames as $name){
				  $ColorName->addchild("name",$name);
			  }
		  }
		  
		  $colorImgUrls = $DetailArr['ColorPicLink'];
          $ColorImgUrl = $item->addchild("ColorImgUrl"); 
		  if(count($colorImgUrls) == 0){
			  $ColorImgUrl->addchild("url",'null');
		  }else{
			   foreach($colorImgUrls as $url){
				  $ColorImgUrl->addchild("url",$url);
			   }
		  }
         

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
         
		 //获取颜色名称
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
			
			
			//获取颜色图片
			$colorImgContent = $html->find('.gallery-item');
			if(count($colorImgContent) != 0){
				for($j = 1; $j < count($colorImgContent); $j++){
					$colorImg = $colorImgContent[$j]->find('a')[0]->href;
					 // echo '颜色图片地址: '.$colorImg.'<br/>';
					$colorImgUrl[$j-1] = $colorImg;
				}
			}
		}
		
		
         
          return array('diameter'=>$arr[0],'weight'=>$arr[1],'ColorName'=>$colorName,'ColorPicLink'=>$colorImgUrl);
     }
?>S
