<?php

namespace FilmsScrapper;

class FilmAffinityParserSpanish extends FilmAffinityParser
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

        $title = $this->cleanText($pageDOM->find('#main-title span')->text());
        if (empty($title))
        {
            return null;
        }

        $infoDOM = $pageDOM->find('.movie-info');
        $originalTitle = $this->cleanText($infoDOM->find('dt:contains(Título original)')->next()->text());
        $year = $this->cleanText($infoDOM->find('dt:contains(Año)')->next()->text());
        $duration = $this->parseDuration($infoDOM->find('dt:contains(Duración)')->next()->text());
        $synopsis = $this->cleanText($infoDOM->find('dt:contains(Sinopsis)')->next()->text());
        $directors = $this->cleanArray($infoDOM->find('dt:contains(Director)')->next()->text(), 2);
        $actors = $this->cleanArray($infoDOM->find('dt:contains(Reparto)')->next()->text(), 5);
        $genres = $this->parseGenres($infoDOM->find('dt:contains(Género)')->next()->text());
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

} 