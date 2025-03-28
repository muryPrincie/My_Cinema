<?php
include 'dbconnect.php';  


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: members.php"); 
    exit;
}

$user_id = intval($_GET['id']); 


$query = "SELECT 
            user.firstname,
            user.lastname,
            user.email,
            membership.id_subscription,
            subscription.name AS subscription_name
          FROM 
            user
          JOIN 
            membership ON user.id = membership.id_user
          JOIN 
            subscription ON membership.id_subscription = subscription.id
          WHERE 
            user.id = ?";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    
    echo "<div class='profile-container'>";
    echo "<h2>Profil de " . htmlspecialchars($user['firstname']) . " " . htmlspecialchars($user['lastname']) . "</h2>";
    echo "<p><strong>Email :</strong> " . htmlspecialchars($user['email']) . "</p>";
    echo "<p><strong>Abonnement actuel :</strong> " . htmlspecialchars($user['subscription_name']) . "</p>";

   
    echo "<h3>Modifier l'abonnement :</h3>";
    echo "<form action='profile.php?id=$user_id' method='POST'>
            <label for='subscription'>Choisir un abonnement :</label>
            <select name='subscription' id='subscription'>
                <option value='NULL'>Aucun abonnement</option>";

    
    $subscriptions = ["VIP", "GOLD", "Classic", "Pass Day"];
    foreach ($subscriptions as $subscription) {
        $selected = $user['subscription_name'] == $subscription ? 'selected' : '';
        echo "<option value='$subscription' $selected>$subscription</option>";
    }

    echo "</select>
          <input type='hidden' name='user_id' value='" . $user['id'] . "'>
          <button type='submit' name='update_subscription' class='update-btn'>Mettre à jour</button>
          </form>";
    echo "</div>";
} else {
    die("Utilisateur introuvable.");
}

if (isset($_POST['update_subscription'])) {
    $user_id = intval($_POST['user_id']);
    $subscription_name = $_POST['subscription'];

    
    if ($subscription_name !== 'NULL') {
        $query = "SELECT id FROM subscription WHERE name = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $subscription_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($subscription = mysqli_fetch_assoc($result)) {
            $subscription_id = $subscription['id'];
        } else {
            die("Abonnement invalide.");
        }
    } else {
        $subscription_id = NULL; 
    }

    $query = "UPDATE membership 
              SET id_subscription = ? 
              WHERE id_user = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $subscription_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
       
        header("Location: profile.php?id=$user_id");
        exit;
    } else {
        echo "<p>Erreur lors de la mise à jour de l'abonnement.</p>";
    }
}
?>
<link rel="stylesheet" type="text/css" href="style.css">


<div class="return-link">
    <a href="members.php" class="back-btn">Retour à la liste</a>

</div>



<style>
body {
    background-image: url("assets/group.jpg");
    background-size: cover;
    background-repeat: no-repeat;
}

.profile-container {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    margin: 20px auto;
    max-width: 600px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    font-family: 'Playfair Display', serif;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

p {
    font-size: 16px;
    margin: 10px 0;
}

label {
    font-weight: bold;
    font-size: 16px;
}

select,
button {
    font-size: 16px;
    padding: 10px;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
}

button.update-btn {
    background-color: #FFEC8B;
    color: white;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

button.update-btn:hover {
    background-color: #FFDD44;
}

.return-link {
    text-align: center;
    margin-top: 20px;
}

.back-btn {
    background-color: salmon;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    font-weight: bold;
}

.back-btn:hover {
    background-color: #FF6347;
}
</style>