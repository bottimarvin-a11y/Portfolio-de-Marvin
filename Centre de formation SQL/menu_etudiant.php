<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: login.php");
    exit();
}
include("Connexion.php");

$section = $_GET['section'] ?? 'dashboard';

// Essayer de lier le compte utilisateur à un étudiant via le login
$user_login = $_SESSION['user'];
$etudiant = null;
$etudiant_id = null;

// Chercher l'étudiant correspondant (on utilise le login comme nom ou email)
$rEtu = mysqli_query($conn, "SELECT * FROM etudiants WHERE email LIKE '%$user_login%' OR nom='$user_login' LIMIT 1");
if ($rEtu && mysqli_num_rows($rEtu) > 0) {
    $etudiant = mysqli_fetch_assoc($rEtu);
    $etudiant_id = $etudiant['id_etudiant'];
}

// Stats globales
$nbFormations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM formations"))['c'];
$nbModules = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM modules"))['c'];

// Notes et moyenne de l'étudiant s'il est lié
$mesNotes = [];
$maMoyenne = '—';
$nbMesNotes = 0;
if ($etudiant_id) {
    $rNotes = mysqli_query($conn, "SELECT n.note, m.nom_module, m.coefficient FROM notes n JOIN modules m ON n.id_module=m.id_module WHERE n.id_etudiant=$etudiant_id ORDER BY n.id_note DESC");
    while ($n = mysqli_fetch_assoc($rNotes)) {
        $mesNotes[] = $n;
    }
    $nbMesNotes = count($mesNotes);
    $avgR = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ROUND(AVG(note),2) as avg FROM notes WHERE id_etudiant=$etudiant_id"));
    $maMoyenne = $avgR['avg'] ?? '—';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👤 Étudiant — Centre de Formation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        
        .sidebar {
            width: 260px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-glass);
            padding: 1.5rem 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--border-glass);
            margin-bottom: 1rem;
        }
        
        .sidebar-brand h2 {
            font-size: 1.1rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-cyan), #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sidebar-brand small { color: var(--text-muted); font-size: 0.75rem; }
        
        .sidebar-section {
            padding: 0.5rem 1rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            font-weight: 700;
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }
        
        .sidebar a:hover, .sidebar a.active {
            color: var(--text-primary);
            background: rgba(34, 211, 238, 0.08);
            border-left-color: var(--accent-cyan);
        }
        
        .sidebar a .nav-icon { font-size: 1.1rem; }
        
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem 2.5rem;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-glass);
        }
        
        .top-bar h1 { font-size: 1.6rem; font-weight: 800; }
        
        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 999px;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        
        .user-badge .role-tag {
            padding: 2px 10px;
            background: linear-gradient(135deg, var(--accent-cyan), #67e8f9);
            border-radius: 999px;
            color: #0f172a;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .dash-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.2rem;
            margin-bottom: 2.5rem;
        }
        
        .dash-card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            padding: 1.5rem;
            backdrop-filter: blur(16px);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .dash-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-cyan), #67e8f9);
            opacity: 0;
            transition: var(--transition);
        }
        
        .dash-card:hover {
            transform: translateY(-4px);
            border-color: rgba(34, 211, 238, 0.3);
            box-shadow: 0 8px 30px rgba(34, 211, 238, 0.12);
        }
        
        .dash-card:hover::after { opacity: 1; }
        .dash-card .card-icon { font-size: 1.5rem; margin-bottom: 0.8rem; }
        .dash-card .card-value {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--accent-cyan), #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dash-card .card-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            padding: 1.8rem;
            backdrop-filter: blur(16px);
            margin-bottom: 2rem;
        }
        
        .panel h2 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }
        
        .admin-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            background: rgba(255,255,255,0.02);
            border-bottom: 1px solid var(--border-glass);
        }
        
        .admin-table td {
            padding: 12px 16px;
            color: var(--text-secondary);
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        
        .admin-table tr:hover td { background: rgba(34, 211, 238, 0.04); }
        
        .perm-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            background: rgba(34, 211, 238, 0.06);
            border: 1px solid rgba(34, 211, 238, 0.15);
            border-radius: 12px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }
        
        .perm-info .perm-icon { font-size: 1.3rem; }
        
        .profil-card {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .profil-item {
            padding: 1rem 1.2rem;
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border-glass);
            border-radius: 12px;
        }
        
        .profil-item .profil-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 6px;
        }
        
        .profil-item .profil-value {
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .bulletin-header {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(34,211,238,0.08), rgba(99,102,241,0.08));
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        
        .bulletin-header h3 {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }
        
        .bulletin-header p {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .moyenne-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 1.5rem;
            margin-top: 1rem;
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border-glass);
            border-radius: 12px;
            text-align: center;
        }
        
        .moyenne-box .moy-value {
            font-size: 2.5rem;
            font-weight: 900;
        }
        
        .moyenne-box .moy-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 1rem; }
            .dash-grid { grid-template-columns: repeat(2, 1fr); }
            .profil-card { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    
    <div class="admin-layout">
        <nav class="sidebar">
            <div class="sidebar-brand">
                <h2>👤 Centre Formation</h2>
                <small>Espace Étudiant</small>
            </div>
            
            <div class="sidebar-section">Navigation</div>
            <a href="?section=dashboard" class="<?= $section=='dashboard'?'active':'' ?>">
                <span class="nav-icon">📊</span> Tableau de bord
            </a>
            
            <div class="sidebar-section">Mon espace</div>
            <a href="?section=profil" class="<?= $section=='profil'?'active':'' ?>">
                <span class="nav-icon">👤</span> Mon profil
            </a>
            <a href="?section=mes_notes" class="<?= $section=='mes_notes'?'active':'' ?>">
                <span class="nav-icon">📝</span> Mes notes
            </a>
            <a href="?section=bulletin" class="<?= $section=='bulletin'?'active':'' ?>">
                <span class="nav-icon">📄</span> Mon bulletin
            </a>
            
            <div class="sidebar-section">Consulter</div>
            <a href="?section=formations" class="<?= $section=='formations'?'active':'' ?>">
                <span class="nav-icon">📚</span> Formations
            </a>
            <a href="?section=modules" class="<?= $section=='modules'?'active':'' ?>">
                <span class="nav-icon">📦</span> Modules
            </a>
            <a href="?section=salles" class="<?= $section=='salles'?'active':'' ?>">
                <span class="nav-icon">🏫</span> Salles
            </a>
            <a href="?section=formateurs" class="<?= $section=='formateurs'?'active':'' ?>">
                <span class="nav-icon">🎓</span> Formateurs
            </a>
            
            <div class="sidebar-section" style="margin-top:auto;"></div>
            <a href="login.php?logout=1" style="color:var(--accent-rose);">
                <span class="nav-icon">🚪</span> Déconnexion
            </a>
        </nav>
        
        <main class="main-content">
            <div class="top-bar">
                <h1>
                    <?php
                    $titles = [
                        'dashboard' => '📊 Tableau de bord',
                        'profil' => '👤 Mon profil',
                        'mes_notes' => '📝 Mes notes',
                        'bulletin' => '📄 Mon bulletin',
                        'formations' => '📚 Formations',
                        'modules' => '📦 Modules',
                        'salles' => '🏫 Salles',
                        'formateurs' => '🎓 Formateurs',
                    ];
                    echo $titles[$section] ?? 'Étudiant';
                    ?>
                </h1>
                <div class="user-badge">
                    <span class="role-tag">Étudiant</span>
                    <?= htmlspecialchars($_SESSION['user']) ?>
                </div>
            </div>
            
            <div class="perm-info">
                <span class="perm-icon">🔒</span>
                <span><strong>Permissions :</strong> Consultation de votre profil, notes et bulletin · Accès en lecture aux formations, modules, salles et formateurs · Aucune modification autorisée</span>
            </div>
            
            <?php if ($section === 'dashboard'): ?>
                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="card-icon">📝</div>
                        <div class="card-value"><?= $nbMesNotes ?></div>
                        <div class="card-label">Mes notes</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">⭐</div>
                        <div class="card-value"><?= $maMoyenne ?></div>
                        <div class="card-label">Ma moyenne</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">📚</div>
                        <div class="card-value"><?= $nbFormations ?></div>
                        <div class="card-label">Formations</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">📦</div>
                        <div class="card-value"><?= $nbModules ?></div>
                        <div class="card-label">Modules</div>
                    </div>
                </div>
                
                <?php if ($etudiant): ?>
                <div class="panel">
                    <h2>👋 Bienvenue, <?= $etudiant['prenom'] ?> <?= $etudiant['nom'] ?></h2>
                    <p style="color:var(--text-muted);">Consultez vos notes, votre bulletin et les informations relatives à votre formation.</p>
                </div>
                <?php else: ?>
                <div class="panel">
                    <h2>👋 Bienvenue, <?= htmlspecialchars($_SESSION['user']) ?></h2>
                    <p style="color:var(--text-muted);">Votre compte n'est pas encore lié à un profil étudiant. Contactez l'administration pour associer votre compte.</p>
                </div>
                <?php endif; ?>
                
                <?php if (count($mesNotes) > 0): ?>
                <div class="panel">
                    <h2>📝 Dernières notes</h2>
                    <table class="admin-table">
                        <thead><tr><th>Module</th><th>Note</th></tr></thead>
                        <tbody>
                        <?php foreach (array_slice($mesNotes, 0, 5) as $n):
                            $color = $n['note'] >= 15 ? 'emerald' : ($n['note'] >= 10 ? 'amber' : 'rose');
                        ?>
                            <tr>
                                <td class="name-cell"><?= $n['nom_module'] ?></td>
                                <td><span class="badge badge-<?= $color ?>"><?= $n['note'] ?>/20</span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
            <?php elseif ($section === 'profil'): ?>
                <?php if ($etudiant): ?>
                <div class="panel">
                    <h2>👤 Mes informations personnelles</h2>
                    <div class="profil-card">
                        <div class="profil-item">
                            <div class="profil-label">Nom</div>
                            <div class="profil-value"><?= $etudiant['nom'] ?></div>
                        </div>
                        <div class="profil-item">
                            <div class="profil-label">Prénom</div>
                            <div class="profil-value"><?= $etudiant['prenom'] ?></div>
                        </div>
                        <div class="profil-item">
                            <div class="profil-label">Date de naissance</div>
                            <div class="profil-value"><?= $etudiant['date_naissance'] ?></div>
                        </div>
                        <div class="profil-item">
                            <div class="profil-label">Email</div>
                            <div class="profil-value"><?= $etudiant['email'] ?></div>
                        </div>
                        <div class="profil-item">
                            <div class="profil-label">Téléphone</div>
                            <div class="profil-value"><?= $etudiant['telephone'] ?></div>
                        </div>
                        <div class="profil-item">
                            <div class="profil-label">Identifiant étudiant</div>
                            <div class="profil-value">#<?= $etudiant['id_etudiant'] ?></div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="panel">
                    <h2>👤 Profil non lié</h2>
                    <p style="color:var(--text-muted);">Votre compte utilisateur n'est pas encore rattaché à un profil étudiant dans la base de données. Veuillez contacter un administrateur.</p>
                </div>
                <?php endif; ?>
                
            <?php elseif ($section === 'mes_notes'): ?>
                <div class="panel">
                    <h2>📝 Toutes mes notes</h2>
                    <?php if (count($mesNotes) > 0): ?>
                    <table class="admin-table">
                        <thead><tr><th>Module</th><th>Coefficient</th><th>Note</th></tr></thead>
                        <tbody>
                        <?php foreach ($mesNotes as $n):
                            $color = $n['note'] >= 15 ? 'emerald' : ($n['note'] >= 10 ? 'amber' : 'rose');
                        ?>
                            <tr>
                                <td class="name-cell"><?= $n['nom_module'] ?></td>
                                <td><span class="badge badge-amber">Coeff. <?= $n['coefficient'] ?></span></td>
                                <td><span class="badge badge-<?= $color ?>"><?= $n['note'] ?>/20</span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p style="color:var(--text-muted); text-align:center; padding:2rem;">Aucune note disponible pour le moment.</p>
                    <?php endif; ?>
                </div>
                
            <?php elseif ($section === 'bulletin'): ?>
                <div class="panel">
                    <div class="bulletin-header">
                        <h3>🏛️ Centre de Formation — Bulletin de notes</h3>
                        <?php if ($etudiant): ?>
                        <p><?= $etudiant['prenom'] ?> <?= $etudiant['nom'] ?> — ID #<?= $etudiant['id_etudiant'] ?></p>
                        <?php else: ?>
                        <p><?= htmlspecialchars($_SESSION['user']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (count($mesNotes) > 0): ?>
                    <table class="admin-table">
                        <thead><tr><th>Module</th><th>Coefficient</th><th>Note</th></tr></thead>
                        <tbody>
                        <?php 
                        $totalPondere = 0;
                        $totalCoeff = 0;
                        foreach ($mesNotes as $n):
                            $color = $n['note'] >= 15 ? 'emerald' : ($n['note'] >= 10 ? 'amber' : 'rose');
                            $totalPondere += $n['note'] * $n['coefficient'];
                            $totalCoeff += $n['coefficient'];
                        ?>
                            <tr>
                                <td class="name-cell"><?= $n['nom_module'] ?></td>
                                <td><span class="badge badge-amber">Coeff. <?= $n['coefficient'] ?></span></td>
                                <td><span class="badge badge-<?= $color ?>"><?= $n['note'] ?>/20</span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php
                    $moyPonderee = $totalCoeff > 0 ? round($totalPondere / $totalCoeff, 2) : 0;
                    $moyColor = $moyPonderee >= 15 ? 'var(--accent-emerald)' : ($moyPonderee >= 10 ? 'var(--accent-amber)' : 'var(--accent-rose)');
                    $decision = $moyPonderee >= 10 ? '✅ Admis(e)' : '❌ Ajourné(e)';
                    ?>
                    
                    <div class="moyenne-box">
                        <div>
                            <div class="moy-value" style="color:<?= $moyColor ?>"><?= $moyPonderee ?>/20</div>
                            <div class="moy-label">Moyenne pondérée</div>
                        </div>
                        <div style="font-size:2rem; margin:0 1rem;">|</div>
                        <div>
                            <div class="moy-value" style="font-size:1.5rem; color:<?= $moyColor ?>"><?= $decision ?></div>
                            <div class="moy-label">Décision du jury</div>
                        </div>
                    </div>
                    <?php else: ?>
                    <p style="color:var(--text-muted); text-align:center; padding:2rem;">Aucune note disponible — bulletin vide.</p>
                    <?php endif; ?>
                </div>
                
            <?php elseif ($section === 'formations'): ?>
                <div class="panel">
                    <h2>📚 Formations disponibles <small style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture seule</small></h2>
                    <table class="admin-table">
                        <thead><tr><th>Intitulé</th><th>Durée</th><th>Niveau</th><th>Formateur</th></tr></thead>
                        <tbody>
                        <?php
                        $r = mysqli_query($conn, "SELECT f.*, fo.nom as f_nom, fo.prenom as f_prenom FROM formations f LEFT JOIN formateurs fo ON f.id_formateur=fo.id_formateur ORDER BY f.id_formation");
                        while ($row = mysqli_fetch_assoc($r)):
                        ?>
                            <tr>
                                <td class="name-cell"><?= $row['intitule'] ?></td>
                                <td><?= $row['duree'] ?>h</td>
                                <td><span class="badge badge-cyan"><?= $row['niveau'] ?></span></td>
                                <td><?= $row['f_prenom'] ?> <?= $row['f_nom'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php elseif ($section === 'modules'): ?>
                <div class="panel">
                    <h2>📦 Modules <small style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture seule</small></h2>
                    <table class="admin-table">
                        <thead><tr><th>Module</th><th>Coefficient</th><th>Formation</th></tr></thead>
                        <tbody>
                        <?php
                        $r = mysqli_query($conn, "SELECT m.*, f.intitule FROM modules m LEFT JOIN formations f ON m.id_formation=f.id_formation ORDER BY m.id_module");
                        while ($row = mysqli_fetch_assoc($r)):
                        ?>
                            <tr>
                                <td class="name-cell"><?= $row['nom_module'] ?></td>
                                <td><span class="badge badge-amber">Coeff. <?= $row['coefficient'] ?></span></td>
                                <td><?= $row['intitule'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php elseif ($section === 'salles'): ?>
                <div class="panel">
                    <h2>🏫 Salles <small style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture seule</small></h2>
                    <table class="admin-table">
                        <thead><tr><th>Nom</th><th>Capacité</th><th>Équipement</th></tr></thead>
                        <tbody>
                        <?php
                        $r = mysqli_query($conn, "SELECT * FROM salles ORDER BY id_salle");
                        while ($row = mysqli_fetch_assoc($r)):
                        ?>
                            <tr>
                                <td class="name-cell"><?= $row['nom_salle'] ?></td>
                                <td><?= $row['capacite'] ?> places</td>
                                <td><?= $row['equipement'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php elseif ($section === 'formateurs'): ?>
                <div class="panel">
                    <h2>🎓 Formateurs <small style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture seule</small></h2>
                    <table class="admin-table">
                        <thead><tr><th>Nom</th><th>Spécialité</th><th>Email</th></tr></thead>
                        <tbody>
                        <?php
                        $r = mysqli_query($conn, "SELECT * FROM formateurs ORDER BY nom");
                        while ($row = mysqli_fetch_assoc($r)):
                        ?>
                            <tr>
                                <td class="name-cell"><?= $row['prenom'] ?> <?= $row['nom'] ?></td>
                                <td><span class="badge badge-violet"><?= $row['specialite'] ?></span></td>
                                <td><?= $row['email'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
