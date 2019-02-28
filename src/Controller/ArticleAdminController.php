<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $art = new Article();
            $art->setTitle($data['title']);
            $art->setContent($data['content']);
            $art->setAuthor($this->getUser());

            $em->persist($art);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

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
    public function list(ArticleRepository $articleRepository)
    {
        $arts = $articleRepository->findAll();

        return $this->render('article_admin/list.html.twig', [
            'articles' => $arts,
        ]);
    }
}
