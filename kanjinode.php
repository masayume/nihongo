<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connected Kanji Tiles</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            height: 100vh;
        }

        #container {
            position: relative;
            width: 100vw;
            height: 100vh;
            cursor: move;
            transition: transform 0.5s ease;
        }

        .tile {
            position: absolute;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .tile:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: scale(1.1);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }

        .tile.active {
            background: rgba(255, 100, 100, 0.3);
            border-color: rgba(255, 100, 100, 0.6);
            transform: scale(1.15);
            box-shadow: 0 16px 48px rgba(255, 100, 100, 0.3);
        }

        .connection {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            height: 2px;
            transform-origin: left center;
            pointer-events: none;
            opacity: 0.6;
        }

        .connection.active {
            background: rgba(255, 150, 150, 0.6);
            height: 3px;
            opacity: 0.8;
        }

        #info {
            position: fixed;
            top: 20px;
            left: 20px;
            color: white;
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            z-index: 1000;
        }

        #controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            color: white;
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div id="info">
        <div>Selected: <span id="selected-kanji">水</span></div>
        <div>Meaning: <span id="kanji-meaning">Water</span></div>
        <div>Radical: <span id="kanji-radical">水 (water)</span></div>
    </div>

    <div id="controls">
        Click tiles to explore connections<br>
        Drag to pan around the network
    </div>

    <div id="container"></div>

    <script>
        const kanjiData = {
            '水': { meaning: 'Water', radical: '水', x: 400, y: 300, connections: ['氷', '河', '海', '泳'] },
            '氷': { meaning: 'Ice', radical: '水', x: 300, y: 200, connections: ['水', '冷'] },
            '河': { meaning: 'River', radical: '水', x: 500, y: 200, connections: ['水', '海', '川'] },
            '海': { meaning: 'Sea', radical: '水', x: 600, y: 300, connections: ['水', '河', '波'] },
            '泳': { meaning: 'Swim', radical: '水', x: 350, y: 400, connections: ['水', '游'] },
            '川': { meaning: 'River', radical: '川', x: 550, y: 100, connections: ['河', '流'] },
            '波': { meaning: 'Wave', radical: '水', x: 700, y: 250, connections: ['海'] },
            '流': { meaning: 'Flow', radical: '水', x: 650, y: 150, connections: ['川', '河'] },
            '冷': { meaning: 'Cold', radical: '冫', x: 200, y: 150, connections: ['氷'] },
            '游': { meaning: 'Swim', radical: '水', x: 250, y: 450, connections: ['泳'] },
            
            '火': { meaning: 'Fire', radical: '火', x: 800, y: 400, connections: ['炎', '燃', '熱'] },
            '炎': { meaning: 'Flame', radical: '火', x: 900, y: 350, connections: ['火', '燃'] },
            '燃': { meaning: 'Burn', radical: '火', x: 850, y: 500, connections: ['火', '炎'] },
            '熱': { meaning: 'Heat', radical: '火', x: 750, y: 500, connections: ['火'] },
            
            '木': { meaning: 'Tree', radical: '木', x: 100, y: 300, connections: ['森', '林', '材'] },
            '森': { meaning: 'Forest', radical: '木', x: 50, y: 200, connections: ['木', '林'] },
            '林': { meaning: 'Woods', radical: '木', x: 150, y: 200, connections: ['木', '森'] },
            '材': { meaning: 'Material', radical: '木', x: 100, y: 400, connections: ['木'] },
            
            '山': { meaning: 'Mountain', radical: '山', x: 400, y: 600, connections: ['峰', '岩'] },
            '峰': { meaning: 'Peak', radical: '山', x: 350, y: 700, connections: ['山'] },
            '岩': { meaning: 'Rock', radical: '山', x: 450, y: 700, connections: ['山'] }
        };

        let currentCenter = '水';
        let translateX = -400 + window.innerWidth / 2;
        let translateY = -300 + window.innerHeight / 2;
        let isDragging = false;
        let dragStart = { x: 0, y: 0 };

        const container = document.getElementById('container');
        const selectedKanji = document.getElementById('selected-kanji');
        const kanjiMeaning = document.getElementById('kanji-meaning');
        const kanjiRadical = document.getElementById('kanji-radical');

        function createTile(kanji, data) {
            const tile = document.createElement('div');
            tile.className = 'tile';
            tile.textContent = kanji;
            tile.style.left = data.x + 'px';
            tile.style.top = data.y + 'px';
            tile.dataset.kanji = kanji;
            
            tile.addEventListener('click', (e) => {
                e.stopPropagation();
                centerOnTile(kanji);
            });
            
            return tile;
        }

        function createConnection(from, to) {
            const fromData = kanjiData[from];
            const toData = kanjiData[to];
            
            const connection = document.createElement('div');
            connection.className = 'connection';
            
            const dx = toData.x - fromData.x;
            const dy = toData.y - fromData.y;
            const length = Math.sqrt(dx * dx + dy * dy);
            const angle = Math.atan2(dy, dx) * 180 / Math.PI;
            
            connection.style.left = (fromData.x + 40) + 'px';
            connection.style.top = (fromData.y + 40) + 'px';
            connection.style.width = length + 'px';
            connection.style.transform = `rotate(${angle}deg)`;
            connection.dataset.connection = `${from}-${to}`;
            
            return connection;
        }

        function updateDisplay() {
            container.innerHTML = '';
            
            // Create connections first (so they appear behind tiles)
            Object.keys(kanjiData).forEach(kanji => {
                const data = kanjiData[kanji];
                data.connections.forEach(connectedKanji => {
                    if (kanjiData[connectedKanji]) {
                        const connection = createConnection(kanji, connectedKanji);
                        if (kanji === currentCenter || connectedKanji === currentCenter) {
                            connection.classList.add('active');
                        }
                        container.appendChild(connection);
                    }
                });
            });
            
            // Create tiles
            Object.keys(kanjiData).forEach(kanji => {
                const tile = createTile(kanji, kanjiData[kanji]);
                if (kanji === currentCenter) {
                    tile.classList.add('active');
                }
                container.appendChild(tile);
            });
            
            // Update container transform
            container.style.transform = `translate(${translateX}px, ${translateY}px)`;
        }

        function centerOnTile(kanji) {
            if (!kanjiData[kanji]) return;
            
            currentCenter = kanji;
            const data = kanjiData[kanji];
            
            translateX = -data.x + window.innerWidth / 2 - 40;
            translateY = -data.y + window.innerHeight / 2 - 40;
            
            selectedKanji.textContent = kanji;
            kanjiMeaning.textContent = data.meaning;
            kanjiRadical.textContent = `${data.radical} (${getRadicalMeaning(data.radical)})`;
            
            updateDisplay();
        }

        function getRadicalMeaning(radical) {
            const radicalMeanings = {
                '水': 'water',
                '火': 'fire',
                '木': 'tree',
                '山': 'mountain',
                '川': 'river',
                '冫': 'ice'
            };
            return radicalMeanings[radical] || 'radical';
        }

        // Drag functionality
        container.addEventListener('mousedown', (e) => {
            if (e.target.classList.contains('tile')) return;
            isDragging = true;
            dragStart.x = e.clientX - translateX;
            dragStart.y = e.clientY - translateY;
            container.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            translateX = e.clientX - dragStart.x;
            translateY = e.clientY - dragStart.y;
            container.style.transform = `translate(${translateX}px, ${translateY}px)`;
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            container.style.cursor = 'move';
        });

        // Initialize
        centerOnTile('水');
    </script>
</body>
</html>


















https://huggingface.co/spaces/GamerIsaac/Pixel-Art-Converter-V.2




    "  // 416",
    "  // 417",
    "  // 418",
    "  // 419",
    "  // 420",
    "  // 421",
    "  // 422",
    "  // 423",
    "  // 424",
    "  // 425",
    "  // 426",
    "  // 427",
    "  // 428",
    "  // 429",
    "  // 430",

https://huggingface.co/spaces/NoCrypt/pixelization
https://github.com/AUTOMATIC1111/stable-diffusion-webui-pixelization
https://portaly.cc/patpixels


https://codepen.io/Petr-Knoll/pen/QwWLZdx   glass button
 
https://en.wikipedia.org/wiki/Atkinson_dithering  
Floyd Steinberg
http://ditherit.com/

https://micku7zu.github.io/vanilla-tilt.js/
https://aresluna.org/the-hardest-working-font-in-manhattan/

feature: 2bit button

楽 鼠 柄 ♥

 ./mysqlrouter --bootstrap 'clusteradmin:St0suC4zz0!@mysql107a.private.cineca.it:3306' --user=mysqlrouter --directory /opt/mysqlrouter107/conf --conf-base-port 11071 --name mysqlrouter107 --force


https://bsky.app/profile/ambermechanic.bsky.social
https://bsky.app/profile/batfeula.bsky.social
https://bsky.app/profile/chelfaust.bsky.social
https://bsky.app/profile/chrysope.bsky.social
https://bsky.app/profile/trick17.bsky.social  (fabian)
https://bsky.app/profile/hby.bsky.social
https://bsky.app/profile/kianamosser.bsky.social
https://bsky.app/profile/ktwfc.bsky.social
https://bsky.app/profile/lisnovski.bsky.social
https://bsky.app/profile/lordsovorn.bsky.social
https://bsky.app/profile/maxattacks.bsky.social
https://bsky.app/profile/moonfell-rpg.com
https://bsky.app/profile/nanadragon4.bsky.social
https://bsky.app/profile/neoriceisgood.bsky.social
https://bsky.app/profile/noppixels.bsky.social
https://bsky.app/profile/pc98bot.gang-fight.com
https://bsky.app/profile/phon.bsky.social
https://bsky.app/profile/pixelflag.bsky.social
https://bsky.app/profile/theblindarcher.bsky.social


# ldap
ldap.search.base.user =
ldap.search.base.group = ou=groups
ldap.url = ldap://ldap.cineca.it:389
ldap.base = o=cineca,c=it
#ldap.auth.user.dn = cn=crowd-user,ou=system-user,o=cineca,c=it
#ldap.auth.user.pwd = '##-=_w800mXtyUN'
ldap.auth.user.dn = cn=ugov-reader,ou=ugov,ou=univ,o=cineca,c=it 
ldap.auth.user.pwd = vxxGLhF3q!LHh8rHG 
ldap.bind.user.filter = (|(mail=%s)(uid=%s))
ldap.search.user.filter = mail 

.
├── authorizations.properties
├── environment.properties
├── ldap.properties


ldap.auth.user.pwd = ##-=_w800mXtyUN
-Dit.cineca.toolbox.ldap.file=/production/toolbox-war/ldap.properties



https://boardgamegeek.com/boardgame/332321/alien-fate-of-the-nostromo

"Action Points", "Cooperative Game", "Pick-up and Deliver", "Variable Player Powers"


TODO:   icons
<i class="fa-solid fa-floppy-disk"></i>

TODO:   sliders with value https://codepen.io/t_afif/pen/JjqNEbZ
TODO:   computazione della funzione MIXED, per quadranti / colorazioni
TODO:   save sections
TODO:   save with frame and title (string)
TODO:   https://codepen.io/hexagoncircle/pen/ogvoXpx  Happy New Year 2025 — CSS Paint API
TODO:   https://codepen.io/lekzd/pen/emOKZMv          design a rug
TODO:   https://www.youtube.com/watch?v=-Q-Ngn_FY84   LCD monitor shader
TODO:   https://www.youtube.com/watch?v=e06OM1XonA8   I Tried Making a Real-Time Painterly Renderer, Van Gogh Style
TODO:   https://www.youtube.com/watch?v=SlS3FOmKUbE   Smaller Than Pixel Art: Sub-Pixel Art!

milena: https://huggingface.co/spaces/BoyuanJiang/FitDiT

URLs:
http://localhost:8912/1bit-pattern-gen/
https://masayume.it/1bit-pattern-gen/

patterns: https://mastodon.social/@vga_gradients
http://vectorpoem.com/bots/#gradients

clipboard: DATA/E/Temp/clipboard.txt

https://huggingface.co/spaces/hexgrad/Kokoro-TTS



function floydSteinbergDither(imageData, width, height) {
  const data = imageData.data;

  function getPixelIndex(x, y) {
    return (y * width + x) * 4;
  }

  function getPixelValue(x, y) {
    if (x < 0 || x >= width || y < 0 || y >= height) {
      return 0; // Out of bounds, treat as black
    }
    const index = getPixelIndex(x, y);
    return data[index] / 255; // Convert 0-255 to 0-1
  }

  function setPixelValue(x, y, value) {
    if (x < 0 || x >= width || y < 0 || y >= height) {
      return; // Out of bounds, do nothing
    }
    const index = getPixelIndex(x, y);
    data[index] = Math.round(value * 255);
    data[index + 1] = Math.round(value * 255); // Grayscale, so R=G=B
    data[index + 2] = Math.round(value * 255);
  }

  for (let y = 0; y < height; y++) {
    for (let x = 0; x < width; x++) {
      const oldValue = getPixelValue(x, y);
      const newValue = Math.round(oldValue); // 0 or 1
      setPixelValue(x, y, newValue);
      const error = oldValue - newValue;

      // Distribute error
      setPixelValue(x + 1, y, getPixelValue(x + 1, y) + error * (7 / 16));
      setPixelValue(x - 1, y + 1, getPixelValue(x - 1, y + 1) + error * (3 / 16));
      setPixelValue(x, y + 1, getPixelValue(x, y + 1) + error * (5 / 16));
      setPixelValue(x + 1, y + 1, getPixelValue(x + 1, y + 1) + error * (1 / 16));
    }
  }
  return imageData;
}

// Example usage (assuming you have a canvas and 2D context):

function applyFloydSteinberg(canvas){
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const ditheredImageData = floydSteinbergDither(imageData, canvas.width, canvas.height);
    ctx.putImageData(ditheredImageData, 0, 0);
}

//Example of how to make a test canvas and image:

function createTestImage(canvas){
    const ctx = canvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, canvas.width, 0);
    gradient.addColorStop(0, 'black');
    gradient.addColorStop(1, 'white');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
}

//Example of how to use it all together.
function example(){
    const canvas = document.createElement('canvas');
    canvas.width = 256;
    canvas.height = 256;
    document.body.appendChild(canvas);
    createTestImage(canvas);
    applyFloydSteinberg(canvas);
}

example();

