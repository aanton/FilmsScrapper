<?php

namespace FilmsScrapper;

use QueryPath\DOMQuery;

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
    public function parseGet($html, $url)
    {
        $pageDOM = $this->getPageDOM($html);

        $title = $this->cleanText($pageDOM->find('#main-title span')->text());
        if (empty($title))
        {
            return null;
        }

        $infoDOM = $pageDOM->find('.movie-info');
        $originalTitle = $this->cleanText($this->parseGetTitle($infoDOM));
        $year = $this->cleanText($this->parseGetYear($infoDOM));
        $duration = $this->parseDuration($this->parseGetDuration($infoDOM));
        $synopsis = $this->cleanText($this->parseGetSynopsis($infoDOM));
        $directors = $this->cleanArray($this->parseGetDirectors($infoDOM), 2);
        $actors = $this->cleanArray($this->parseGetActors($infoDOM), 5);
        $genres = $this->parseGenres($this->parseGetGenres($infoDOM));
        $image = $this->cleanImage($pageDOM->find('#movie-main-image-container img')->attr('src'));
        $rating = $pageDOM->find('#movie-rat-avg')->text();
        $rating = str_replace(',', '.', $rating);

        $film = new Film();
        $film->setTitle($title)->setOriginalTitle($originalTitle)
            ->setYear($year)
            ->setDuration($duration)
            ->setSynopsis($synopsis)
            ->setPermalink($url)
            ->setImageUrl($image)->setRating($rating)
            ->setDirectors($directors)->setActors($actors)
            ->setGenres($genres);

        return $film;
    }

    /**
     * @param DOMQuery $dom
     * @return string
     */
    protected abstract function parseGetTitle(DOMQuery $dom);

    /**
     * @param DOMQuery $dom
     * @return string
     */
    protected abstract function parseGetYear(DOMQuery $dom);

    /**
     * @param DOMQuery $dom
     * @return string
     */
    protected abstract function parseGetDuration(DOMQuery $dom);

    /**
     * @param DOMQuery $dom
     * @return string
     */
    protected abstract function parseGetSynopsis(DOMQuery $dom);

    /**
     * @param DOMQuery $dom
     * @return string
     */
    protected abstract function parseGetDirectors(DOMQuery $dom);

    /**
     * @param DOMQuery $dom
     * @return string
     */
    protected abstract function parseGetActors(DOMQuery $dom);

    /**
     * @param DOMQuery $dom
     * @return string
     */
    public abstract function parseGetGenres(DOMQuery $dom);

    /**
     * @param string $html
     * @return Film[]
     * @throws \Exception
     */
    public function parseSearch($html)
    {
        $pageDOM = $this->getPageDOM($html);

        $films = array();
        /** @var DOMQuery $filmsDOM */
        $filmsDOM = $pageDOM->find('.movie-card');
        foreach ($filmsDOM as $filmDOM)
        {
            /** @var DOMQuery $filmDOM */
            $title = $this->cleanText($filmDOM->find('.mc-title')->text());

            list($title, $year) = $this->parseTitleAndYear($title);
            if (empty($title) || empty($year))
            {
                continue;
            }

            $permalink = $filmDOM->find('.mc-poster a')->attr('href');
            $thumbnail = $this->cleanImage($filmDOM->find('.mc-poster img')->attr('src'));
            $rating = $filmDOM->find('.mr-rating > img')->attr('src');
            $rating = $rating ? (preg_replace('#.*/([0-9]+)\.png#', '$1', $rating)) : false;
            $directors = $this->cleanArray($filmDOM->find('.mc-director')->text(), 2);
            $actors = $this->cleanArray($filmDOM->find('.mc-cast')->text(), 5);

            $film = new Film();
            $film->setTitle($title)->setYear($year)->setPermalink($this->domain . $permalink)
                ->setThumbnailUrl($thumbnail)->setRating($rating)
                ->setDirectors($directors)->setActors($actors);
            $films[] = $film;
        }

        return $films;
    }

    /**
     * @param string $html
     * @return DOMQuery
     */
    protected function getPageDOM($html)
    {
        // disable standard libxml errors and enable user error handling
        libxml_use_internal_errors(true);

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
        $text = preg_replace('#\|.*$#', '', $text); // Discard genres after | character
        $text = preg_replace('#\.#', ',', $text);
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

        $array = preg_split('#\s*,\s*#', $text);
        return array_slice($array, 0, $limit);
    }

}