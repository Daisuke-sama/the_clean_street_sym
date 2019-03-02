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
    private const ARTICLE_FORM_VAR_IN_TWIG = 'articleForm';

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
            /** @var Article $art */
            $art = $form->getData();

            $em->persist($art);
            $em->flush();

            $this->addFlash('success', 'Article has been created.');

            return $this->redirectToRoute('article_admin_list');
        }

        return $this->render('article_admin/new.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit", name="article_admin_edit")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Article $article
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(EntityManagerInterface $em, Request $request, Article $article)
    {
        $form = $this->createForm(ArticleFormType::class, $article, [
            'include_published_at' => true,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article has been updated.');

            return $this->redirectToRoute('article_admin_edit', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article_admin/edit.html.twig', [
            self::ARTICLE_FORM_VAR_IN_TWIG => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article", name="article_admin_list")
     */
    public function list(ArticleRepository $articleRepository)
    {
        $arts = $articleRepository->findAll();

        return $this->render('article_admin/list.html.twig', [
            'articles' => $arts,
        ]);
    }

    /**
     * @Route(path="/admin/article/location-select", name="admin_article_location_select")
     * @param Request $request
     * @return Response
     */
    public function getSpecificLocationSelectGroup(Request $request)
    {
        $article = new Article();
        $article->setLocation($request->query->get('location'));

        $form = $this->createForm(ArticleFormType::class, $article);

        // no field? Return an empty response
        if (!$form->has(ArticleFormType::SPECIFIC_LOCATION_FIELD_NAME)) {
            return new Response(null, 200);
        }

        return $this->render('article_admin/_specific_location_name.html.twig', [
            self::ARTICLE_FORM_VAR_IN_TWIG => $form->createView(),
        ]);
    }
}
