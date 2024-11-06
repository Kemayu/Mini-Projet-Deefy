<?php

namespace iutnc\deefy\classes\repository;

use PDO;
use PDOException;

class DeefyRepository
{
    private static ?PDO $instance = null;
    private static array $config;

    public static function setConfig($file): void
    {
        self::$config = parse_ini_file($file);
    }
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            if (self::$config === null) {
                throw new \Exception("Config error : Fichier de configuration non trouvé");
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
    public function createPlaylist(int $userId, string $name): void
    {
        $stmt = self::getInstance()->prepare('INSERT INTO playlist (name, user_id) VALUES (:name, :user_id)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $playlistId = self::getInstance()->lastInsertId();

        $_SESSION['playlist_id'] = $playlistId;
    }


    public function saveTrack(string $title, string $artist, int $duration, ?string $genre, string $file_name): int
    {
        $query = "INSERT INTO track (title, artist, duration, genre, file_name, created_at) 
                  VALUES (:title, :artist, :duration, :genre, :file_name, NOW())";

        $stmt = self::getInstance()->prepare($query);


        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':artist', $artist);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':file_name', $file_name);

        $stmt->execute();
        return self::getInstance()->lastInsertId();
    }




    public function addTrackToPlaylist(int $playlistId, int $trackId): void
    {
        $query = "INSERT INTO playlist_track (playlist_id, track_id) VALUES (:playlist_id, :track_id)";
        $stmt = self::getInstance()->prepare($query);
        $stmt->bindParam(':playlist_id', $playlistId);
        $stmt->bindParam(':track_id', $trackId);
        $stmt->execute();
    }

    public function findPlaylistById(int $id): array
    {

        $stmt = self::getInstance()->prepare('SELECT * FROM playlist WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $playlist = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si la playlist n'existe pas, retourner un tableau vide
        if (!$playlist) {
            return [];
        }


        $stmt = self::getInstance()->prepare('
        SELECT track.id AS track_id, track.title, track.artist, track.duration, track.genre, track.file_name
        FROM track
        JOIN playlist_track ON track.id = playlist_track.track_id
        WHERE playlist_track.playlist_id = :id
    ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Ajouter les pistes récupérées à la playlist
        $playlist['tracks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $playlist;
    }


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

    public function getUserByEmail(string $email): ?array
    {
        $stmt = self::getInstance()->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

}
