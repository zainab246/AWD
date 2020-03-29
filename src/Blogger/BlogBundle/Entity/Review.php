<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Review
 * @package Blogger\BlogBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="reviews")
 * @ORM\Entity(repositoryClass="Blogger\BlogBundle\Repository\ReviewRepository")
 *
 */

class Review
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;


    /**
     * @var string
     * @Assert\NotBlank(message="please enter the review title")
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
     *
     * @var string
     * @Assert\NotBlank(message="please write a review")
     * @Assert\Length(
     *     min=10,
     *     max=300,
     *     minMessage="The review is too short",
     *     maxMessage="The review is too long."
     * )
     * @ORM\Column(name="article", type="text")
     */
    private $article;

    /**
     * @var \DateTime
     * @Assert\DateTime
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var \Blogger\BlogBundle\Entity\Album
     * @ORM\ManyToOne(targetEntity="Blogger\BlogBundle\Entity\Album",inversedBy="reviews")
     * @ORM\JoinColumn(name="reviewOf", referencedColumnName="id")
     */
    private $reviewOf;

    /**
     * @var \Blogger\BlogBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\Blogger\BlogBundle\Entity\User",inversedBy="articles")
     * @ORM\JoinColumn(name="reviewer", referencedColumnName="id")
     */
    private $reviewer;


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
     * Get Article
     *
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }


    /**
     * Set Article
     *
     * @param string $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * Get Timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set TimeStamp
     *
     * @param \DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }



    /**
     * Get reviewOf.
     *
     * @return \Blogger\BlogBundle\Entity\Album
     */
    public function getReviewOf()
    {
        return $this->reviewOf;
    }



    /**
     * Set reviewOf.
     *
     * @param \Blogger\BlogBundle\Entity\Album $reviewOf
     *
     * @return Review
     */
    public function setReviewOf($reviewOf)
    {
        $this->reviewOf = $reviewOf;
        return $this;
    }


    /**
     * Set reviewer.
     *
     * @param \Blogger\BlogBundle\Entity\User
     *
     * @return Review
     */
    public function setReviewer($reviewer)
    {
        $this->reviewer = $reviewer;
        return $this;
    }


    /**
     * Get reviewer.
     *
     * @return \Blogger\BlogBundle\Entity\User
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }


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
     * Set Id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



}

