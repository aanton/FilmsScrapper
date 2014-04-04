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
        $films = $this->scrapper->search('invalid-identifier');
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
}
