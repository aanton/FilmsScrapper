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

        $title = $this->cleanText($pageDOM->find('#main-title span')->text());
        if (empty($title))
        {
            return null;
        }

        $infoDOM = $pageDOM->find('.movie-info');
        $originalTitle = $this->cleanText($infoDOM->find('dt:contains(Original title)')->next()->text());
        $year = $this->cleanText($infoDOM->find('dt:contains(Year)')->next()->text());
        $duration = $this->parseDuration($infoDOM->find('dt:contains(Running time)')->next()->text());
        $synopsis = $this->cleanText($infoDOM->find('dt:contains(Synopsis / Plot)')->next()->text());
        $directors = $this->cleanArray($infoDOM->find('dt:contains(Director)')->next()->text(), 2);
        $actors = $this->cleanArray($infoDOM->find('dt:contains(Cast)')->next()->text(), 5);
        $genres = $this->parseGenres($infoDOM->find('dt:contains(Genre)')->next()->text());
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