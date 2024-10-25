<?php

namespace iutnc\deefy\repository;

use PDO;
use iutnc\deefy\entity\Playlist;
use iutnc\deefy\entity\Track;

class DeefyRepository
{
    private static ?PDO $db = null;

    public static function setConfig(string $file): void
    {
        $config = parse_ini_file($file);
        self::$db = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
    }

    public static function getInstance(): ?PDO
    {
        return self::$db;
    }

    public function findPlaylistById(int $id): ?Playlist
    {
        $stmt = self::$db->prepare("SELECT * FROM playlist WHERE id = ?");
        $stmt->execute([$id]);
        $playlistData = $stmt->fetch();

        if ($playlistData) {
            $playlist = new Playlist($playlistData['id'], $playlistData['name'], $playlistData['user_id']);

            $stmtTracks = self::$db->prepare("SELECT * FROM track INNER JOIN playlist2track ON track.id = playlist2track.track_id WHERE playlist2track.playlist_id = ?");
            $stmtTracks->execute([$id]);

            while ($trackData = $stmtTracks->fetch()) {
                $track = new Track($trackData['id'], $trackData['title'], $trackData['artist']);
                $playlist->addTrack($track);
            }

            return $playlist;
        }

        return null;
    }
}
