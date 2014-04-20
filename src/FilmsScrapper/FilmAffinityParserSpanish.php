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

        $title = $this->cleanText($pageDOM->find('#main-title')->text());
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
            $thumbnail = $this->cleanImage($filmDOM->find('.mc-poster img')->attr('src'));
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

} 