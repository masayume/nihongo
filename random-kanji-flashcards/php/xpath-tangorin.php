<?php

$DEBUG		= 0;	

$kanjilist 	= Array();
$kanji2enc 	= Array(); $ki = 0;
$jlpt 		= 1;

// single kanji debug
// print_r(read_kanji_data('会')); exit(0);

if (!$jlpt || $jlpt == 4) {
	// jlpt4
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt4'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt4&offset=25'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt4&offset=75'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt4&offset=125'));

} else if ($jlpt == 5) {

	// jlpt5
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt5'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt4&offset=25'));

} else if ($jlpt == 3) {

	// jlpt3
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3&offset=25'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3&offset=75'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3&offset=125'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3&offset=175'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3&offset=225'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3&offset=275'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt3&offset=325'));

} else if ($jlpt == 2) {

	// jlpt2
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=25'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=75'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=125'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=175'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=225'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=275'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=325'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=425'));
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt2&offset=475'));

} else if ($jlpt == 1) {

	// jlpt1
	$kanjilist = array_merge($kanjilist, read_kanji_list('dict=kanji&s=jlpt1'));
	for ($i=25; $i<=1175; $i+=50) {
		$kanjilist = array_merge($kanjilist, read_kanji_list("dict=kanji&s=jlpt1&offset=$i"));
	}

}

if ($DEBUG) { print ""; print_r($kanjilist); }
foreach ($kanjilist as $_k) {
	if ($GLOBALS['DEBUG']) { print "\nhttp://tangorin.com/kanji/" . $_k . "\n"; }
	$kanji = read_kanji_data($_k);

	$kanji2enc['KanjiArray'][$ki]['name'] = $_k; 								fwrite(STDERR, "\n retrieving jlpt" . $jlpt . " . . . ". $ki . " -> " . $_k);
	$kanji['k-readings'] = preg_replace('~\/~', " ", $kanji['k-readings']);
	$kanji2enc['KanjiArray'][$ki]['kana'] = $kanji['k-readings'];
	$kanji2enc['KanjiArray'][$ki]['english'] = $kanji['k-meaning'];
	$kanji2enc['KanjiArray'][$ki]['words'] = $kanji['k-compounds'];

/*
	$kanji['k-compounds-array'][$kanjikey]['keys'] .= "$kideogram,";
      $kanji['k-compounds-array'][$kanjikey][$kideogram]['kideogram'] = $kideogram;
      $kanji['k-compounds-array'][$kanjikey][$kideogram]['kkana'] = $kkana;
      $kanji['k-compounds-array'][$kanjikey][$kideogram]['kpronounce'] = $kpronounce;
      $kanji['k-compounds-array'][$kanjikey][$kideogram]['kmeaning'] = $kmeaning;
*/

    $words3 = "";

    foreach (explode(',', $kanji['k-compounds-array']['mainkeys']) as $mkey) { 
    	$words3 .= "<strong>$mkey</strong>: ";
	    foreach (explode(',', $kanji['k-compounds-array'][$mkey]['wordkeys']) as $wkey) { 

	    	// print " [external: " . $mkey . "] - [internal: " . $wkey . "] \n";

// BEGIN CARD HTML COMPOSITION

			$kanji2enc['KanjiArray'][$ki]['words2'][$mkey][$wkey]['kideogram'] 	= $kanji['k-compounds-array'][$mkey][$wkey]['kideogram'];
			$kanji2enc['KanjiArray'][$ki]['words2'][$mkey][$wkey]['kkana'] 		= $kanji['k-compounds-array'][$mkey][$wkey]['kkana'];
			$kanji2enc['KanjiArray'][$ki]['words2'][$mkey][$wkey]['kpronounce'] = $kanji['k-compounds-array'][$mkey][$wkey]['kpronounce'];
			$kanji2enc['KanjiArray'][$ki]['words2'][$mkey][$wkey]['kmeaning'] 	= $kanji['k-compounds-array'][$mkey][$wkey]['kmeaning'];

			$wordbody = " (" . $kanji['k-compounds-array'][$mkey][$wkey]['kkana'] . " " . $kanji['k-compounds-array'][$mkey][$wkey]['kpronounce'] . "): " . $kanji['k-compounds-array'][$mkey][$wkey]['kmeaning'] . " ";
			$words3 .=  "<a title='" . $wordbody . "' href='http://tangorin.com/general/" . $kanji['k-compounds-array'][$mkey][$wkey]['kideogram'] .  "' target='_blank'>" . $kanji['k-compounds-array'][$mkey][$wkey]['kideogram'] . "</a> " . "&nbsp; &nbsp;";

// END CARD HTML COMPOSITION

		}
    	$words3 .= "<br>";

	}

	$kanji2enc['KanjiArray'][$ki]['words3'] = $words3;

	$ki++;
}

print json_encode($kanji2enc, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

exit(0);

// .  .  .  .  .  .  .  .  .  .  . . 


function read_kanji_list($qstring) {

	$url 	=  'http://tangorin.com/dict.php?' . $qstring;

	if ($GLOBALS['DEBUG']) { echo "\nparsing URL: " . $url; }

	//  Initiate curl
	$ch     = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 	// Disable SSL verification
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_URL, $url); 			// Set the url

	// Execute
	$kpage  = curl_exec($ch);
	curl_close($ch); 					// Closing

	libxml_use_internal_errors(true); 			/* Use internal libxml errors -- turn on in production, off for debugging */

	$doc	= new DomDocument; 				/* Create a new DomDocument object */
	$doc->loadHTML($kpage); 				/* Load the HTML */
	libxml_use_internal_errors(false); 			/* Use internal libxml errors -- turn on in production, off for debugging */

	$finder = new DomXPath($doc);

	$_kanji = Array();

	// XPaths: nome
	$class 	= 'k-dt';
	$nodes 	= $finder->query("//*[contains(@class, '$class')]");
	foreach ($nodes as $nod) {
	  	array_push($_kanji, $nod->firstChild->textContent);  
		// echo '\n ' . $kanji['nome'];
	}

	if ($GLOBALS['DEBUG']) { print " . . . got " . count($_kanji) . " kanji "; }
	// print ""; print_r($kanji);

	return $_kanji;

} // end function read_kanji_list

function read_kanji_data($whichkanji) {

	$kanji = '';
	if (!$whichkanji) {
		$kanji 	= '%E4%BC%9A';
	} else {
		$kanji	= $whichkanji;
	}

	$kanjisite 	= 'http://tangorin.com/kanji';
	$url 	=  $kanjisite . '/' . $kanji;

	//  Initiate curl
	$ch     = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 	// Disable SSL verification
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_URL, $url); 				// Set the url

	// Execute
	$kpage  = curl_exec($ch);
	curl_close($ch); 							// Closing

	libxml_use_internal_errors(true); 			/* Use internal libxml errors -- turn on in production, off for debugging */

	$doc	= new DomDocument; 					/* Create a new DomDocument object */
	$doc->loadHTML($kpage); 					/* Load the HTML */
	libxml_use_internal_errors(false); 			/* Use internal libxml errors -- turn on in production, off for debugging */

	$finder = new DomXPath($doc);

	$kanji = Array();
	$kanji['k-compounds-array'] = Array();
	$kanji['k-compounds-array']['mainkeys'] = "";

	// XPaths: nome
	$class 	= 'k-dt';
	$nodes 	= $finder->query("//*[contains(@class, '$class')]");
	foreach ($nodes as $nod) {
	  $kanji['nome']	= $nod->firstChild->textContent;  
	}

	// XPaths: k-readings
	$class 	= 'k-readings';
	$nodes 	= $finder->query("//*[contains(@class, '$class')]");
	foreach ($nodes as $nod) {
	  $kanji['k-readings']	= $nod->firstChild->textContent;  
	}

	// XPaths: k-meaning
	$class 	= 'k-meaning';
	$nodes 	= $finder->query("//*[contains(@class, '$class')]");
	foreach ($nodes as $nod) {
	  $kanji['k-meaning']	= $nod->firstChild->textContent;  
	}

	// XPaths: k-info
	$class 	= 'k-info';
	$nodes 	= $finder->query("//*[contains(@class, '$class')]");
	foreach ($nodes as $nod) {
	  $kanji['k-info']	= $nod->firstChild->textContent;  
	}

	// XPaths: k-compounds
	$class 	= 'k-compounds';
	$nodes 	= $finder->query("//*[contains(@class, '$class')]");
	foreach ($nodes as $nod) {
	  $kanji['k-compounds']	= $nod->firstChild->textContent;  
	}

	// XPaths: k-compounds-array
	// $nodes 	= $finder->query('//*[@id="dictEntries"]/div/dd/div[2]/table/tbody/tr[1]/td');
	$nodes 	= $finder->query('//*[@id="dictEntries"]/div/dd/div[2]/table/tbody/tr');
	$kanjikey = "";
	$kanjival = "";
	for ($i = 0; $i < $nodes->length; $i++) {
	    $cols = $nodes->item($i)->getElementsbyTagName("td");
	    for ($j = 0; $j < $cols->length; $j++) {
//	        echo "\n$i-$j: " . $cols->item($j)->nodeValue, "\t";
			if ($j == 0) {
				$kanjikey = $cols->item($j)->nodeValue; $kanjival = "";
	    		$kanji['k-compounds-array']['mainkeys'] .= "$kanjikey,";

			} else if ($j == 1) {
				$kanjival .= $cols->item($j)->nodeValue;


				$knode = $cols->item($j);
				$kideogram = ""; $kkana = ""; $kpronounce = ""; $kmeaning = "";

	    		for ($l = 0; $l < $knode->childNodes->length; $l+=6) {
	    			$kideogram = $knode->childNodes->item($l)->nodeValue;
//					echo "\n\n ideogram: " . $kideogram;
	    			$kkana = $knode->childNodes->item($l+2)->nodeValue;
//					echo "\n kana " . $kkana;
	    			$kpronounce = $knode->childNodes->item($l+3)->nodeValue;
//					echo "\n pronounce " . $kpronounce;
	    			$kmeaning = $knode->childNodes->item($l+4)->nodeValue;
//					echo "\n meaning " . $kmeaning;

	    			if(empty($kanji['k-compounds-array'][$kanjikey]['wordkeys'])) {
						$kanji['k-compounds-array'][$kanjikey]['wordkeys'] = "";
	    			}
		    		$kanji['k-compounds-array'][$kanjikey]['wordkeys'] .= "$kideogram,";
				    $kanji['k-compounds-array'][$kanjikey][$kideogram]['kideogram'] = $kideogram;
				    $kanji['k-compounds-array'][$kanjikey][$kideogram]['kkana'] = $kkana;
				    $kanji['k-compounds-array'][$kanjikey][$kideogram]['kpronounce'] = $kpronounce;
				    $kanji['k-compounds-array'][$kanjikey][$kideogram]['kmeaning'] = substr($kmeaning, 3); // cancella il primo carattere multibyte "]"

	    		}
	    		$kanji['k-compounds-array'][$kanjikey]['wordkeys'] = substr($kanji['k-compounds-array'][$kanjikey]['wordkeys'], 0, -1);

			}

	        // you can also use DOMElement::textContent
	        // echo $cols->item($j)->textContent, "\t";
	    }

	    $kanji['k-compounds-array'][$kanjikey]['all'] = $kanjival;
		
	    //echo "\n";
	} // nodes
	$kanji['k-compounds-array']['mainkeys'] = substr($kanji['k-compounds-array']['mainkeys'], 0, -1);

// print_r($kanji['k-compounds-array']); exit(0);

	// XPaths: k-links
	$class 	= 'k-links';
	$nodes 	= $finder->query("//*[contains(@class, '$class')]");
	foreach ($nodes as $nod) {
	  $kanji['k-links']	= $nod->firstChild->textContent;  
	}

	if ($GLOBALS['DEBUG']) { var_dump($kanji); print "\n"; }

	return $kanji;

}

exit(0);

?>
