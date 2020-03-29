<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\BloggerBlogBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Blogger\BlogBundle\Entity\Album;
use Blogger\BlogBundle\Entity\Review;
use Blogger\BlogBundle\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;




class PageController extends Controller
{
    //finds all the albums
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository('Blogger\BlogBundle\Entity\Album')->findAll();
        // dump($posts);
        return $this->render('BloggerBlogBundle:Page:index.html.twig'
            ,array('posts' => $posts));

    }

    //displays the about page
    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }


    //displays each album onto a different page with its details along with its reviews
    public function moreAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        //retrieving the book id
        $posts = $em->getRepository('Blogger\BlogBundle\Entity\Album')->find($id);
        $reviews = $em->getRepository('Blogger\BlogBundle\Entity\Review')->findAllReviewsby($id);
        // dump($reviews);

        //dump($posts);

        return $this->render('BloggerBlogBundle:Page:more.html.twig',array(
            'posts' => $posts, 'reviews' => $reviews));

        // dump($posts);


    }


}


