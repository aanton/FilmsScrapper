<?php

namespace FilmsScrapper;

class FilmAffinityScrapper
{
    const LANGUAGE_SPANISH = 'es';
    const LANGUAGE_ENGLISH = 'en';

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

            case self::LANGUAGE_ENGLISH:
                $this->parser = new FilmAffinityParserEnglish($this->domain, $language);
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
     * @param string $title
     * @param array $options (year)
     * @return Film[]
     * @throws \Exception
     */
    public function search($title, array $options = array())
    {
        $title = urlencode(utf8_decode($title)); // Convert search string to ISO-8859-1
        $path = 'advsearch.php?stext=' . $title . '&stype=title';
        if (array_key_exists('year', $options))
        {
            $year = $options['year'];
            $path .= '&fromyear=' . $year .'&toyear=' . $year;
        }
        $url = $this->generateUrl($path);

        $response = \Requests::get($url, array());
        if ($response->status_code !== 200) {
            return array();
        }

        return $this->parser->parseSearch($response->body);
    }

    protected function generateUrl($path)
    {
        return $this->domain . '/' . $this->language . '/' . $path;
    }
}