<?php
namespace iutnc\deefy\repository;

use PDO;
use PDOException;

class DeefyRepository {
    private static ?PDO $instance = null;
    private static array $config;

    public static function setConfig($file): void {
        self::$config = parse_ini_file($file);
    }

    public static function getInstance(): PDO {
        if (self::$instance === null) {
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

    public function findAllPlaylists(): array {
        $stmt = self::$instance->query('SELECT * FROM playlist');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveEmptyPlaylist(string $name): int {
        $stmt = self::$instance->prepare('INSERT INTO playlist (name) VALUES (:name)');
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return self::$instance->lastInsertId();
    }

    public function saveTrack(string $title, string $artist): int {
        $stmt = self::$instance->prepare('INSERT INTO track (title, artist) VALUES (:title, :artist)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':artist', $artist);
        $stmt->execute();
        return self::$instance->lastInsertId();
    }

    public function addTrackToPlaylist(int $playlistId, int $trackId): void {
        $stmt = self::$instance->prepare('INSERT INTO playlist2track (playlist_id, track_id) VALUES (:playlist_id, :track_id)');
        $stmt->bindParam(':playlist_id', $playlistId);
        $stmt->bindParam(':track_id', $trackId);
        $stmt->execute();
    }
}
