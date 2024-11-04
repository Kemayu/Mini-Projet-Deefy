<?php

namespace iutnc\deefy\classes\repository;

use PDO;
use PDOException;

class DeefyRepository
{
    private static ?PDO $instance = null;
    private static array $config;

    // Méthode pour charger le fichier de configuration
    public static function setConfig($file): void
    {
        self::$config = parse_ini_file($file);
    }
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            if (self::$config === null) {
                throw new \Exception("Configuration non définie. Veuillez appeler setConfig() avant d'utiliser cette classe.");
            }
            try {
                $dsn = 'mysql:host=' . self::$config['host'] . ';dbname=' . self::$config['dbname'];
                self::$instance = new PDO($dsn, self::$config['username'], self::$config['password']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public function findAllPlaylists(): array
    {
        $stmt = self::getInstance()->query('SELECT * FROM playlist');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findPlaylistsByUser(int $userId): array
    {
        $stmt = self::getInstance()->prepare('SELECT * FROM playlist WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findLastPlaylistByUser(int $userId): ?array
    {
        $stmt = self::getInstance()->prepare("SELECT * FROM playlist WHERE user_id = :userId ORDER BY created_at DESC LIMIT 1");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $playlist = $stmt->fetch(PDO::FETCH_ASSOC);

        return $playlist ?: null;
    }



    public function saveEmptyPlaylist(string $name, int $userId): int
    {
        $stmt = self::getInstance()->prepare('INSERT INTO playlist (name, user_id, created_at) VALUES (:name, :user_id, NOW())');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return self::getInstance()->lastInsertId();
    }

    public function createPlaylist(int $userId, string $name): void
    {
        // Préparez la requête pour insérer une nouvelle playlist
        $stmt = self::getInstance()->prepare('INSERT INTO playlist (name, user_id) VALUES (:name, :user_id)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':user_id', $userId); // Utilisez user_id pour lier l'utilisateur
        $stmt->execute();

        // Récupérer l'ID de la playlist nouvellement créée
        $playlistId = self::getInstance()->lastInsertId();

        // Stocker l'ID de la playlist dans la session
        $_SESSION['playlist_id'] = $playlistId;
    }



    // Méthode pour créer une instance PDO si elle n'existe pas encore



    // Enregistrer une nouvelle piste
    public function saveTrack(string $title, string $artist): int
    {
        $stmt = self::getInstance()->prepare('INSERT INTO track (title, artist, created_at) VALUES (:title, :artist, NOW())');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':artist', $artist);
        $stmt->execute();
        return self::getInstance()->lastInsertId();
    }

    // Ajouter une piste à une playlist
    public function addTrackToPlaylist(int $playlistId, int $trackId): void
    {
        $stmt = self::getInstance()->prepare('INSERT INTO playlist_track (playlist_id, track_id) VALUES (:playlist_id, :track_id)');
        $stmt->bindParam(':playlist_id', $playlistId);
        $stmt->bindParam(':track_id', $trackId);
        $stmt->execute();
    }

    // Récupérer une playlist et ses pistes associées par ID
    public function findPlaylistById(int $id): array
    {
        $stmt = self::getInstance()->prepare('SELECT * FROM playlist WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $playlist = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifiez si la playlist existe
        if (!$playlist) {
            return [];
        }

        // Récupérez les pistes associées à cette playlist
        $stmt = self::getInstance()->prepare('
        SELECT track.title, track.artist
        FROM track
        JOIN playlist_track ON track.id = playlist_track.track_id
        WHERE playlist_track.playlist_id = :id
    ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $playlist['tracks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $playlist;
    }


    // Créer un nouvel utilisateur
    public function createUser(string $email, string $hashedPassword): void
    {
        $stmt = self::getInstance()->prepare("INSERT INTO user (email, passwd, created_at) VALUES (:email, :passwd, NOW())");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passwd', $hashedPassword);
        $stmt->execute();
    }

    public function getUserIdByEmail(string $email): ?int
    {
        $stmt = self::getInstance()->prepare('SELECT id FROM user WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? (int)$user['id'] : null;
    }


}
