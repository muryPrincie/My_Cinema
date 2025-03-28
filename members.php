<?php
include 'dbconnect.php';  
include 'header.php';      


?>
<h2>Membres</h2>
<form method="GET">
    <input type="search" class="sun" name="z" placeholder="Rechercher un membre..." />
    <input type="submit" class="sun" value="Valider" />
</form>
<ul>
    <?php

$searchTerm = isset($_GET['z']) ? $_GET['z'] : ''; 

$sub = "SELECT user.id, user.firstname, user.lastname
        FROM user
        JOIN membership ON user.id = membership.id_user
        JOIN subscription ON membership.id_subscription = subscription.id
        WHERE user.firstname LIKE ? OR user.lastname LIKE ?";


$stmt = mysqli_prepare($connection, $sub);


$searchTermWithWildcards = '%' . $searchTerm . '%';
mysqli_stmt_bind_param($stmt, 'ss', $searchTermWithWildcards, $searchTermWithWildcards);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


while ($tab = mysqli_fetch_assoc($result)) {
    
    $imageUrl = "assets/members.png"; 

    echo "<li>
        <div class=\"iconp\">
            <img src=\"$imageUrl\" alt=\"people\">
            <a href=\"profile.php?id={$tab['id']}\">
                <button type=\"button\">" . htmlspecialchars($tab['firstname']) . " | " . htmlspecialchars($tab['lastname']) . "</button>
            </a>
        </div>
    </li>";
}
?>
</ul>