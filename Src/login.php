<?php
// Fichier: login.php

// Démarrer la session PHP
session_start();

// 1. Inclure le fichier de configuration de la base de données
require_once 'config.php';

// Initialiser les variables d'erreur
$email = $password = "";
$error = "";

// Traiter les données du formulaire lorsque le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer et nettoyer les données
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    
    // --- 2. Validation des données ---
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Veuillez entrer une adresse e-mail valide.";
    } else if (empty($password)) {
        $error = "Veuillez entrer votre mot de passe.";
    }

    // --- 3. Vérification des identifiants dans la base de données ---
    if (empty($error)) {
        $sql = "SELECT id, fullname, password_hash FROM users WHERE email = :email";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = $email;
            
            if ($stmt->execute()) {
                // Vérifier si l'utilisateur existe
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $fullname = $row["fullname"];
                        $hashed_password = $row["password_hash"];
                        
                        // Vérifier le mot de passe avec le hachage sécurisé
                        if (password_verify($password, $hashed_password)) {
                            // Mot de passe correct, démarrer la session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["fullname"] = $fullname;
                            
                            // Rediriger l'utilisateur vers la page de bienvenue
                            header("location: index.php"); // Remplacez par votre page de destination
                            exit;
                        } else {
                            // Mot de passe invalide
                            $error = "L'e-mail ou le mot de passe est incorrect.";
                        }
                    }
                } else {
                    // E-mail non trouvé
                    $error = "L'e-mail ou le mot de passe est incorrect.";
                }
            } else {
                $error = "Oops! Une erreur s'est produite. Veuillez réessayer plus tard.";
            }
            unset($stmt);
        }
    }
    
    // Fermer la connexion
    unset($pdo);
}

// NOTE: Vous devrez afficher $error dans votre page HTML/JS
// par exemple: echo "<p style='color:red;'>$error</p>";
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../Style/style_log.css">
    <title>Connexion</title>
</head>
<body>
    <div class="login" id="login">
        <h2 class="login-heading">Connexion</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="on">
            <input type="email" placeholder="Email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
            <input type="password" placeholder="Mot de passe" id="password" name="password" required>
            <input type="submit" id="send" name="send" value="Se connecter">
        </form>
        
    </div>


    
</body>
</html>