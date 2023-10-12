<?php
/*
 * Ce fichier fait partie du mock-ecole-directe-api.
 *
 * (c) redbull
 *
 * Pour les informations complètes sur les droits d'auteur et la licence,
 * veuillez consulter le fichier LICENSE qui a été distribué avec ce code source.
 */


namespace Core;

use Exception;

class TokenHandler
{
    /**
     * @throws Exception
     */
    public static function generate($length = 64): string
    {
        if($length < 8 || $length > 64) {
            return false;
        }
        return bin2hex(random_bytes($length));
    }
}
