<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleAdminController
 * @package App\Controller
 */
class ArticleAdminController extends AbstractController
{
    /**
     * @Route(path="/admin/article/new", name="article_admin_new")
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     *
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $manager)
    {
        $form = $this->createForm(ArticleFormType::class);

        

        return $this->render('article_admin/new.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit", name="article_admin_edit")
     * @param Article $article
     *
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article)
    {
        // or use this instead of annotation
        //$this->denyAccessUnlessGranted('MANAGE', $article);

        dd($article);

    }

    /**
     * @Route("/admin/article", name="article_admin")
     */
    public function index()
    {
        return $this->render('article_admin/index.html.twig', [
            'controller_name' => 'ArticleAdminController',
        ]);
    }
}
