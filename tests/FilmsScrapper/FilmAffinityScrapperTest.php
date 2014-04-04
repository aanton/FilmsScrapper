<?php

use FilmsScrapper\Film;
use FilmsScrapper\FilmAffinityScrapper;

class FilmAffinityScrapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var FilmAffinityScrapper */
    protected $scrapper;

    public function setUp()
    {
        $this->scrapper = new FilmAffinityScrapper();
    }

    public function testFailedGet()
    {
        $film = $this->scrapper->get('invalid-identifier');
        $this->assertNull($film);
    }

    public function testGet()
    {
        $film = $this->scrapper->get(931317);
        $this->assertNotNull($film);
        $this->assertEquals('Memento', $film->getTitle());
    }

    public function testFailedSearch()
    {
        $films = $this->scrapper->search('invalid-film-title');
        $this->assertEmpty($films);
    }

    public function testSearch()
    {
        $films = $this->scrapper->search('Criadas y Señoras');
        $this->assertNotEmpty($films);
        $this->assertEquals(1, count($films));

        $film = new Film();
        $film->setTitle('Criadas y señoras')
            ->setYear(2011)
            ->setPermalink('http://www.filmaffinity.com/es/film512560.html')
            ->setThumbnailUrl('http://pics.filmaffinity.com/Criadas_y_senoras-512560-small.jpg')
            ->setRating(8.0)
            ->setDirectors(array('Tate Taylor'))
            ->setActors(array('Emma Stone', 'Viola Davis', 'Bryce Dallas Howard', 'Sissy Spacek', 'Octavia Spencer'));
        $this->assertEquals($films[0], $film);
    }

    public function testSearchMultipleResults()
    {
        $films = $this->scrapper->search('Futbolín');
        $this->assertNotEmpty($films);
        $this->assertEquals(2, count($films));

        $film = new Film();
        $film->setTitle('Futbolín 2 (Metegol 2)')
            ->setYear(2016)
            ->setPermalink('http://www.filmaffinity.com/es/film177643.html')
            ->setThumbnailUrl('http://www.filmaffinity.com/imgs/movies/noimg50x72.jpg')
            ->setRating(0.0)
            ->setDirectors(array())
            ->setActors(array('Animation'));
        $this->assertEquals($films[0], $film);

        $film = new Film();
        $film->setTitle('Futbolín (Metegol)')
            ->setYear(2013)
            ->setPermalink('http://www.filmaffinity.com/es/film347421.html')
            ->setThumbnailUrl('http://pics.filmaffinity.com/Futbolin_Metegol-347421-small.jpg')
            ->setRating(6.0)
            ->setDirectors(array('Juan José Campanella'))
            ->setActors(array('Animation'));
        $this->assertEquals($films[1], $film);
    }
}
