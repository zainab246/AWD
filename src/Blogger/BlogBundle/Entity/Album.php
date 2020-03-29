<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Albums
 * @package Blogger\BlogBundle\Entity
 *
 * @ORM\Entity(repositoryClass="Blogger\BlogBundle\Repository\AlbumRepository")
 * @ORM\Table(name="albums")
 *
 */

class Album
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\ GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     * @Assert\NotBlank(message="please enter the book title")
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The title is too short",
     *     maxMessage="The title is too long."
     * )
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;



    /**
     * @var string
     * @Assert\NotBlank(message="please enter the albums artist")
     * @Assert\Length(
     *     min=4,
     *     max=255,
     *     minMessage="The name is too short. put in full name",
     *     maxMessage="The name is too long."
     * )
     * @ORM\Column(name="albumArtist", type="string", length=255)
     */

    private $albumArtist;


    /**
     * @var string
     * @Assert\NotBlank(message="please enter the albums trackList")
     * @Assert\Length(
     *     min=10,
     *     max=255,
     *     minMessage="The number is too short",
     *     maxMessage="The number is too long."
     * )
     * @ORM\Column(name="trackList", type="string", length=255)
     */

    private $trackList;


    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;


    /**
     *
     * @ORM\Column(name="timestamp", type="datetime")
     *
     */

    private $timestamp;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Blogger\BlogBundle\Entity\Review",mappedBy="reviewOf")
     */

    private $reviews;

    /**
     * @var \Blogger\BlogBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="Blogger\BlogBundle\Entity\User",inversedBy="albums")
     * @ORM\JoinColumn(name="creator", referencedColumnName="id")
     *
     */

    private $creator;

    /**
     * Get publisher
     *
     * @return \Blogger\BlogBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set Creator
     *
     * @param \Blogger\BlogBundle\Entity\User $creator
     *
     * @return Album
     */
    public function setCreator(\Blogger\BlogBundle\Entity\User $creator)
    {
        $this->creator = $creator;
        return $this;
    }


    /**
     * Get Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get Album Artist
     *
     * @return string
     */
    public function getAlbumArtist()
    {
        return $this->albumArtist;
    }

    /**
     * Set Album Artist
     *
     * @param string $albumArtist
     */
    public function setAlbumArtist($albumArtist)
    {
        $this->albumArtist = $albumArtist;
    }

    /**
     * Get Track List
     *
     * @return string
     */
    public function getTrackList()
    {
        return $this->trackList;
    }

    /**
     * Set Track List
     *
     * @param string $trackList
     */
    public function setTrackList($trackList)
    {
        $this->trackList = $trackList;
    }

    /**
     * Get Image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set Image
     *
     * @param string $image
     *
     * @return Album
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }


    /**
     * Set Image
     *
     * @param string $image
     *
     * @return Album
     */

    public function setImageFile(File $image =  null)
    {
        $this->image = $image;

        return $this;
    }


    /**
     * Get Timestamp
     *
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set Timestamp
     *
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get Reviews
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set Reviews
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }







}