<?php
include 'dbconnect.php';
include 'header.php';
?>
<meta charset="UTF-8">
<title>My_Cinema</title>
<h2>FILMS</h2>
<img src="assets/eeven.jpg" class="eeven" alt="eeven">
<br>

<form method="GET">

    <input type="search" class="sun" name="z" placeholder="Rechercher un film..." />
    <select name="distrib" class="sun">
        <option value="">Distributeur</option>
        <?php
        $movie = "SELECT name FROM distributor";

        $result = mysqli_query($connection, $movie);
        while ($tab = mysqli_fetch_assoc($result)){
            printf("<option value=\"{$tab['name']}\"class =\"sun\"> {$tab['name']}  </option>");
        }
        ?>
    </select>
    <select name="genre" class="sun">
        <option value="">Genre</option>
        <?php
        $movie = "SELECT name FROM genre";

        $result = mysqli_query($connection, $movie);
        while ($tab = mysqli_fetch_assoc($result)){
            printf("<option value=\"{$tab['name']}\"> {$tab['name']} </option>");
        }
        ?>
    </select>
    <input type="submit" class="sun" value="Valider" />
</form>
<ul id="cards">
    <?php

$movie ="select movie.id, movie.title from genre join movie_genre on genre.id = movie_genre.id_genre join movie on movie_genre.id_movie = movie.id join distributor on movie.id_distributor = distributor.id  where distributor.name like '{$_GET["distrib"]}%' and genre.name like '{$_GET["genre"]}%' AND title LIKE '%{$_GET["z"]}%';";

$result = mysqli_query($connection, $movie);
while ($tab = mysqli_fetch_assoc($result)){
            printf("<li><div class=\"card-film\"><img src=\"assets\camera.png\" alt=\"card-film\"><label>{$tab['title']}</label></div></li>");
 
}
?>
</ul>