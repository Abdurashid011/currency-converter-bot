<?php

declare(strict_types=1);

class Currency
{
    const CB_URL = "https://cbu.uz/uz/arkhiv-kursov-valyut/json/";

    public function exchange(float $amount, string $from_currency, string $to_currency): ?float
    {
        // Valyuta kurslarini olish
        $content = file_get_contents(self::CB_URL);
        if ($content === false) {
            return null;
        }

        $rates = json_decode($content, true);
        if ($rates === null) {
            return null;
        }

        // UZS kurslarini aniqlash
        $from_rate = ($from_currency === 'UZS') ? 1 : null;
        $to_rate = ($to_currency === 'UZS') ? 1 : null;

        // Kurslarni topish
        foreach ($rates as $rate) {
            if ($rate['Ccy'] === $from_currency) {
                $from_rate = floatval($rate['Rate']);
            }
            if ($rate['Ccy'] === $to_currency) {
                $to_rate = floatval($rate['Rate']);
            }
            if ($from_rate !== null && $to_rate !== null) {
                break;
            }
        }

        // Agar ikkala kurs ham mavjud bo'lsa, konvertatsiya qilish
        if ($from_rate !== null && $to_rate !== null) {
            if ($from_currency === 'UZS') {
                return round($amount / $to_rate, 2);
            }
            if ($to_currency === 'UZS') {
                return round($amount * $from_rate, 2);
            }
            return round(($amount * $from_rate) / $to_rate, 2);
        }

        return null;
    }
}
