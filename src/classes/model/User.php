<?php

namespace iutnc\deefy\classes\model;

use iutnc\deefy\classes\repository\DeefyRepository;

class User
{
    private int $id;
    private string $email;
    private string $createdAt;
    private string $role;

    // Constructeur pour initialiser un utilisateur
    public function __construct(int $id, string $email, string $createdAt, string $role = 'STANDARD')
    {
        $this->id = $id;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->role = $role;
    }

    // Getters pour les propriétés privées
    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    // Verif si l'utilisateur est un administrateur
    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    // Méthode statique pour recuperer un utilisateur par email
    public static function loadByEmail(string $email): ?User
    {
        $repo = new DeefyRepository();
        $userData = $repo->getUserByEmail($email);

        if ($userData) {
            return new User($userData['id'], $userData['email'], $userData['created_at'], $userData['role']);
        }

        return null;
    }

    // Methode statique pour récupérer un utilisateur par ID
    public static function loadById(int $id): ?User
    {
        $repo = new DeefyRepository();
        $userData = $repo->getUserById($id);

        if ($userData) {
            return new User($userData['id'], $userData['email'], $userData['created_at'], $userData['role']);
        }

        return null;
    }
}
