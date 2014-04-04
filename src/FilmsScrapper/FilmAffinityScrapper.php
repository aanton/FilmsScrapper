<?php

namespace FilmsScrapper;

class FilmAffinityScrapper
{
    protected $domain = 'http://www.filmaffinity.com';

    protected $baseUrl;

    /**
     * @param string $language
     */
    public function __construct($language = 'es')
    {
        $this->baseUrl = $this->domain . '/' . $language . '/';
    }

    /**
     * @param string $text
     * @return Film[]
     * @throws \Exception
     */
    public function search($text)
    {
        $text = urlencode(utf8_decode($text)); // Convert search string to ISO-8859-1
        $url = $this->baseUrl . 'advsearch.php?stext=' . $text . '&stype=title';

        $response = \Requests::get($url, array());
        if ($response->status_code !== 200) {
            return array();
        }

        $html = utf8_encode($response->body); // Convert HTML to UTF-8

        /** @var \QueryPath\DOMQuery $pageDOM */
        $pageDOM = htmlqp($html);

        $films = array();
        /** @var \QueryPath\DOMQuery $filmsDOM */
        $filmsDOM = $pageDOM->find('.movie-card');
        foreach ($filmsDOM as $filmDOM)
        {
            /** @var \QueryPath\DOMQuery $filmDOM */
            $title = $this->cleanText($filmDOM->find('.mc-title')->text());

            list($title, $year) = $this->parseTitleAndYear($title);
            if (empty($title) || empty($year))
            {
                continue;
            }

            $permalink = $filmDOM->find('.mc-poster a')->attr('href');
            $thumbnail = $filmDOM->find('.mc-poster img')->attr('src');
            $rating = $filmDOM->find('.mc-info-container > img')->attr('src');
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
     * @param mixed $id
     * @return Film|null
     * @throws \Exception
     */
    public function get($id)
    {
        $url = $this->baseUrl . 'film' . $id . '.html';
        $response = \Requests::get($url, array());

        if ($response->status_code !== 200)
        {
            return null;
        }

        $html = $response->body;
        /** @var \QueryPath\DOMQuery $pageDOM */
        $pageDOM = htmlqp($html);

        $title = $this->cleanText($pageDOM->find('#main-title')->text());
        if (empty($title))
        {
            return null;
        }

        $film = new Film();
        $film->setTitle($title);

        return $film;
    }

    private function parseTitleAndYear($text)
    {
        $result = preg_match('#^(.*?)\s*\(([0-9]+)\)$#', $text, $matches);
        return $result ? array($matches[1], $matches[2]) : array($text, 0);
    }

    private function cleanText($text)
    {
        return trim($text);
    }

    private function cleanArray($text, $limit = PHP_INT_MAX)
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