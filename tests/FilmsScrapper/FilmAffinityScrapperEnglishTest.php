<?php

use FilmsScrapper\Film;
use FilmsScrapper\FilmAffinityScrapper;

class FilmAffinityScrapperEnglishTest extends \PHPUnit_Framework_TestCase
{
    /** @var FilmAffinityScrapper */
    private $scrapper;

    public function setUp()
    {
        $this->scrapper = new FilmAffinityScrapper(FilmAffinityScrapper::LANGUAGE_ENGLISH);
    }

    public function testGet()
    {
        $foundFilm = $this->scrapper->get(931317);
        $this->assertNotNull($foundFilm);

        $film = new Film();
        $film->setTitle('Memento')
            ->setOriginalTitle('Memento')
            ->setYear(2000)
            ->setDuration(115)
            ->setSynopsis('Leonard (Guy Pearce) is an insurance investigator whose memory has been damaged following a head injury he sustained after intervening on his wife\'s murder. His quality of life has been severely hampered after this event, and he can now only live a comprehendable life by tattooing notes on himself and taking pictures of things with a Polaroid camera. The movie is told in forward flashes of events that are to come that compensate for his unreliable memory, during which he has liaisons with various complex characters. Leonard badly wants revenge for his wife\'s murder, but, as numerous characters explain, there may be little point if he won\'t remember it in order to provide closure for him. The movie veers between these future occurrences and a telephone conversation Leonard is having in his motel room in which he compares his current state to that of a client whose claim he once dealt with.')
            ->setPermalink('http://www.filmaffinity.com/en/film931317.html')
            ->setImageUrl('http://pics.filmaffinity.com/Memento-230609861-main.jpg')
            ->setRating(7.9)
            ->setDirectors(array('Christopher Nolan'))
            ->setActors(array('Guy Pearce', 'Carrie-Anne Moss', 'Joe Pantoliano', 'Mark Boone Junior', 'Stephen Tobolowsky'))
            ->setGenres(array('Thriller', 'Mystery'));
        $this->assertEquals($foundFilm, $film);
    }

    public function testFailedSearch()
    {
        $films = $this->scrapper->search('invalid-film-title');
        $this->assertEmpty($films);
    }

    public function testSearch()
    {
        $foundFilms = $this->scrapper->search('La gran familia española');
        $this->assertNotEmpty($foundFilms);
        $this->assertEquals(1, count($foundFilms));

        $film = $this->getSearchTestFilm();
        $this->assertEquals($foundFilms[0], $film);
    }

    public function testSearchMultipleResults()
    {
        $foundFilms = $this->scrapper->search('Metegol');
        $this->assertNotEmpty($foundFilms);
        $this->assertEquals(2, count($foundFilms));

        $film = new Film();
        $film->setTitle('Metegol 2')
            ->setYear(2016)
            ->setPermalink('http://www.filmaffinity.com/en/film177643.html')
            // ->setThumbnailUrl('') // no image
            ->setRating(0.0)
            ->setDirectors(array())
            ->setActors(array('Animation'));
        $this->assertEquals($foundFilms[0], $film);

        $film = new Film();
        $film->setTitle('Underdogs')
            ->setYear(2013)
            ->setPermalink('http://www.filmaffinity.com/en/film347421.html')
            ->setThumbnailUrl('http://pics.filmaffinity.com/Underdogs-347421-medium.jpg')
            ->setRating(6.0)
            ->setDirectors(array('Juan José Campanella'))
            ->setActors(array('Animation'));
        $this->assertEquals($foundFilms[1], $film);
    }

    public function testSearchByYear()
    {
        $foundFilms = $this->scrapper->search('La gran familia española', array('year' => 2013));
        $this->assertNotEmpty($foundFilms);
        $this->assertEquals(1, count($foundFilms));

        $film = $this->getSearchTestFilm();
        $this->assertEquals($foundFilms[0], $film);
    }

    private function getSearchTestFilm()
    {
        $film = new Film();
        $film->setTitle('My Family and Other Hooligans (Family United)')
            ->setYear(2013)
            ->setPermalink('http://www.filmaffinity.com/en/film728142.html')
            ->setThumbnailUrl('http://pics.filmaffinity.com/My_Family_and_Other_Hooligans_Family_United-728142-medium.jpg')
            ->setRating(6.0)
            ->setDirectors(array('Daniel Sánchez Arévalo'))
            ->setActors(array('Quim Gutiérrez', 'Antonio de la Torre', 'Patrick Criado', 'Verónica Echegui', 'Roberto Álamo'));
        return $film;
    }
}
