<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     *
     * Show all row from article's entity
     *
     * @Route("/blog", name="blog_index")
     * @return Response
     */
    public function index() :Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if(!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render('blog/index.html.twig', [
            'articles'=>$articles,
        ]);
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/blog/show/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show(?string $slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }

    /**
     * @param string $categoryName
     * @Route("/blog/category/{categoryName}", name="show_category")
     * @return Response
     *
     */
    public function showByCategory(string $categoryName) :Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find articles in article\'s table.');
        }

        $categoryName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($categoryName)), "-")
        );

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower(ucfirst($categoryName))]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with '.$category.' name, found in category\'s table.'
            );
        }

//        $articlesByCategory = $this->getDoctrine()
//            ->getRepository(Article::class)
//            ->findBy(['category'=> $Category->getId()],
//                ['id'=>'DESC'],
//                3);

        $articlesByCategory = $category->getArticles();

        return $this->render(
            'blog/category.html.twig',
            ['articles'=>$articlesByCategory,
                'category'=>$category]
        );
    }
}
