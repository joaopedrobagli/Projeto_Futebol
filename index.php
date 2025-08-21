<?php

session_start();

$players = [
    [
        'id' => 1,
        'name' => 'Neymar Jr',
        'team' => 'Santos',
        'position' => 'Atacante',
        'age' => 31,
     
        'stats' => [88, 85, 86, 94, 30, 65],
        'nationality' => 'Brasil',
        'value' => '€60M'
    ],
    [
        'id' => 2,
        'name' => 'Vinicius Jr',
        'team' => 'Real Madrid',
        'position' => 'Atacante',
        'age' => 23,
        
        'stats' => [94, 82, 75, 91, 28, 70],
        'nationality' => 'Brasil',
        'value' => '€120M'
    ],
    [
        'id' => 3,
        'name' => 'Casemiro',
        'team' => 'Manchester United',
        'position' => 'Volante',
        'age' => 31,
        
        'stats' => [65, 70, 82, 75, 86, 85],
        'nationality' => 'Brasil',
        'value' => '€40M'
    ],
    [
        'id' => 4,
        'name' => 'Alisson Becker',
        'team' => 'Liverpool',
        'position' => 'Goleiro',
        'age' => 31,
        
        'stats' => [85, 40, 60, 45, 90, 78],
        'nationality' => 'Brasil',
        'value' => '€35M'
    ],
    [
        'id' => 5,
        'name' => 'Endrick',
        'team' => 'Real Madrid',
        'position' => 'Atacante',
        'age' => 18,
        
        'stats' => [81, 44, 60, 25, 70, 68],
        'nationality' => 'Brasil',
        'value' => '€55M'
    ],
    [
        'id' => 6,
        'name' => 'Estevão',
        'team' => 'Chelsea',
        'position' => 'Atacante',
        'age' => 18,
        
        'stats' => [82, 33, 50, 35, 70, 71],
        'nationality' => 'Brasil',
        'value' => '€50M'
    ]
];


if (!isset($_SESSION['comparison'])) {
    $_SESSION['comparison'] = [];
}

if (isset($_GET['add_to_compare']) && !in_array($_GET['add_to_compare'], $_SESSION['comparison'])) {
    if (count($_SESSION['comparison']) < 2) {
        $_SESSION['comparison'][] = $_GET['add_to_compare'];
    }
}


if (isset($_GET['clear_compare'])) {
    $_SESSION['comparison'] = [];
}


$filtered_players = $players;
if (isset($_GET['position']) && $_GET['position'] !== 'all') {
    $filtered_players = array_filter($players, function($player) {
        return $player['position'] === $_GET['position'];
    });
}

if (isset($_GET['team']) && $_GET['team'] !== 'all') {
    $filtered_players = array_filter($filtered_players, function($player) {
        return $player['team'] === $_GET['team'];
    });
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scout Futebol</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(90deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        header p {
            opacity: 0.8;
        }
        
        .filters {
            background: #f8f9fa;
            padding: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .filter-group label {
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        select, button {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
        }
        
        button {
            background: #4e54c8;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #3f43a6;
        }
        
        .comparison-section {
            padding: 20px;
            background: #e9ecef;
            margin: 20px;
            border-radius: 10px;
        }
        
        .players-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        
        .player-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .player-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .player-header {
            background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .player-name {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .player-info {
            display: flex;
            justify-content: center;
            gap: 15px;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .player-body {
            padding: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            display: flex;
            flex-direction: column;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .stat-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .stat-fill {
            height: 100%;
            background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
            border-radius: 4px;
        }
        
        .compare-btn {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .compare-btn:hover {
            background: #218838;
        }
        
        .comparison-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            background: #1a1a2e;
            color: white;
            margin-top: 40px;
        }
        
        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
            }
            
            .players-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Scout Futebol ⚽</h1>
            <p>Sistema de Scout de Futebol</p>
        </header>
        
        <div class="filters">
            <div class="filter-group">
                <label for="position">Posição</label>
                <select id="position" onchange="filterPlayers()">
                    <option value="all">Todas</option>
                    <option value="Atacante">Atacante</option>
                    <option value="Volante">Volante</option>
                    <option value="Goleiro">Goleiro</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="team">Time</label>
                <select id="team" onchange="filterPlayers()">
                    <option value="all">Todos</option>
                    <option value="Al Hilal">Al Hilal</option>
                    <option value="Real Madrid">Real Madrid</option>
                    <option value="Manchester United">Manchester United</option>
                    <option value="Liverpool">Liverpool</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>&nbsp;</label>
                <button onclick="clearFilters()">Limpar Filtros</button>
            </div>
        </div>
        
        <?php if (!empty($_SESSION['comparison'])): ?>
        <div class="comparison-section">
            <h2>Jogadores Selecionados para Comparação</h2>
            <div class="comparison-container">
                <?php foreach ($_SESSION['comparison'] as $player_id): 
                    $player = array_filter($players, function($p) use ($player_id) {
                        return $p['id'] == $player_id;
                    });
                    $player = array_values($player)[0] ?? null;
                    if ($player):
                ?>
                <div class="player-card">
                    <div class="player-header">
                        <h3 class="player-name"><?= $player['name'] ?></h3>
                        <div class="player-info">
                            <span><?= $player['position'] ?></span>
                            <span><?= $player['team'] ?></span>
                        </div>
                    </div>
                    <div class="player-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label">Idade</span>
                                <span class="stat-value"><?= $player['age'] ?> anos</span>
                            </div>
                            
                            <div class="stat-item">
                                <span class="stat-label">Nacionalidade</span>
                                <span class="stat-value"><?= $player['nationality'] ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Valor de Mercado</span>
                                <span class="stat-value"><?= $player['value'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="?clear_compare=true" class="compare-btn" style="display: inline-block; width: auto; padding: 10px 20px;">
                    Limpar Comparação
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="players-grid">
            <?php foreach ($filtered_players as $player): ?>
            <div class="player-card">
                <div class="player-header">
                    <h3 class="player-name"><?= $player['name'] ?></h3>
                    <div class="player-info">
                        <span><?= $player['position'] ?></span>
                        <span><?= $player['team'] ?></span>
                    </div>
                </div>
                
                <div class="player-body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-label">Idade</span>
                            <span class="stat-value"><?= $player['age'] ?> anos</span>
                        </div>
                       
                        <div class="stat-item">
                            <span class="stat-label">Nacionalidade</span>
                            <span class="stat-value"><?= $player['nationality'] ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Valor de Mercado</span>
                            <span class="stat-value"><?= $player['value'] ?></span>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <?php 
                        $stat_labels = ['Velocidade', 'Finalização', 'Passe', 'Drible', 'Defesa', 'Físico'];
                        foreach ($player['stats'] as $index => $stat): 
                        ?>
                        <div class="stat-item">
                            <span class="stat-label"><?= $stat_labels[$index] ?></span>
                            <span class="stat-value"><?= $stat ?></span>
                            <div class="stat-bar">
                                <div class="stat-fill" style="width: <?= $stat ?>%;"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="?add_to_compare=<?= $player['id'] ?>" class="compare-btn">
                        <?= in_array($player['id'], $_SESSION['comparison']) ? 'Selecionado' : 'Comparar Jogador' ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <footer>
            <p> &copy; 2025 - Desenvolvido Por João Pedro Bagli</p>
            
        </footer>
    </div>

    <script>
        function filterPlayers() {
            const position = document.getElementById('position').value;
            const team = document.getElementById('team').value;
            window.location.href = `?position=${position}&team=${team}`;
        }
        
        function clearFilters() {
            window.location.href = '?';
        }
        
    
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('position')) {
            document.getElementById('position').value = urlParams.get('position');
        }
        if (urlParams.get('team')) {
            document.getElementById('team').value = urlParams.get('team');
        }
    </script>
</body>
</html>