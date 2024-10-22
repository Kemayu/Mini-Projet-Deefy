<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\audio\tracks\PodcastTrack;

class AddTrackAction extends Action
{
    public function execute(): string
    {
        if ($this->http_method === 'GET') {
            // Formulaire pour ajouter un podcast track
            $html = <<<END
                <form method="post" action="?action=add-track">
                    <label>Titre : <input type="text" name="titre"></label><br>
                    <label>Nom du fichier : <input type="text" name="nomDuFichier"></label><br>
                    <label>Auteur : <input type="text" name="auteur"></label><br>
                    <label>Date : <input type="text" name="date"></label><br>
                    <label>Genre : <input type="text" name="genre"></label><br>
                    <label>Durée (en secondes) : <input type="number" name="duree"></label><br>
                    <button type="submit">Ajouter</button>
                </form>
            END;
        } else {
            // Récupération des données
            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $nomDuFichier = filter_var($_POST['nomDuFichier'], FILTER_SANITIZE_SPECIAL_CHARS);
            $auteur = filter_var($_POST['auteur'], FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST['genre'], FILTER_SANITIZE_SPECIAL_CHARS);

            // Conversion de la durée en entier
            $duree = (int)$_POST['duree'];

            // Création de PodcastTrack
            $podcastTrack = new PodcastTrack($titre, $auteur, $genre, $nomDuFichier, $duree, $date);

            // Affichage
            $html = "<div>Podcast Track ajouté : {$podcastTrack->getTitre()}</div>";
        }

        return $html;
    }
}
