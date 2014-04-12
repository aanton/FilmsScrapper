<?php

namespace FilmsScrapper;

class FilmAffinityScrapper
{
    const LANGUAGE_SPANISH = 'es';

    /** @var string */
    protected $domain = 'http://www.filmaffinity.com';

    /** @var string */
    protected $language;

    /** @var FilmAffinityParser */
    protected $parser;

    /**
     * @param string $language
     * @throws \Exception
     */
    public function __construct($language)
    {
        switch ($language)
        {
            case self::LANGUAGE_SPANISH:
                $this->parser = new FilmAffinityParserSpanish($this->domain, $language);
                break;

            default:
                throw new \Exception('Only ES language is currently supported');
        }

        $this->language = $language;
    }

    /**
     * @param mixed $id
     * @return Film|null
     * @throws \Exception
     */
    public function get($id)
    {
        $url = $this->generateUrl('film' . $id . '.html');
        $response = \Requests::get($url, array());

        if ($response->status_code !== 200)
        {
            return null;
        }

        return $this->parser->parseGet($response->body, $url);
    }

    /**
     * @param string $text
     * @return Film[]
     * @throws \Exception
     */
    public function search($text)
    {
        $text = urlencode(utf8_decode($text)); // Convert search string to ISO-8859-1
        $url = $this->generateUrl('advsearch.php?stext=' . $text . '&stype=title');

        $response = \Requests::get($url, array());
        if ($response->status_code !== 200) {
            return array();
        }

        return $this->parser->parseSearch($response->body);
    }

    protected function generateUrl($url)
    {
        return $this->domain . '/' . $this->language . '/' . $url;
    }
}