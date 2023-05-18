
var Card = (function Card(kanjilist) {
  var kanji;
  var $kanjifield;
  var $kanafield;
  var $translatefield;
  var $wordsfield;
  var $card;
  
  
  function init() {
    $kanjifield = $("#kanji");
    $kanafield = $("#kana");
    $translatefield = $("#translation");
    $wordsfield = $("#words");
    $card = $("#card");
    kanji = kanjilist;
    bindUI();
    newCard();
  }
  
  
  function bindUI() {
    // $card.on("click", handleClick);  
    $kanjifield.on("click", handleClick);  
  }
  
  
  function newCard() {
    var newKanji = _.sample(kanji);
    $kanjifield.html(newKanji.name);
    $kanafield.html(newKanji.kana);
    $translatefield.html(newKanji.english);
    $wordsfield.html(newKanji.words3);
  }
  
  
  function handleClick() {
    var tl = new TimelineMax();
    tl.to($card, .3, {
      rotationY: 90
    });
    tl.add(newCard);
    tl.to($card, .3, {
      rotationY: 0
    });
  }
  
  
  var api = {
    init: init,
  }
  return api;
})(KanjiArray);


window.addEventListener("load", Card.init);