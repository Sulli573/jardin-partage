<?php 

namespace App\Enum;

class UserRoleEnum {
    public const ROLE_USER = "Utilisateur";
    public const ROLE_ADMIN = "Administrateur";

    #fonction pour récupérer tous les roles 'traduit'
    public function getRoles() {
        return [
           "ROLE_ADMIN"=> self::ROLE_ADMIN,
            "ROLE_USER"=>self::ROLE_USER
        ];
    }
}
