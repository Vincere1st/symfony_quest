<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Form\ArticleSearchType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /**
     *
     * Show all row from article's entity
     *
     * @Route("/blog", name="blog_index")
     * @return Response
     */
    public function index(ArticleRepository $articleRepository) :Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if(!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        $form = $this->createForm(
            ArticleSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );

        return $this->render('blog/index.html.twig', [
            'articles'=> $articleRepository->findAllWithCategories(),
            'form' => $form->createView(),
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
     * @Route("/blog/category", name="add_category")
     *
     * @return Response
     */

    public function addCategory(Request $request)
    {
        $category =new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/add.html.twig',
            ['form'=>$form->createView()]);
    }

    /**
     *
     * @Route("/blog/category/{name}", name="show_category")
     *
     * @return Response
     *
     */
    public function showByCategory(Category $categoryName) :Response
    {

        $articlesByCategory = $categoryName->getArticles();

        return $this->render(
            'blog/category.html.twig',
            ['articles'=>$articlesByCategory,
                'category'=>$categoryName]
        );
    }

    //Crée une page, répondant à l'appel de la route /tag/{name} et affichant les informations suivantes :
    //
    //Le nom du tag,
    //La liste des articles associés à ce tag.
    //Que l’on soit en OneToMany ou en ManyToMany ne change en rien la maniere de faire, vue jusqu’à présent.

    /**
     * @Route("/blog/tag/{name}", name="show_by_tag")
     *
     * @return Response
     */

    public function showByTag(Tag $tag) :Response
    {
        $articlesByTag = $tag->getArticles();;

        return $this ->render(
            'blog/tag.html.twig',
            ['articles'=>$articlesByTag]
        );
    }
}
