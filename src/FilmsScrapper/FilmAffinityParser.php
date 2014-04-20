<?php

namespace FilmsScrapper;

abstract class FilmAffinityParser
{
    /** @var string */
    protected $domain;

    /** @var string */
    protected $language;

    /**
     * @param string $domain
     * @param string $language
     */
    function __construct($domain, $language)
    {
        $this->domain = $domain;
        $this->language = $language;
    }

    /**
     * @param string $html
     * @param string $url
     * @return Film|null
     * @throws \Exception
     */
    public abstract function parseGet($html, $url);

    /**
     * @param string $html
     * @return Film[]
     * @throws \Exception
     */
    public abstract function parseSearch($html);

    /**
     * @param string $html
     * @return \QueryPath\DOMQuery
     */
    protected function getPageDOM($html)
    {
        // disable standard libxml errors and enable user error handling
        libxml_use_internal_errors(true);

        $html = utf8_encode($html); // Convert HTML to UTF-8
        $pageDOM = htmlqp($html);
        return $pageDOM;
    }

    /**
     * @param string $text
     * @return array Array of two elements: title & year
     */
    protected function parseTitleAndYear($text)
    {
        $result = preg_match('#^(.*?)\s*\(([0-9]+)\)$#', $text, $matches);
        return $result ? array($matches[1], $matches[2]) : array($text, 0);
    }

    /**
     * @param string $text
     * @return int
     */
    protected function parseDuration($text)
    {
        $result = preg_replace('#^([0-9]+) .*$#', '$1', $this->cleanText($text));
        return intval($result);
    }

    /**
     * @param string $text
     * @param int $limit
     * @return array
     */
    protected function parseGenres($text, $limit = 3)
    {
        $text = preg_replace('#\|.*$#', '', $text); // iscard genres after | character
        return $this->cleanArray($text, $limit);
    }

    /**
     * @param string $text
     * @return string
     */
    protected function cleanText($text)
    {
        return trim($text);
    }

    /**
     * @param string $text
     * @return string|null
     */
    protected function cleanImage($text)
    {
        if (preg_match('#movies/noimg#', $text))
        {
            return null;
        }

        return $text;
    }

    /**
     * @param string $text
     * @param int $limit
     * @return array
     */
    protected function cleanArray($text, $limit = 10)
    {
        $text = $this->cleanText($text);
        if (empty($text))
        {
            return array();
        }

        $array = preg_split('#\s*[,\.]\s*#', $text);
        return array_slice($array, 0, $limit);
    }

}