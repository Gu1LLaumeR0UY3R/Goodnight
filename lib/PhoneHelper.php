<?php

/**
 * Normalisation des numéros de téléphone vers le format E.164 (+33612345678).
 * Sert de filet de sécurité côté serveur : le navigateur envoie déjà un numéro E.164,
 * mais JavaScript peut être désactivé et les formulaires peuvent être appelés directement.
 */
class PhoneHelper
{
    /** Indique si une valeur a été saisie (espaces ignorés). */
    public static function isProvided($raw): bool
    {
        return trim((string)$raw) !== '';
    }

    /**
     * Convertit un numéro saisi en E.164.
     * Accepte : 06 12 34 56 78, 0612345678, +33 6 12 34 56 78, 0033612345678, +33 (0)6 12 34 56 78.
     * Sans indicatif, le numéro est considéré comme français.
     * Renvoie null si la valeur est vide ou invalide.
     */
    public static function normalize($raw): ?string
    {
        $s = preg_replace('/[\s.\-()]/', '', (string)$raw);
        if ($s === '' || $s === null) {
            return null;
        }

        if (strpos($s, '00') === 0) {
            $s = '+' . substr($s, 2);
        }

        if ($s[0] !== '+') {
            if (!preg_match('/^0([1-9]\d{8})$/', $s, $m)) {
                return null;
            }
            $s = '+33' . $m[1];
        }

        // zéro de ligne saisi à tort après l'indicatif : +330612345678
        if (preg_match('/^\+330([1-9]\d{8})$/', $s, $m)) {
            $s = '+33' . $m[1];
        }

        if (!preg_match('/^\+[1-9]\d{7,14}$/', $s)) {
            return null;
        }
        if (strpos($s, '+33') === 0 && !preg_match('/^\+33[1-9]\d{8}$/', $s)) {
            return null;
        }
        return $s;
    }

    /** Mise en forme pour l'affichage : +33 6 12 34 56 78 (autres pays : inchangé). */
    public static function format($e164): string
    {
        $e164 = (string)$e164;
        if (preg_match('/^\+33([1-9])(\d{2})(\d{2})(\d{2})(\d{2})$/', $e164, $m)) {
            return "+33 $m[1] $m[2] $m[3] $m[4] $m[5]";
        }
        return $e164;
    }
}
