<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_index")
     * @return Response
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'owner'=>'Vincent',
        ]);
    }

    /**
     * @Route("/blog/show/{slug}", name="blog_show", requirements={"slug"="[a-z0-9-]+"})
     */
    public function show($slug = 'Article Sans Titre')
    {
        $formatTitle = ucwords(str_replace('-',' ',(strtolower($slug))));
        return $this->render('blog/show.html.twig', [
           'slug'=>$formatTitle,
        ]);
    }
}