<?php

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
        $films = $this->scrapper->search('futbolín');
        $this->assertNotEmpty($films);
        $this->assertEquals(2, count($films));
    }
}