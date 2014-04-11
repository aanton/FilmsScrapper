<?php

namespace FilmsScrapper;

class Film
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $originalTitle;

    /** @var string */
    protected $synopsis;

    /** @var string */
    protected $permalink;

    /** @var float */
    protected $rating;

    /** @var string */
    protected $imageUrl;

    /** @var string */
    protected $thumbnailUrl;

    /** @var int */
    protected $year;

    /** @var array */
    protected $directors;

    /** @var array */
    protected $actors;

    /**
     * @return string
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * @param string $synopsis
     * @return $this
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     * @return $this
     */
    public function setRating($rating)
    {
        $this->rating = floatval($rating);
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * @param string $thumbnailUrl
     * @return $this
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     * @return $this
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = intval($year);
        return $this;
    }

    /**
     * @return array
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * @param array $actors
     * @return $this
     */
    public function setActors($actors)
    {
        $this->actors = $actors;
        return $this;
    }

    /**
     * @return array
     */
    public function getDirectors()
    {
        return $this->directors;
    }

    /**
     * @param array
     * @return $this
     */
    public function setDirectors($directors)
    {
        $this->directors = $directors;
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalTitle()
    {
        return $this->originalTitle;
    }

    /**
     * @param string $originalTitle
     * @return $this
     */
    public function setOriginalTitle($originalTitle)
    {
        $this->originalTitle = $originalTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return $this
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

}