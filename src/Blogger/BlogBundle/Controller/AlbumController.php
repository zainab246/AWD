<?php
namespace Blogger\BlogBundle\Controller;
use Blogger\BlogBundle\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Form\AlbumType;
use Blogger\BlogBundle\Security\AlbumVoter;
use Blogger\BlogBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class AlbumController extends Controller
{
    //getting the albums by artist
    public function artistsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $this->getDoctrine()->getRepository('Blogger\BlogBundle\Entity\Album')->getAlbumsByArtist();
        $posts = $this->getDoctrine()->getRepository('Blogger\BlogBundle\Entity\Album')->find($id);
        dump($albums);
        // dump($posts);

        return $this->render('BloggerBlogBundle:Album:albumsByArtist.html.twig'
            , array('albums' => $albums, 'posts' => $posts));
    }


//creating a new album
  public function createAlbumAction(Request $request)
    {
        //Create an empty Album entity

        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            //retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            //set the creator to the current user
            $album->setCreator($this->getUser());
            //manually set the timestamp to the new DateTime object
            $album->setTimestamp(new \DateTime());

            $fileUploader = new FileUploader($this->getParameter('image_directory'));
            $image = $album->getImage();
            $fileName = $fileUploader->upload($image);
            $album->setImage($fileName);


            $em->persist($album);
            $em->flush();
            $this->addFlash('success', 'You have added ' . $album->getTitle() . ' to the music site');
            return $this->redirect($this->generateUrl('blogger_viewalbum', ['id' => $album->getId()]));
        }
        return $this->render('BloggerBlogBundle:Album:createalbum.html.twig', array(
            'form' => $form->createView()
        ));

    }

    //reduces the similarity of file names
  private function generateUniqueFileName()
  {
      return md5(uniqid());
  }


  //editing the album
public function editAlbumAction($id, Request $request)
{
    $em = $this->getDoctrine()->getManager();
    $album = $em->getRepository('Blogger\BlogBundle\Entity\Album')->find($id);

    //adding permissions to deny access

    if(!$this->isGranted(AlbumVoter::EDIT, $album))
    {
        $this->addFlash('danger', 'You cannot edit this book');
        return $this->redirect('blogger_index');
    }

    $album->setImage(new File($this->getParameter('image_directory').'/'.$album->getImage()));
    $form = $this->createForm(AlbumType::class, $album, ['action' => $request->getUri()]);
    $form->handleRequest($request);

    if($form->isValid() and $form->isSubmitted()){

        $fileUploader = new FileUploader($this->getParameter('image_directory'));
        $image = $album->getImage();
        $fileName = $fileUploader->upload($image);
        $album->setImage($fileName);


        $em->flush();
        $this->addFlash('success', 'You have edited the details of ' . $album->getTitle());
        return $this->redirect($this->generateUrl('blogger_viewalbum', ['id' => $album->getId()]));
    }

    return $this->render('BloggerBlogBundle:Album:editalbum.html.twig', array(
        'form' => $form->createView(), 'album' => $album
    ));
}

    //deleting the album
    public function deleteAlbumAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('Blogger\BlogBundle\Entity\Album')->find($id);

        if(!$this->isGranted(AlbumVoter::DELETE, $album))
        {
            $this->addFlash('danger', 'You cannot delete this book');
            return $this->redirectToRoute('blogger_index');
        }
        try{
            $image = $album->getImage();
            $em->remove($album);
            $em->flush();
            $path = $this->getParameter('image_directory'). '/' .$image;
            $fileSystem = new Filesystem();
            $fileSystem->remove(array($path));
            $this->addFlash('success','You have successfully deleted  ' . $album->getTitle() . ' from the Music Site');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'You have to delete the reviews before you can delete the image');
        }

        return $this->redirectToRoute('blogger_index');
    }


}