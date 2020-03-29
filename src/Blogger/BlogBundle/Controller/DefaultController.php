<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    //displayes the homepage
    public function indexAction()
    {
        return $this->render('@BloggerBlog/Page/index.html.twig');
    }
}
