<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repo = $entityManager->getRepository(Article::class);

        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form($id=null,Request $request , EntityManagerInterface $entityManager, ArticleRepository $articleRepository )
    {
        
        
        if(!$id){
            
            $article = new Article();
        }
        else{
            
            $article= $articleRepository->find($id);  
        }

        // $form = $this->createFormBuilder($article)
        //              ->add("title")
        //              ->add("content")
        //              ->add("image")
        //             //  ->add("title" , TypeTextType::class,[
        //             //     "attr" => [
        //             //         "placeholder" => "Titre de l'article",
        //             //         // "class" => "form-control"
        //             //     ]
        //             //  ] )
                     
        //              ->getForm();


        $form = $this->createForm(ArticleType::class,$article);
        
                // Analyse la requete http fourni dans la request
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    if(!$article->getId()){

                        $article->setCreatedAt(new DateTime());
                    }

                    $entityManager->persist($article);
                    $entityManager->flush();

                    return $this->redirectToRoute("blog_show",["id" => $article->getId()]);
                }

                return $this->render('blog/create.html.twig',[
                    'formArticle' => $form->createView(),
                    'editMode' => $article->getId() !== null
                ]);

        
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */

    public function show($id, EntityManagerInterface $entityManager)
    {
        $repo = $entityManager->getRepository(Article::class);

        $article = $repo->find($id);
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}
