<?php
namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Album;
use Blogger\BlogBundle\Entity\User;
use Blogger\BlogBundle\Form\AlbumType;
use Blogger\BlogBundle\Entity\Review;
use Blogger\BlogBundle\Form\ReviewType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class BlogAPIController extends FOSRestController
{
//
//    public function getBlogpostAction($id) {
//        $em = $this->getDoctrine()->getManager();
//        $entry = $em->getRepository('BloggerBlogBundle:Album')->find($id);
//        if(!$entry) {
//        // no blog entry is found, so we set the view
//        // to no content and set the status code to 404
//            $view = $this->view(null, 404);
//        }
//        else {
//        // the blog entry exists, so we pass it to the view // and the status code defaults to 200 "OK"
//            $view = $this->view($entry);
//        }
//        return $this->handleView($view); }



    //methods for reviews



    //getting all the reviews of all albums
    //POST method api/v1/albums
    public function getReviewsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository(Review::class)->findAll();

        return $this->handleView($this->view($reviews));
    }


    //only gets one review of the album and not all the reviews of that specific album
    // GET method
    //getting all reviews for one album
    //api_blog_get_album_reviews
   // api/v1/albums/{albumID}/reviews
    public function getAlbumReviewsAction($albumID)
    {
        $em = $this->getDoctrine()->getManager();
        //does not work with this line
        //$reviews = $em->getRepository('BloggerBlogBundle:Review')->findBy(['reviewOf' => $albumID]);

        //does not show you the review of the album column
        $reviews = $em->getRepository(Review::class)->find($albumID);
        if(!$reviews){
            //no album is found so set the view
            //to no content and the status code to 404
            $view = $this->view(null, 404);
        } else {
            //the album does exist so its passed to the view
            //the status code is 200 OK
            $view = $this->view($reviews);
        }
        return $this->handleView($view);
    }

    //not working still gets all reviews of all albums
    //GET method
    //getting one review of one album
    //api_blog_get_album_review
    // /api/v1/albums/"albumID}/reviews/{reviewID}

    public function getAlbumReviewAction($albumID, $reviewID)
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository(Review::class)-> findOneBy(['reviewOf' => $albumID, 'id' =>$reviewID]);
        if(!$reviews){
            //no album is found so set the view
            //the status code is
            $view = $this->view(null, 400);
        }
        else{
            //the album does exist so pass it to the view
            //the  status code is 200 OK
            $view = $this->view($reviews);
        }
        return $this->handleView($view);
    }


////testing users
//// /api/v1/users
////username = test123 id=3
//    public function getUsersAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//        $reviews = $em->getRepository(User::class)->findAll();
//
//        return $this->handleView($this->view($reviews));
//    }





    //creating an album review
    //POST method
    //api_blog_post_users_album_reviews
    // /api/v1/users/albums/{albumID}/reviews
    // /api/v1/27/albums/11/reviews
    public function postUserAlbumReviewsAction(Request $request, $albumID)
    {
        if (!$this->getUser()){
            throw new AccessDeniedException();
        }

        $user = $this->getUser();
        $review = new Review();
        //prepare the form
        $form = $this->createForm(ReviewType::class, $review);

        //check if we can parse the POST data
        if($request->getContentType() != 'json'){
            return $this->handleView($this->view(null, 400));
        }

        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));

        //Check that the POST data meets validations (using the form)
        if($form->isValid()) {

            //If they are met, create a new Entry entity and persist it
            $em = $this->getDoctrine()->getManager();
            $album = $em->getRepository(Album::class)->find($albumID);
            if (!$album)
            {
                return $this->handleView($this->view(null, 400));
            }

            if ($user instanceof User and $album instanceof Album){
                $review->setReviewer($user);
                $review->setReviewOf($album);
                $review->setTimestamp(new \DateTime());
                $em->persist($review);
                $em->flush();
                //return a new 201 “created” status code along with the URL where we can GET the
                //created Entry in the Location header
                return $this->handleView($this->view(null, 201)
                    ->setLocation($this->generateUrl('api_album_get_album_review', ['albumID' => $album->getId(), 'reviewID' => $review->getId()]))
                );
            }
            else{
                return $this->handleView($this->view($form, 401));

            }
        }
        else{
            //the form is not valid
            //return the status code of 400
            return $this->handleView($this->view($form, 400));

        }
    }







    //PUT Method
    //Editing an albums review
    public function putUserBookReviewAction(Request $request, $albumID, $reviewID)
    {
        if(!$this->getUser()){
            throw new AccessDeniedException();
        }

        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $reviews = $entityManager->getRepository(Review::class)->findOneBy(['reviewOf' => $albumID, 'id' =>$reviewID]);
        if (!$reviews)
        {
            return $this->handleView($this->view(null, 404));

        }

        $newAlbumReview =  new Review();
        if($request->getContentType() != 'json'){
            return $this->handleView($this->view(null, 400));
        }
        $form = $this->createForm(ReviewType::class, $newAlbumReview,
            ['action' => $request->getUri()]
        );

        $form->submit(json_decode($request->getContent(), true));


        if($form->isValid()){
            if ($reviews->getReviewer() == $user)
            {
                $reviews->setTitle($newAlbumReview->getTitle());
                $reviews->setArticle($newAlbumReview->getArticle());
                $reviews->setTimestamp(new \DateTime());


                $entityManager->persist($reviews);
                $entityManager->flush();

                return $this->handleView($this->view(null, 201)
                ->setLocation($this->generateUrl('api_album_get_album_review', ['albumID' => $reviews->getReviewOf(), 'reviewID' => $reviews->getId()])
                ));

            }
            else
            {
                $view = $this->view(null, 403);
                return $this->handleView($view);
            }
        }
        else
            {
            return $this->handleView($this->view($form, 400));
        }

    }








    //Delete method
    //Removing an album review using a userID
    public function deleteUserAlbumReviewAction($albumID, $reviewID)
    {
        if(!$this->getUser()){
            throw new AccessDeniedException();
        }

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $reviews = $em->getRepository(Review::class)->findOneBy(['reviewOf' => $albumID, 'id' => $reviewID]);
        if(!$reviews){
            //no review entry found
            //set the view and status code to 404
            $view = $this->view(null, 404);
        }
        else{
            if ($reviews->getReviewer() == $user)
            {
                //the review entry does exist
                //set the status code to 200 OK
                $em->remove($reviews);
                $em->flush();
                $view = $this->view(null, 200);
            }
            else
            {
                $view = $this->view(null, 403);
            }
        }
        return $this->handleView($view);


    }











    //methods for albums







    //GET method
    //getting all the albums
    // /api/v1/album
    public function getAlbumsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $em->getRepository(Album::class)->findAll();

        return $this->handleView($this->view($albums));
    }



    //GET one album
    //getting one album
//    /api/v1/albums/11
    public function getAlbumAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $em->getRepository(Album::class)->find($id);
        if (!$albums){
            //no blog entry is found so we set the view
            //to no content and set the staus code to 404
            $view = $this->view(null, 404);
        }
        else{
            //the blog entry exists, so we pass it to the view
            //and the status code defaults to 200 "OK"
            $view = $this->view($albums);
        }
        return $this->handleView($view);
    }






//    PUT method for editing an album
// /api/v1/blogsposts/{id}
//        public function putAlbumAction(Request $request, $albumID)
//        {
//            if (!$this->getUser()){
//                throw new AccessDeniedException();
//            }
//
//            $user = $this->getUser();
//            $em = $this->getDoctrine()->getManager();
//            $album = $em->getRepository(Album::class)->find($id);
//            if (!$album)
//            {
//                return $this->handleView($this->view(null, 404));
//
//            }
//
//            $newAlbum = new Album();
//            if ($request->getContentType() != 'json')
//            {
//                return $this->handleView($this->view(null, 400));
//
//            }
//            $form = $this->createForm(AlbumType::class, $newAlbum,
//                ['action' => $request->getUri()]
//            );
//
//            //json decode the request content and pass it into the form
//            $form->submit(json_decode($request->getContent(), true));
//            if($form->isValid()){
//                if($album->getCreator()== $user){
//                    //testing with just the title
//                    $album->setTitle($newAlbum->getTitle());
//                    //album artist
//                    $album->setAlbumArtist($newAlbum->getAlbumArtist());
//                    //track list
//                    $album->setTrackList($newAlbum->getTrackList());
//                    //set the image too
////                    $album->setImage("image.jpeg");
//
//                    //point 4 of the list
//                    $em->persist($album);
//                    $em->flush();
//
//                    //set the status code to 201 and set the location header
//                    //to the URL to get the blog entry
//                    return $this->handleView($this->view(null, 200)
//                        ->setLocation($this->generateUrl('api_blog_get_blogpost', ['id' => $album->getId()]))
//                    );
//                }
//                else{
//                    $view = $this->view(null, 403);
//                    return $this->handleView($view);
//                }
//
//            }
//            else{
//                //the form is not valid so return the form with 400 status code
//                return $this->handleView($this->view($form, 400));
//            }
//
//        }
//
//
//
//    //Delete an album
//    //DELETE method /api/v1/blogsposts/{id}
//
//
//    public function deleteBlogspostsAction($id)
//    {
//        $user = $this->$this->getUser();
//
//        $em =  $this->getDoctrine()->getManager();
//        $album = $em->getRepository(Album::class)->findOneBy(['id' => $id]);
//        if(!$album){
//            //if there is no album entry set the view to no content and the status code 404
//            $view = $this->view(null, 404);
//        } else {
//            if ($album->getCreator() == $user)
//            {
//                //if the album does exist it is passsed to the view and the status code is 200 "OK"
//                $em->remove(($album));
//                $em->flush();
//                $view = $this->view(null, 200);
//            }
//            else
//            {
//
//                $view = $this->view(null, 403);
//            }
//        }
//        return $this->handleView($view);
//
//
//    }
//
//
//

}
