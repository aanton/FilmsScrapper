<?php

namespace FilmsScrapper;

use QueryPath\DOMQuery;

class FilmAffinityParserEnglish extends FilmAffinityParser
{

    /**
     * @inheritdoc
     */
    protected function parseGetTitle(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Original title)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetYear(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Year)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetDuration(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Running time)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetSynopsis(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Synopsis / Plot)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetDirectors(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Director)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetActors(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Cast)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    public function parseGetGenres(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Genre)')->next()->text();
    }

} 