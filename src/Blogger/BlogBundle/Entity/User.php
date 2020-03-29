<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser


{
    public function __construct()
    {
        parent::__construct();
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * Get Id
     *
     * @return int
     */

    public function getId()
    {
        return $this->id;
    }


    /**
     * Get Firstname
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstname
     *
     * @param $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /***
     * @param $email
     * @return BaseUser|\FOS\UserBundle\Model\UserInterface
     */
    public function setEmail($email)
    {


        return parent::setEmail($email);

    }

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Blogger\BlogBundle\Entity\Review",mappedBy="reviewer",cascade="remove")
     */
    protected $articles;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Blogger\BlogBundle\Entity\Album",mappedBy="creator",cascade="remove")
     */

    protected $albums;

    /**
     * Add Album.
     *
     * @param \Blogger\BlogBundle\Entity\Album
     *
     * @return User
     */
    public function addAlbum(\Blogger\BlogBundle\Entity\Album $album)
    {
        $this->albums[] = $album;
        return $this;
    }

    /**
     * Remove Album
     *
     * @param \Blogger\BlogBundle\Entity\Album $album
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAlbum(\Blogger\BlogBundle\Entity\Album $album)
    {
        return $this->albums->removeElement($album);
    }

    /**
     * Get Album
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAlbums()
    {
        return $this->albums;
    }




}
