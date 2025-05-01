<?php

namespace App\Utils;

class HtmlContentExtractor {
    public static function extractPlainText($rawEmail): string
    {
        $normalized = preg_replace('/\r\n|\r/', "\n", $rawEmail);

        if (preg_match('/boundary="(.*?)"/', $normalized, $matches)) {
            $boundary = $matches[1];
            $parts = explode("--".$boundary, $normalized);

            foreach ($parts as $part) {
                if (str_contains($part, 'Content-Type: text/plain')) {
                    $split = preg_split('/\n\n/', $part, 2);
                    $body = $split[1] ?? '';
                    return self::cleanText($body);
                }
            }
        }
        if (preg_match('/Content-Type: text\/html.*?\n\n(.*?)(\n--[^\n]+|$)/s', $normalized, $matches)) {
            $html = $matches[1];
            return self::cleanText($html);
        }
        $split = preg_split('/\n\n/', $normalized, 2);
        $body = $split[1] ?? $normalized;
        return self::cleanText($body);
    }

    private static function cleanText(string $text): string
    {
        $decoded = quoted_printable_decode($text);
        $plain = strip_tags($decoded);
        $plain = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $clean = preg_replace('/[^\x20-\x7E\x0A]/', '', $plain);
        $clean = preg_replace('/\n{3,}/', "\n\n", $clean);

        return trim($clean);
    }
}
