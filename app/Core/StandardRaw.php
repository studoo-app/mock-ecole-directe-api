<?php

namespace Core;

/**
 * Class StandardRaw
 *
 * @author Benoit Foujols
 */
class StandardRaw
{
    /**
     * Normalize to Standard Raw Upper
     * Rules Raw -> Clean -> Upper -> Raw (Standard)
     * @param String $raw
     * @return String|null
     */
    public function normalizeSRString(string $raw, bool $space = false): ?string
    {
        return ($space === true) ? strtoupper($this->clean($this->cleanBeginToEndWhiteSpace($raw))) : strtoupper($this->clean($raw));
    }

    /**
     * Normalize to Standard Raw UCFirst
     * Rules Raw -> Clean -> UCFirst -> Raw (Standard)
     * @param String $raw
     * @return String|null
     */
    public function normalizeSRSUcfirst(string $raw, bool $space = false): ?string
    {
        return ($space === true) ? ucfirst(strtolower($this->clean($this->cleanBeginToEndWhiteSpace($raw)))) : ucfirst(strtolower($this->clean($raw)));
    }


    /**
     * Normalize to Standard Raw UTF8
     * Rules Raw -> Clean -> Raw (Standard)
     * @param String $raw Source à nettoyer
     * @param bool $space Supprimer les espaces avant et après la source
     * @param bool $nonbreaking Supprimer les
     * @return String|null
     */
    public function normalizeSRUtf8(string $raw, bool $space = false, bool $nonbreaking = false): ?string
    {
        return ($space === true) ? $this->clean($this->cleanBeginToEndWhiteSpace($raw), $nonbreaking) : $this->clean($raw, $nonbreaking);
    }

    /**
     * Clean String UTF8 to Normal
     * @param String $text
     * @return String|null
     */
    private function clean(string $text, bool $nonbreaking = false): ?string
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/[.]/' => '',
        );
        $utf8['/ /'] = ($nonbreaking === true) ? ' ' : '-'; // nonbreaking space (equiv. to 0x160)
        $utf8['/-/'] = ($nonbreaking === true) ? ' ' : '-';
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    /**
     * Clean whitespace Begin To End
     * @param String $raw
     * @return String|null
     */
    private function cleanBeginToEndWhiteSpace(string $raw): ?string
    {
        return rtrim(ltrim($raw));
    }

}
