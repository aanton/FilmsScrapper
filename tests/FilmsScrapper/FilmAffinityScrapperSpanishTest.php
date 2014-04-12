<?php

use FilmsScrapper\Film;
use FilmsScrapper\FilmAffinityScrapper;

class FilmAffinityScrapperSpanishTest extends \PHPUnit_Framework_TestCase
{

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
        $scrapper = new FilmAffinityScrapper(FilmAffinityScrapper::LANGUAGE_SPANISH);
        $film = $scrapper->get('invalid-identifier');
        $this->assertNull($film);
    }

    public function testGet()
    {
        $scrapper = new FilmAffinityScrapper(FilmAffinityScrapper::LANGUAGE_SPANISH);
        $foundFilm = $scrapper->get(931317);
        $this->assertNotNull($foundFilm);

        $film = new Film();
        $film->setTitle('Memento')
            ->setOriginalTitle('Memento')
            ->setYear(2000)
            ->setSynopsis('Leonard es un investigador de una agencia de seguros cuya memoria está irreversiblemente dañada por culpa de un golpe en la cabeza, sufrido al intentar evitar el asesinato de su mujer: éste es el último hecho que recuerda del pasado. A causa del golpe, ha perdido la memoria reciente, es decir, los hechos cotidianos desaparecen de su mente en unos minutos. Así pues, para investigar y vengar el asesinato de su esposa tiene que recurrir a la ayuda de una cámara instantánea y a las notas tatuadas en su cuerpo. (FILMAFFINITY)')
            ->setPermalink('http://www.filmaffinity.com/es/film931317.html')
            ->setImageUrl('http://pics.filmaffinity.com/Memento-230609861-main.jpg')
            ->setRating(7.9)
            ->setDirectors(array('Christopher Nolan'))
            ->setActors(array('Guy Pearce', 'Carrie-Anne Moss', 'Joe Pantoliano', 'Mark Boone Junior', 'Stephen Tobolowsky'));
        $this->assertEquals($foundFilm, $film);
    }

    public function testFailedSearch()
    {
        $scrapper = new FilmAffinityScrapper(FilmAffinityScrapper::LANGUAGE_SPANISH);
        $films = $scrapper->search('invalid-film-title');
        $this->assertEmpty($films);
    }

    public function testSearch()
    {
        $scrapper = new FilmAffinityScrapper(FilmAffinityScrapper::LANGUAGE_SPANISH);
        $foundFilms = $scrapper->search('Criadas y Señoras');
        $this->assertNotEmpty($foundFilms);
        $this->assertEquals(1, count($foundFilms));

        $film = new Film();
        $film->setTitle('Criadas y señoras')
            ->setYear(2011)
            ->setPermalink('http://www.filmaffinity.com/es/film512560.html')
            ->setThumbnailUrl('http://pics.filmaffinity.com/Criadas_y_senoras-512560-small.jpg')
            ->setRating(8.0)
            ->setDirectors(array('Tate Taylor'))
            ->setActors(array('Emma Stone', 'Viola Davis', 'Bryce Dallas Howard', 'Sissy Spacek', 'Octavia Spencer'));
        $this->assertEquals($foundFilms[0], $film);
    }

    public function testSearchMultipleResults()
    {
        $scrapper = new FilmAffinityScrapper(FilmAffinityScrapper::LANGUAGE_SPANISH);
        $foundFilms = $scrapper->search('Futbolín');
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
            ->setThumbnailUrl('http://pics.filmaffinity.com/Futbolin_Metegol-347421-small.jpg')
            ->setRating(6.0)
            ->setDirectors(array('Juan José Campanella'))
            ->setActors(array('Animation'));
        $this->assertEquals($foundFilms[1], $film);
    }
}