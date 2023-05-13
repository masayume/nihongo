UI: From A Pen created at CodePen.io. You can find this one at http://codepen.io/reccanti/pen/BjwOev.
 Displays a random kanji from the JLPT N5 kanji set. The set came from http://tangorin.com/vocabulary/20005 and was parsed by this script https://github.com/reccanti/TangorinCSVParser

	RESOURCES:
http://tangorin.com/kanji/+jlpt4 							N4 kanji + info
http://tangorin.com/dict.php?dict=kanji&s=jlpt4 					(K1)
http://tangorin.com/dict.php?dict=kanji&s=jlpt4&offset=25
http://tangorin.com/dict.php?dict=kanji&s=jlpt4&offset=75			
http://tangorin.com/dict.php?dict=kanji&s=jlpt4&offset=125 			

	http://tangorin.com/general/%E4%BC%9A 						general informations
	http://tangorin.com/kanji/%E4%BC%9A 						kanji info

  TODO:
- usare kanji.php invece di kanji.htm per caricare i vari js dei jlpt
- verifiche presenza null nei file json/js ( 14 行 - 17 後 - 19 生 - 72 終 - 74 広 per N5)

  ARCHITECTURE:

0)  PARSE via curl and domxpath from tangorin.com
1)  php php/xpath-tangorin.php > json/jlpt4.json              GENERARE il FILE json dei dati dei KANJI di un SET 
2)  occorre copiare json/jlpt4.json in js/jlpt4.js modificare il js prodotto (js/jlpt4.js) in un array javascript (var kanjiList = [ ... ];) 
3)  kanji.htm legge dalla DIR js un array javascript php/kanji.php?file=jlpt4.js

  NEW FEATURES:

- numerino
- kanji radical info + link alle pagine interessanti
    http://tangorin.com/examples/%E4%BC%9A              esempi di frasi
    http://tangorin.com/dict.php?dict=kanji&s=jlpt1&offset=1175 esempi con kanji 
    link al kanji
- flash card behavior (prima solo il kanji, e poi tutte le info)


...
}, {
    "name": "一", 									xpath: 	//*[@id="dictEntries"]/div/dt/h2
    "kana": "イチ ・ イツ ・ ひと- ・ ひと.つ",
    "english": "one; one radical (no.1)",
    "words": "一番; "
}, {
    "name": "国",
    "kana": "コク ・ くに",
    "english": "country",
    "words": ""
}, {
...

2) kanji.htm should show more info (related kanji)

	PARAMS:
set 	. . . . . . . . . . . . . . . .  			N4 parses the N4 kanji list		





[d/regioni.php da aleantocorp.com]

$html = file_get_contents($ourwines, true);

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($html);

$class = $_GET["c"];

$finder = new DomXPath($doc);
$node = $finder->query("//*[contains(@class, '$class')]");

// print_r($node);

$wineshash = array(); 


foreach ($node as $wine) {
  
//  print_r($wine->firstChild->textContent); print "\n";  // Marche 
  $regione  = $wine->firstChild->textContent;

//  print_r($wine->parentNode->childNodes->item(2)->textContent); print "\n"; // Cantina
  $cantina  = $wine->parentNode->childNodes->item(2)->textContent;

//  print_r($wine->parentNode->childNodes->item(2)->getAttribute('href')); print "\n"; // Cantina URL
  $cantinaUrl = $wine->parentNode->childNodes->item(2)->getAttribute('href');

//  $wineshash[$regione][$cantina] = $cantinaUrl;

//  print_r($wine->parentNode->childNodes->item(0)->textContent); print "\n"; // wine name
  $vino   = $wine->parentNode->childNodes->item(0)->textContent;

//  print_r($wine->parentNode->childNodes->item(0)->getAttribute('href')); print "\n"; // Wine URL
  $vinoUrl  = $wine->parentNode->childNodes->item(0)->getAttribute('href');

  $wineshash[$regione][$cantina]["url"] = $cantinaUrl;
  $wineshash[$regione][$cantina]["vini"][$vino] = $vinoUrl;

}


