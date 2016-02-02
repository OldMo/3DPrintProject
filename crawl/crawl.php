<?php
    include 'simple_html_dom.php';
	$selectFlag = 'www';
    $html = file_get_html('https://www.lulzbot.com/store/filament');

// Find all images
// foreach($html->find('img') as $element)
       // echo $element->src . '<br>';

    $content = $html->find('.view-content');
    //print( $content[0]);


    foreach($content[0]->find('a') as $element){
		$hrefStr = $element->href;
		//echo $hrefStr.'--';
		$isContain = stripos($hrefStr,$selectFlag);
		if($isContain){
			echo $element->plaintext.' : '.$hrefStr;
			echo 'true'.'<br>';
		}else{
			//echo 'false'.'<br>';
		}
	}

?>
