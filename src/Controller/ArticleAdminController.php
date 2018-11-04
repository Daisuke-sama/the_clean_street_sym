<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route(path="/admin/article/new")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function new(EntityManagerInterface $manager)
    {
        die('todo');

        $article = new Article();
        $title = 'Sit Totam Debitis Esse Aut';
        $article->setTitle($title)
            ->setSlug(strtolower(str_replace(' ', '-', $title)) . rand(123, 912))
            ->setAuthor('Daisuke-king')
            ->setLikeCount(rand(10, 101))
            ->setImageFilename('asteroid.jpg')
            ->setContent(<<<EOF
            and [my website](https://rpr.by/)
Quas dicta odit optio cumque. Sit et aliquid repellendus libero. Molestiae iure ullam aut omnis. Magni nisi autem ipsam est.

Ullam delectus tenetur commodi repellendus omnis aut. Sed autem molestiae repellendus porro nesciunt et. Sapiente itaque cupiditate suscipit recusandae officiis reprehenderit.

Ipsum voluptatem pariatur beatae optio. Est dolor deserunt sit similique odit rerum similique. Ut in odio earum omnis voluptas vitae et enim. Nihil alias molestias necessitatibus explicabo officiis consequatur.
EOF
            );
        if (rand(2, 10) > 2) {
            $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1, 99))));
        }


        $manager->persist($article);
        $manager->flush();

        return new Response(sprintf(
                'Article saved: id: #%d; slug: %s',
                $article->getId(),
                $article->getSlug())
        );
    }

    /**
     * @Route("/article/admin", name="article_admin")
     */
    public function index()
    {
        return $this->render('article_admin/index.html.twig', [
            'controller_name' => 'ArticleAdminController',
        ]);
    }
}
