<?php
// register.php
session_start();
require 'db.php'; //On appel le fichier db.php pour faire le lien entre notre base de données et le fichier

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Quand une requête de type "POST"
    $name = htmlspecialchars($_POST['name']); // Définition de la variable "name"
    $mail = htmlspecialchars($_POST['mail']); // Définition de la variable "mail"
    $phone = $_POST['phone']; // Définition de la variable "phone"
    $password = $_POST['password']; // Définition de la variable "password"

    // 1. Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE mail = ?"); //On prépare une requête pour selectionner l'id |
    //d'un utilisateur qui à l'addresse mail rentre dans la variable mail
    $stmt->execute([$mail]);
    
    if ($stmt->rowCount() > 0) {
        echo "Cet email est déjà utilisé.";
    } else {
        // 2. Hacher le mot de passe (Indispensable pour la sécurité)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insérer dans la base
        $stmt= $pdo->prepare("INSERT INTO users (name, mail, password, phone) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $mail, $passwordHash, $phone])) {
            // Redirection vers la page de login après succès
            header("Location: login.php");
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Inscription — Guardia</title>
    <link rel="stylesheet" href="./../Style/register.css"/>
</head>
<body>
    <main class="wrap" role="main">
        <section class="card">
            <h2 id="signup-title">Créer un compte</h2>
            <form id="registerForm" action="register.php" method="POST">
                <div class="form-row">
                    <div>
                        <label for="fullname">Nom complet</label>
                        <input id="fullname" name="name" type="text" required placeholder="Prénom Nom" />
                        <div class="errors" id="err-fullname"></div>
                    </div>
                    <div>
                        <label for="phone">Téléphone</label>
                        <input id="phone" name="phone" type="tel" placeholder="+33 6..." />
                    </div>
                </div>

                <div>
                    <label for="email">Adresse e-mail</label>
                    <input id="email" name="mail" type="email" required placeholder="vous@exemple.com" />
                    <div class="errors" id="err-email"></div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="password">Mot de passe</label>
                        <input id="password" name="password" type="password" required minlength="8" />
                        <div class="errors" id="err-password"></div>
                    </div>
                    <div>
                        <label for="confirm">Confirmer</label>
                        <input id="confirm" name="confirm" type="password" required />
                        <div class="errors" id="err-confirm"></div>
                    </div>
                </div>

                <div style="margin-top:15px;">
                    <button id="submitBtn" class="btn" type="submit">S'inscrire</button>
                    <div id="progress" style="display:none; color:gray;">Enregistrement en cours...</div>
                </div>

                <div class="success-box" id="successBox" style="display:none; color:green; margin-top:10px;"></div>
                <div class="error-box" id="globalError" style="display:none; color:red; margin-top:10px;"></div>
                
                <p style="margin-top:20px;">Déjà un compte ? <a href="login.html">Se connecter</a></p>
            </form>
        </section>
    </main>
</body>
</html>