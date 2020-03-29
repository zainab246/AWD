<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Review;
use Blogger\BlogBundle\Entity\Album;
use Blogger\BlogBundle\Form\ReviewType;
use Blogger\BlogBundle\Security\ReviewVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    //create a review
    public function createAction($id, Request $request)
    {
        // Create a new empty Review entity
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        //validating the form
        if ($form->isSubmitted() and $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $album = $em->getRepository('BloggerBlogBundle:Album')->find($id);
            //dump($album);

            //manually set the author to the current user
            $review->setReviewer($this->getUser());

            $review->setReviewOf($album);
            //manually set the timestamp to a new DateTime object
            $review->setTimestamp(new \DateTime());

            //tell the entity manager to persist this entity
            $em->persist($review);

            //commit all changes
            $em->flush();
            $this->addFlash('success', 'You have added a review');
            return $this->redirect($this->generateUrl('blogger_viewalbum', ['id' => $album->getId()]));
        }
        //Render the view from twig and pass the form to the view
        return $this->render('BloggerBlogBundle:Review:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

//edit a review
    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BloggerBlogBundle:Review')->find($id);
        if (!$this->isGranted(ReviewVoter::EDIT, $review))
        {
            $this->addFlash('warning', 'You are not allowed to edit this album');
            return $this->redirectToRoute('blogger_index');

        }

        $form = $this->createForm(ReviewType::class, $review, ['action' => $request->getUri() ]);

        $form->handleRequest($request);
        if($form->isValid())
        { $em->flush();
            $this->addFlash('success','You have successfully edited your review!');
            return $this->redirect($this->generateUrl('blogger_viewalbum', ['id' => $review->getReviewOf()->getId()]));
        }

        return $this->render('BloggerBlogBundle:Review:edit.html.twig', array(
            'form' => $form->createView(),
            'reviews' => $review
        ));

    }

    //delete a review
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BloggerBlogBundle:Review')->find($id);

        if (!$this->isGranted(ReviewVoter::DELETE, $review))

        {
            $this->addFlash('warning','You are not allowed to delete this album');
            return $this->redirectToRoute('blogger_index');
        }

        $em->remove($review);
        $em->flush();
        $this->addFlash('success', 'Your review has been deleted, go ahead and review a different album!');

        return $this->redirect($this->generateUrl('blogger_index'));
    }

}
