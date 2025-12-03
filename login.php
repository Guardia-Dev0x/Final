<?php
// login.php
session_start();
require 'db.php';

// Si l'utilisateur est déjà connecté, on le redirige (par exemple vers dashboard.php ou index.html)
if (isset($_SESSION['users'])) {
    header("Location: index.php"); 
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = htmlspecialchars($_POST['mail']);
    $password = $_POST['password'];

    // 1. On cherche l'utilisateur par son email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE mail = ?");
    $stmt->execute([$mail]);
    $user = $stmt->fetch();

    // 2. On vérifie le mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // SUCCÈS : On enregistre les infos en session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name']; // On garde le nom pour l'afficher plus tard
        
        // Redirection vers l'accueil ou le tableau de bord
        header("Location: index.php");
        exit();
    } else {
        // ERREUR
        $message = "Email ou mot de passe incorrect.";
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Connexion — Guardia</title>
    <link rel="stylesheet" href="./../Style/register.css"/>
</head>
<body>
    <main class="wrap" role="main">
        <section class="card">
            <h2 id="login-title">Se connecter</h2>
            
            <form id="loginForm" action="login.php" method="POST">
                
                <div>
                    <label for="email">Adresse e-mail</label>
                    <input id="email" name="mail" type="email" required placeholder="vous@exemple.com" value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : ''; ?>" />
                </div>

                <div style="margin-top: 15px;">
                    <label for="password">Mot de passe</label>
                    <input id="password" name="password" type="password" required />
                </div>

                <div style="margin-top:20px;">
                    <button id="submitBtn" class="btn" type="submit">Connexion</button>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="error-box" style="color:red; margin-top:15px; text-align:center;">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <p style="margin-top:20px;">Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
            </form>
        </section>
    </main>
</body>
</html>