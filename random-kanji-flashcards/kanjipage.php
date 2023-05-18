<?php

$version = '1.0';

$jfile = $_GET['jfile'];
if (!$jfile) {
  $jfile = "5";
}
$jsonfile = 'jlpt' . $jfile . ".js";


$page =<<< "EOP"
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>masayume kanji N$jfile virtual flashcard page - ver. $version</title>
  <link rel="stylesheet" href="utilities/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="utilities/jquery.bigSlide.min.js"></script>
  <style>
.wrapper {
  background-image: url("img/j$jfile.jpg");
  display: flex;
  justify-content: center;
  align-items: center;
  background-repeat: no-repeat;
  background-size: cover;
  opacity: 0.9;
  width: 100%;
  height: 100%;
}
  </style>
<!-- to delete -->

</head>

<body >
  <div class="wrapper">

    <div id="site-id" class="overlay-box">
      <a href="https://www.masayume.it">masayume.it</a>
    </div>


    <div id="positionController" class="hide">
      <div id="controllerToggle" class="overlay-box no-select">
        <a href="#menu" class="menu-link">&#9776;</a>
      </div>
    </div>

    <div class="KanjiCard" id="card">
      <p class="KanjiCard_kana" id="kana"></p>
      <p class="KanjiCard_translation" id="translation"></p>
      <button id="card"><p class="KanjiCard_kanji" id="kanji"></p></button>
      <p class="KanjiCard_words" id="words"></p>
    </div>

</div>

    <div id="menu" class="panel" role="navigation">
        <br />
        <ul>
            <li>"random JLPT N$jfile kanji" - v. $version</li>
            <li>&nbsp;</li>
            <li><a href="kanjipage.php"><span class="icon-github nav-icon"></span>JLPT N5 kanji</a></li>
            <li><a href="kanjipage.php?jfile=4"><span class="icon-github nav-icon"></span>JLPT N4 kanji</a></li>
            <li><a href="kanjipage.php?jfile=3"><span class="icon-github nav-icon"></span>JLPT N3 kanji</a></li>
            <li><a href="kanjipage.php?jfile=2"><span class="icon-github nav-icon"></span>JLPT N2 kanji</a></li>
            <li><a href="kanjipage.php?jfile=1"><span class="icon-github nav-icon"></span>JLPT N1 kanji</a></li>
            <li>&nbsp;</li>
            <li><b>Credits:</b></li>
            <li><a href="http://codepen.io/reccanti/pen/BjwOev"><span class="icon-cog nav-icon"></span>Random Kanji Card by B. Wilcox</a></li>
            <li><a href="http://tangorin.com/"><span class="icon-cog nav-icon"></span>tangorin.com</a></li>
        </ul>
    </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js'></script>

  <script src="php/kanji.php?file=$jsonfile"></script>
  <script src="js/index.js"></script>


    <script src="utilities/jquery.min.js"></script>
    <script src="utilities/bigSlide.js"></script>
    <script>
        $(document).ready(function() {
            $('.menu-link').bigSlide();
        });
    </script>

</body>

<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-89665-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-89665-1');
</script>

</html>

EOP;

print $page;

exit(0);
