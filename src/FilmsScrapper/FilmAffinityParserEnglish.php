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
        $pageDOM = $this->getPageDOM($html);

        $films = array();
        /** @var \QueryPath\DOMQuery $filmsDOM */
        $filmsDOM = $pageDOM->find('.adv-search-movie-container');

        if ($filmsDOM->length === 1 && $filmsDOM->find('b:contains(There are no results)')->length === 1)
        {
            return $films;
        }

        foreach ($filmsDOM as $filmDOM)
        {
            /** @var \QueryPath\DOMQuery $filmDOM */
            $title = $this->cleanText($filmDOM->find('.mc-title')->parent()->text());

            list($title, $year) = $this->parseTitleAndYear($title);
            if (empty($title) || empty($year))
            {
                continue;
            }

            $permalink = $filmDOM->find('.mc-title a')->attr('href');
            $thumbnail = $this->cleanImage($filmDOM->find('img[src*="-small"]')->attr('src'));
            $rating = $filmDOM->find('img[src*="imgs/ratings"]')->attr('src');
            $rating = $rating ? (preg_replace('#.*/([0-9]+)\.png#', '$1', $rating)) : false;
            $directors = $this->cleanArray($filmDOM->find('.director')->text(), 2);
            $actors = $this->cleanArray($filmDOM->find('.cast')->text(), 5);

            $film = new Film();
            $film->setTitle($title)->setYear($year)->setPermalink($this->domain . $permalink)
                ->setThumbnailUrl($thumbnail)->setRating($rating)
                ->setDirectors($directors)->setActors($actors);
            $films[] = $film;
        }

        return $films;
    }

} 