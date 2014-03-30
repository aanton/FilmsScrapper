<?php

namespace FilmsScrapper;

class FilmAffinityScrapper
{
    protected $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl = 'http://www.filmaffinity.com/es/')
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $text
     * @return Film[]
     */
    public function search($text)
    {
        $text = urlencode(utf8_decode($text)); // ISO-8859-1
        $url = $this->baseUrl . 'search.php?stext=' . $text . '&stype=title';
        $response = \Requests::get($url, array());

        if ($response->status_code !== 200) {
            return array();
        }

        $html = $response->body;
        /** @var \QueryPath\DOMQuery $pageDOM */
        $pageDOM = htmlqp($html);

        $films = array();
        /** @var \QueryPath\DOMQuery $filmsDOM */
        $filmsDOM = $pageDOM->find('.movie-card');
        foreach ($filmsDOM as $filmDOM)
        {
            /** @var \QueryPath\DOMQuery $filmDOM */
            $title = $this->cleanText($filmDOM->find('.mc-title')->text());
            // $thumbnail = $filmDOM->find('.mc-poster img')->attr('src');
            // $directors = $this->cleanText($filmDOM->find('.mc-director')->text());
            // $actors = $this->cleanText($filmDOM->find('.mc-cast')->text());
            $rating = $filmDOM->find('.mc-info-container > img')->attr('src');
            $rating = $rating ? (preg_replace('#.*/([0-9]+)\.png#', '$1', $rating)) : false;

            $film = new Film();
            $film->setTitle($title);
            $film->setRating($rating);

            $films[] = $film;
        }

        return $films;
    }

    /**
     * @param mixed $id
     * @return Film|null
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
        if (!$title)
        {
            return null;
        }

        $film = new Film();
        $film->setTitle($title);

        return $film;
    }

    private function cleanText($text)
    {
        return trim(utf8_encode($text));
    }
}