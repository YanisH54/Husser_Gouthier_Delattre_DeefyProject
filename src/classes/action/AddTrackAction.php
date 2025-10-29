<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\render\PodcastTrackRenderer;
use iutnc\deefy\repository\DeefyRepository;


class AddTrackAction extends ActionConnecte {

    public function execute() : string{
        if (! isset ($_SESSION['playlist'])){
            $html = "<b>Aucune playlist en session</b>";
        } else {

            if ($this->http_method === "GET"){


            } else if ($this->http_method === "POST"){

                $nom = filter_var($_POST['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
                $chemin = filter_var($_POST['chemin'], FILTER_SANITIZE_URL);
                $artiste = filter_var($_POST['artiste'], FILTER_SANITIZE_SPECIAL_CHARS);
                $num = filter_var($_POST['num'], FILTER_SANITIZE_NUMBER_INT);
                $p = new PodcastTrack($nom, []);
                $_SESSION['playlist'] = serialize($p);
                $html = "<br><b>Podcast ajouté à la playlist</b>";
                $renderer = new PodcastTrackRenderer($p);
                $html .= $renderer->render(1);
                $html .='<a href="?action=add-track">Ajouter encore une piste</a>';
                $filter = filter_var($nom, FILTER_SANITIZE_SPECIAL_CHARS);

            }
            $playlist = unserialize($_SESSION(['playlist']));
            $playlist->ajouterPiste(new AudioTrack("Piste 1","nom fichier","artiste",4));
            $_SESSION['playlist'] = serialize($playlist);
            $html = "<br><b>Track ajoutée à la playlist</b>";
        }
        return $html;
    }

    public function GET(): string
    {
        if (!isset($_SESSION['playlist']))
            $html = "<b>Aucune playlist en session</b>";
        else {
            $playlist = unserialize($_SESSION['playlist']);
            if (Authz::checkPlaylistCurrOwner($playlist->__GET("id"))) {
                $html = <<<END
                    <form method='POST' action='?action=add-track'><br>
                    <input type='text' name='titre' value="Nom de la piste"><br>
                    <input type='text' name='filename' value='Chemin du fichier'><br>
                    <input type='text' name='artiste' value='Nom artiste'><br>
                    <input type='text' name='numero_album' value='Numero Track'><br>
                    <input type='text' name='duree'><br>
                    <input type='text' name='genre'><br>
                    <input type='text' name='titre_album'><br>
                    <input type='text' name='annee'><br>
                    <input type='text' name='date_podcast'><br>
                    <input type="radio" id="ALBUM" name="type" value="Album">
                    <input type="radio" id="PODCAST" name="type" value="Podcast">
                    <button type='submit' name='bouton'>Valider</button>
                END;
            } else {
                $html = "<b>Accès à la playlist courante refusé</b>";
            }
        }
        return $html;
    }

    public function POST(): string
    {

        if (!isset($_SESSION['playlist']))
            $html = "<b>Aucune playlist en session</b>";
        else {
            $playlist = unserialize($_SESSION['playlist']);
            if (Authz::checkPlaylistCurrOwner($playlist->__GET("id"))) {


                $pdo = DeefyRepository::getInstance();

                $titre = $_POST["titre"];
                $filename = $_POST["filename"];
                $artiste = $_POST["artiste"];
                $numero_album = $_POST["numero_album"];
                $duree = $_POST["duree"];
                $genre = $_POST["genre"];
                $titre_album = $_POST["titre_album"];
                $type = $_POST["type"];
                $annee = $_POST["annee"];
                $date_podcast = $_POST["date_podcast"];

                if (!(filter_var($titre, FILTER_SANITIZE_SPECIAL_CHARS) &&
                    filter_var($filename, FILTER_SANITIZE_URL) &&
                    filter_var($artiste, FILTER_SANITIZE_SPECIAL_CHARS) &&
                    filter_var($duree, FILTER_SANITIZE_NUMBER_INT) &&
                    filter_var($genre, FILTER_SANITIZE_SPECIAL_CHARS))) {
                    $html = "<b>Erreur de saisie, veuillez réessayer</b>";
                } else {
                    if (isset($type) && $type === AudioTrack::$ALBUM) {
                        if (isset($titre_album) && isset($numero_album) && isset($annee)) {
                            if (!(filter_var($numero_album, FILTER_SANITIZE_NUMBER_INT) &&
                                filter_var($titre_album, FILTER_SANITIZE_SPECIAL_CHARS) &&
                                filter_var($date_podcast, FILTER_SANITIZE_SPECIAL_CHARS))) {
                                $html = "<b>Erreur de saisie, veuillez réessayer</b>";
                            } else {
                                $albumtrack = new AlbumTrack($titre, $filename, $artiste, $duree, $titre_album, $numero_album, $genre, $annee);
                                $pdo->sauvegarderNouvellePiste($albumtrack);
                                $pdo->savePisteExistante($playlist->__GET("id"), $albumtrack->__GET("id"));
                                $playlist->ajouterPiste($albumtrack);
                                $_SESSION['playlist'] = serialize($playlist);
                                $html = "<b>AlbumTrack ajouté à la playlist</b>";
                            }
                        } else
                            $html = "<b>Erreur de saisie, veuillez réessayer</b>";
                    } else {
                        if (isset($date_podcast)) {
                            if (!filter_var($date_podcast, FILTER_SANITIZE_SPECIAL_CHARS)) {
                                $html = "<b>Erreur de saisie, veuillez réessayer</b>";
                            } else {
                                $podcasttrack = new PodcastTrack($titre, $filename, $artiste, $duree, $genre, $date_podcast);
                                $pdo->sauvegarderNouvellePiste($podcasttrack);
                                $pdo->savePisteExistante($playlist->__GET("id"), $podcasttrack->__GET("id"));
                                $playlist->ajouterPiste($podcasttrack);
                                $_SESSION['playlist'] = serialize($playlist);

                                $html = "<b>PodcastTrack ajouté à la playlist</b>";

                            }
                        } else
                            $html = "<b>Erreur de saisie, veuillez réessayer</b>";
                    }
                }
            } else
                $html = "<b>Accès à la playlist courante non autorisé</p>";
        }
        return $html;
    }
}