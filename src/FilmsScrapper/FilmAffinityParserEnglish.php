<?php

namespace FilmsScrapper;

class FilmAffinityParserEnglish extends FilmAffinityParser
{
    /**
     * @param string $html
     * @param string $url
     * @return Film|null
     * @throws \Exception
     */
    public function parseGet($html, $url)
    {
        $pageDOM = $this->getPageDOM($html);

        $title = $this->cleanText($pageDOM->find('img[src*="images/movie.gif"]')->parent()->text());
        if (empty($title))
        {
            return null;
        }

        $originalTitle = $this->cleanText($pageDOM->find('b:contains(ORIGINAL TITLE)')->parent()->next()->text());
        $year = $this->cleanText($pageDOM->find('b:contains(YEAR)')->parent()->next()->text());
        $synopsis = $this->cleanText($pageDOM->find('b:contains(SYNOPSIS/PLOT)')->parent()->next()->text());
        $directors = $this->cleanArray($pageDOM->find('b:contains(DIRECTOR)')->parent()->next()->text(), 2);
        $actors = $this->cleanArray($pageDOM->find('b:contains(CAST)')->parent()->next()->text(), 5);
        $image = $this->cleanImage($pageDOM->find('.lightbox img')->attr('src'));
        $rating = $pageDOM->find('img[src*="imgs/ratings"]')->parent()->parent()->prev()->text();
        $rating = str_replace(',', '.', $rating);

        $film = new Film();
        $film->setTitle($title)->setOriginalTitle($originalTitle)->setYear($year)
            ->setSynopsis($synopsis)
            ->setPermalink($url)
            ->setImageUrl($image)->setRating($rating)
            ->setDirectors($directors)->setActors($actors);

        return $film;
    }

    /**
     * @param string $html
     * @return Film[]
     * @throws \Exception
     */
    public function parseSearch($html)
    {
        return array();
    }

} 