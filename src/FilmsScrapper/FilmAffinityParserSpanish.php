<?php

namespace FilmsScrapper;

use QueryPath\DOMQuery;

class FilmAffinityParserSpanish extends FilmAffinityParser
{
    /**
     * @inheritdoc
     */
    protected function parseGetTitle(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Título original)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetYear(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Año)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetDuration(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Duración)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    protected function parseGetSynopsis(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Sinopsis)')->next()->text();
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
        return $dom->find('dt:contains(Reparto)')->next()->text();
    }

    /**
     * @inheritdoc
     */
    public function parseGetGenres(DOMQuery $dom)
    {
        return $dom->find('dt:contains(Género)')->next()->text();
    }

} 