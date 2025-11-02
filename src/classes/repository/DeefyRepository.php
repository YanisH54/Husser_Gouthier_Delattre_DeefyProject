<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;
use PDO;


class DeefyRepository
{
    private PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [ ];
    private function __construct(array $conf) {
        $this->pdo = new PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public static function getInstance() : DeefyRepository{
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $file) : void{
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }
        $dsn = "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']}";
        self::$config = [ 'dsn'=> $dsn,'user'=> $conf['username'],'pass'=> $conf['password']];
    }

    /**
     * Renvoit la liste des playlists d'un utilisateur donné
     * @param string $email
     * @return array
     * @throws InvalidPropertyValueException
     *
     */
    function getListPlaylists(string $email) : array {
        $requete = "SELECT id FROM user WHERE email = ?;";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$email);
        $statm->execute();
        $idUser = $statm->fetch()[0];

        $requete = "SELECT id_pl FROM user2playlist WHERE id_user = ?";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$idUser);
        $statm->execute();
        $liste = [];
        while ($donnee = $statm->fetch()){
            $playlist = $this->findPlaylistById($donnee[0]);
            $liste[] = $playlist;
        }
        return $liste;
    }

    /**
     * Ajoute une nouvelle playlist dans la base de données
     * @param Playlist $playlist
     * @return void
     * @throws InvalidPropertyNameException
     */
    function sauvegarderNouvellePlaylist(Playlist $playlist) : void {
        $var = $playlist->__GET("nom");
        $statm1 = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (?);");
        $statm1->bindParam(1, $var);
        $statm1->execute();
        $playlist->setId($this->pdo->lastInsertId());
    }

    /**
     * Ajoute une nouvelle track à la base de données
     * @param AudioTrack $track
     * @return void
     * @throws InvalidPropertyNameException
     */

    public function sauvegarderNouvellePiste(AudioTrack $track) : void{
        $statm = $this->pdo->prepare("INSERT INTO 'track' ('titre','filename','duree','genre',) VALUES (?, ?, ?, ?)");
        $titre = $track->__GET("titre");
        $nomFichier = $track->__GET("nomFichier");
        $duree = $track->__GET("duree");
        $genre = $track->__GET("genre");
        $auteur = $track->__GET("auteur");
        try {
            // Cas d'ajout d'une piste d'album
            $album = $track->__GET("album");
            $annee = $track->__GET("annee");
            $numPiste = $track->__GET("annee");
            $statm = $this->pdo->prepare("INSERT INTO track (titre,filename,duree,genre,artiste_album,titre_album,annee_album,numero_album,type) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?);");
            $statm->bindParam(5, $album);
            $statm->bindParam(6, $auteur);
            $statm->bindParam(7, $annee);
            $statm->bindParam(8, $numPiste);
            $type = "A";
            $statm->bindParam(9,$type);
        } catch (InvalidPropertyNameException){
            try {
                // Cas d'ajout d'un podcast
                $date = $track->__GET("date");
                $statm = $this->pdo->prepare("INSERT INTO track (titre,filename,duree,genre,auteur_podcast,date_posdcast,type) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $statm->bindParam(5,$auteur);
                $statm->bindParam(6,$date);
                $type = "P";
                $statm->bindParam(7,$type);

            } catch (InvalidPropertyNameException){}
        }
        $statm->bindParam(1,$titre);
        $statm->bindParam(2,$nomFichier);
        $statm->bindParam(3,$duree);
        $statm->bindParam(4,$genre);

        $statm->execute();
        $track->setId($this->pdo->lastInsertId());
    }

    /**
     * Créé un lien entre une piste et une playlist
     * @param int $idPlaylist
     * @param int $idAudioList
     * @return void
     */
    public function savePisteExistante(int $idPlaylist, int $idAudioList) : void{
        $requete = "SELECT count(*) FROM playlist2track WHERE id_pl = ?;";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$idPlaylist);
        $statm->execute();
        $n = $statm->fetch()[0] + 1;
        $requete = "INSERT INTO playlist2track (id_pl,id_track,no_piste_dans_liste) VALUES (?, ?, ?)";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$idPlaylist);
        $statm->bindParam(2,$idAudioList);
        $statm->bindParam(3, $n);
        $statm->execute();
    }

    /**
     * Créé un lien ente une playlist et un utilisateur
     * @param string $email
     * @param int $idPlaylist
     * @return void
     */
    public function saveUserPlaylist(string $email,int $idPlaylist) : void{
        $idUser = $this->getUserInfo($email)[2];
        $requete = "INSERT INTO user2playlist VALUES (?, ?)";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$idUser);
        $statm->bindParam(2,$idPlaylist);
        $statm->execute();
    }

    /**
     * Obtient les informations d'un utilisateur de la base de donnée
     * @param string $email
     * @return mixed
     */
    public function getUserInfo(string $email) : mixed
    {
        $requete = "SELECT passwd, role, id FROM user WHERE email = ?;";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1, $email);
        $statm->execute();
        return $statm->fetch();
    }

    /**
     * Verifie si une email est déja présente dans la base de donnée
     * @param string $email
     * @return bool
     */
    public function verifieEmailExiste(string $email) : bool {
        $requete = "SELECT count(*) FROM user WHERE email = ?;";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1, $email);
        $statm->execute();
        $nbr = $statm->fetch()[0];
        return ((int)$nbr !== 0);
    }

    /**
     * Renvoie la playlist associé à une ID
     * @param int $n
     * @return Playlist
     * @throws InvalidPropertyValueException
     */
    public function findPlaylistById(int $n) : Playlist{
        $requete = "SELECT nom FROM playlist WHERE id = ?";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$n);
        $statm->execute();
        $nom = $statm->fetch()[0];
        if (!$nom)
            throw new InvalidPropertyValueException("Erreur : Playlist inexistante");
        $playlist = new Playlist($nom,[],$n);

        $requete = "SELECT * FROM track 
                    INNER JOIN playlist2track 
                    ON playlist2track.id_track = track.id
                    WHERE playlist2track.id_pl = ?";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$n);
        $statm->execute();
        while ($trackData = $statm->fetch()){
            $track = null;
            if ($trackData[6]){
                $track = new AlbumTrack($trackData[1],
                    $trackData[4],
                    $trackData[6],
                    $trackData[3],
                    $trackData[7],
                    $trackData[9],
                    $trackData[2],
                    $trackData[8]);
            } else if ($trackData[10]){
                $track = new PodcastTrack(
                    $trackData[1],$trackData[4],$trackData[10],$trackData[3],$trackData[2],$trackData[11]
                );
            }
            $playlist->ajouterPiste($track);
        }
        return $playlist;
    }

    /**
     * Vérifie si une playlist appartient à un utilisateur
     * @param string $user
     * @param int $idplaylist
     * @return bool
     */
    public function checkPlaylistOwner(string $user, int $idplaylist) : bool {
        $requete = <<<END
            SELECT count(*) FROM user
            INNER JOIN user2playlist
            ON user.id = user2playlist.id_user
            WHERE user.email = ? AND user2playlist.id_pl = ?; 
            END;
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$user);
        $statm->bindParam(2,$idplaylist);
        $statm->execute();
        $n = $statm->fetch()[0];
        return ((int)$n === 1);
    }

    /**
     * Enregistre un nouvel utilisateur dans la base de données
     * @param string $email
     * @param string $passwd
     * @return void
     */
    public function registerNewUser(string $email,string $passwd) : void {
        $requete = "INSERT INTO user (email, passwd, role) values (?, ?, 1);";
        $statm = $this->pdo->prepare($requete);
        $statm->bindParam(1,$email);
        $statm->bindParam(2,$passwd);
        $statm->execute();
    }

}