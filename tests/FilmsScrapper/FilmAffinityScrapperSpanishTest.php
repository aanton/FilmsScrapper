<?php

use FilmsScrapper\Film;
use FilmsScrapper\FilmAffinityScrapper;

class FilmAffinityScrapperSpanishTest extends \PHPUnit_Framework_TestCase
{
    /** @var FilmAffinityScrapper */
    private $scrapper;

    public function setUp()
    {
        $this->scrapper = new FilmAffinityScrapper(FilmAffinityScrapper::LANGUAGE_SPANISH);
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidLanguage()
    {
        $scrapper = new FilmAffinityScrapper('invalid');
        $film = $scrapper->get('invalid-identifier');
        $this->assertNull($film);
    }

    public function testFailedGet()
    {
        $film = $this->scrapper->get('invalid-identifier');
        $this->assertNull($film);
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
            ->setSynopsis('Leonard es un investigador de una agencia de seguros cuya memoria está irreversiblemente dañada por culpa de un golpe en la cabeza, sufrido al intentar evitar el asesinato de su mujer: éste es el último hecho que recuerda del pasado. A causa del golpe, ha perdido la memoria reciente, es decir, los hechos cotidianos desaparecen de su mente en unos minutos. Así pues, para investigar y vengar el asesinato de su esposa tiene que recurrir a la ayuda de una cámara instantánea y a las notas tatuadas en su cuerpo. (FILMAFFINITY)')
            ->setPermalink('http://www.filmaffinity.com/es/film931317.html')
            ->setImageUrl('http://pics.filmaffinity.com/Memento-230609861-main.jpg')
            ->setRating(7.9)
            ->setDirectors(array('Christopher Nolan'))
            ->setActors(array('Guy Pearce', 'Carrie-Anne Moss', 'Joe Pantoliano', 'Mark Boone Junior', 'Stephen Tobolowsky'))
            ->setGenres(array('Thriller', 'Intriga'));
        $this->assertEquals($foundFilm, $film);
    }

    public function testFailedSearch()
    {
        $films = $this->scrapper->search('invalid-film-title');
        $this->assertEmpty($films);
    }

    public function testSearch()
    {
        $foundFilms = $this->scrapper->search('Criadas y Señoras');
        $this->assertNotEmpty($foundFilms);
        $this->assertEquals(1, count($foundFilms));

        $film = $this->getSearchTestFilm();
        $this->assertEquals($foundFilms[0], $film);
    }

    public function testSearchMultipleResults()
    {
        $foundFilms = $this->scrapper->search('Futbolín');
        $this->assertNotEmpty($foundFilms);
        $this->assertEquals(2, count($foundFilms));

        $film = new Film();
        $film->setTitle('Futbolín 2 (Metegol 2)')
            ->setYear(2016)
            ->setPermalink('http://www.filmaffinity.com/es/film177643.html')
            // ->setThumbnailUrl('') // no image
            ->setRating(0.0)
            ->setDirectors(array())
            ->setActors(array('Animation'));
        $this->assertEquals($foundFilms[0], $film);

        $film = new Film();
        $film->setTitle('Futbolín (Metegol)')
            ->setYear(2013)
            ->setPermalink('http://www.filmaffinity.com/es/film347421.html')
            ->setThumbnailUrl('http://pics.filmaffinity.com/Futbol_n_Metegol-347421-medium.jpg')
            ->setRating(6.0)
            ->setDirectors(array('Juan José Campanella'))
            ->setActors(array('Animation'));
        $this->assertEquals($foundFilms[1], $film);
    }

    public function testSearchByYear()
    {
        $foundFilms = $this->scrapper->search('Criadas y Señoras', array('year' => 2011));
        $this->assertNotEmpty($foundFilms);
        $this->assertEquals(1, count($foundFilms));

        $film = $this->getSearchTestFilm();
        $this->assertEquals($foundFilms[0], $film);
    }

    private function getSearchTestFilm()
    {
        $film = new Film();
        $film->setTitle('Criadas y señoras')
            ->setYear(2011)
            ->setPermalink('http://www.filmaffinity.com/es/film512560.html')
            ->setThumbnailUrl('http://pics.filmaffinity.com/Criadas_y_se_oras-512560-medium.jpg')
            ->setRating(7.0)
            ->setDirectors(array('Tate Taylor'))
            ->setActors(array('Emma Stone', 'Viola Davis', 'Bryce Dallas Howard', 'Sissy Spacek', 'Octavia Spencer'));
        return $film;
    }
}
