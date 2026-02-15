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

        #kanji-panel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 30px;
            min-width: 350px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            color: #333;
            z-index: 2000;
            opacity: 0;
            scale: 0.8;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        #kanji-panel.show {
            opacity: 1;
            scale: 1;
            pointer-events: all;
        }

        #kanji-panel .kanji-display {
            font-size: 80px;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: bold;
        }

        #kanji-panel .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        #kanji-panel .info-label {
            font-weight: bold;
            color: #555;
            min-width: 100px;
        }

        #kanji-panel .info-value {
            color: #333;
            flex: 1;
            text-align: right;
        }

        #kanji-panel .connections-section {
            margin-top: 20px;
        }

        #kanji-panel .connections-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        #kanji-panel .connection-item {
            background: rgba(74, 144, 226, 0.1);
            border: 1px solid rgba(74, 144, 226, 0.3);
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #kanji-panel .connection-item:hover {
            background: rgba(74, 144, 226, 0.2);
            transform: translateY(-1px);
        }

        #panel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1999;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        #panel-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        #kanji-panel .close-hint {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #999;
            font-size: 12px;
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

    <div id="panel-overlay"></div>
    
    <div id="kanji-panel">
        <div class="close-hint">ESC or click outside to close</div>
        <div class="kanji-display" id="panel-kanji">水</div>
        <div class="info-row">
            <span class="info-label">Meaning:</span>
            <span class="info-value" id="panel-meaning">Water</span>
        </div>
        <div class="info-row">
            <span class="info-label">Radical:</span>
            <span class="info-value" id="panel-radical">水 (water)</span>
        </div>
        <div class="info-row">
            <span class="info-label">Stroke Count:</span>
            <span class="info-value" id="panel-strokes">4</span>
        </div>
        <div class="info-row">
            <span class="info-label">Reading (On):</span>
            <span class="info-value" id="panel-on">スイ</span>
        </div>
        <div class="info-row">
            <span class="info-label">Reading (Kun):</span>
            <span class="info-value" id="panel-kun">みず</span>
        </div>
        <div class="connections-section">
            <div class="info-label">Connected Kanji:</div>
            <div class="connections-list" id="panel-connections"></div>
        </div>
    </div>

    <div id="container"></div>

    <script>
        const kanjiData = {
            '水': { meaning: 'Water', radical: '水', x: 400, y: 300, connections: ['氷', '河', '海', '泳'], strokes: 4, on: 'スイ', kun: 'みず' },
            '氷': { meaning: 'Ice', radical: '水', x: 300, y: 200, connections: ['水', '冷'], strokes: 5, on: 'ヒョウ', kun: 'こおり' },
            '河': { meaning: 'River', radical: '水', x: 500, y: 200, connections: ['水', '海', '川'], strokes: 8, on: 'カ', kun: 'かわ' },
            '海': { meaning: 'Sea', radical: '水', x: 600, y: 300, connections: ['水', '河', '波'], strokes: 9, on: 'カイ', kun: 'うみ' },
            '泳': { meaning: 'Swim', radical: '水', x: 350, y: 400, connections: ['水', '游'], strokes: 8, on: 'エイ', kun: 'およ.ぐ' },
            '川': { meaning: 'River', radical: '川', x: 550, y: 100, connections: ['河', '流'], strokes: 3, on: 'セン', kun: 'かわ' },
            '波': { meaning: 'Wave', radical: '水', x: 700, y: 250, connections: ['海'], strokes: 8, on: 'ハ', kun: 'なみ' },
            '流': { meaning: 'Flow', radical: '水', x: 650, y: 150, connections: ['川', '河'], strokes: 10, on: 'リュウ', kun: 'なが.れる' },
            '冷': { meaning: 'Cold', radical: '冫', x: 200, y: 150, connections: ['氷'], strokes: 7, on: 'レイ', kun: 'つめ.たい' },
            '游': { meaning: 'Swim', radical: '水', x: 250, y: 450, connections: ['泳'], strokes: 12, on: 'ユウ', kun: 'およ.ぐ' },
            
            '火': { meaning: 'Fire', radical: '火', x: 800, y: 400, connections: ['炎', '燃', '熱'], strokes: 4, on: 'カ', kun: 'ひ' },
            '炎': { meaning: 'Flame', radical: '火', x: 900, y: 350, connections: ['火', '燃'], strokes: 8, on: 'エン', kun: 'ほのお' },
            '燃': { meaning: 'Burn', radical: '火', x: 850, y: 500, connections: ['火', '炎'], strokes: 16, on: 'ネン', kun: 'も.える' },
            '熱': { meaning: 'Heat', radical: '火', x: 750, y: 500, connections: ['火'], strokes: 15, on: 'ネツ', kun: 'あつ.い' },
            
            '木': { meaning: 'Tree', radical: '木', x: 100, y: 300, connections: ['森', '林', '材'], strokes: 4, on: 'ボク', kun: 'き' },
            '森': { meaning: 'Forest', radical: '木', x: 50, y: 200, connections: ['木', '林'], strokes: 12, on: 'シン', kun: 'もり' },
            '林': { meaning: 'Woods', radical: '木', x: 150, y: 200, connections: ['木', '森'], strokes: 8, on: 'リン', kun: 'はやし' },
            '材': { meaning: 'Material', radical: '木', x: 100, y: 400, connections: ['木'], strokes: 7, on: 'ザイ', kun: '材料' },
            
            '山': { meaning: 'Mountain', radical: '山', x: 400, y: 600, connections: ['峰', '岩'], strokes: 3, on: 'サン', kun: 'やま' },
            '峰': { meaning: 'Peak', radical: '山', x: 350, y: 700, connections: ['山'], strokes: 10, on: 'ホウ', kun: 'みね' },
            '岩': { meaning: 'Rock', radical: '山', x: 450, y: 700, connections: ['山'], strokes: 8, on: 'ガン', kun: 'いわ' }
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
                showKanjiPanel(kanji);
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

        function showKanjiPanel(kanji) {
            if (!kanjiData[kanji]) return;
            
            const data = kanjiData[kanji];
            const panel = document.getElementById('kanji-panel');
            const overlay = document.getElementById('panel-overlay');
            
            // Update panel content
            document.getElementById('panel-kanji').textContent = kanji;
            document.getElementById('panel-meaning').textContent = data.meaning;
            document.getElementById('panel-radical').textContent = `${data.radical} (${getRadicalMeaning(data.radical)})`;
            document.getElementById('panel-strokes').textContent = data.strokes;
            document.getElementById('panel-on').textContent = data.on;
            document.getElementById('panel-kun').textContent = data.kun;
            
            // Update connections
            const connectionsList = document.getElementById('panel-connections');
            connectionsList.innerHTML = '';
            data.connections.forEach(connectedKanji => {
                if (kanjiData[connectedKanji]) {
                    const connectionItem = document.createElement('div');
                    connectionItem.className = 'connection-item';
                    connectionItem.textContent = connectedKanji;
                    connectionItem.addEventListener('click', () => {
                        hideKanjiPanel();
                        setTimeout(() => showKanjiPanel(connectedKanji), 100);
                    });
                    connectionsList.appendChild(connectionItem);
                }
            });
            
            // Show panel
            overlay.classList.add('show');
            panel.classList.add('show');
            
            // Also center the view on this kanji
            centerOnTile(kanji);
        }

        function hideKanjiPanel() {
            const panel = document.getElementById('kanji-panel');
            const overlay = document.getElementById('panel-overlay');
            panel.classList.remove('show');
            overlay.classList.remove('show');
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

        // Panel close functionality
        document.getElementById('panel-overlay').addEventListener('click', hideKanjiPanel);
        
        // ESC key to close panel
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                hideKanjiPanel();
            }
        });

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