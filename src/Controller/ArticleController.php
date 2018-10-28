<?php
/**
 * Created by Pavel Burylichau
 * Company: iRoyalPR
 * User: paul.burilichev@gmail.com
 * Date: 10/20/18
 * Time: 3:19 PM
 */


namespace App\Controller;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ArticleController extends AbstractController
{
    /**
     * @var
     */
    private $isDebug;

    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function homepg(EntityManagerInterface $entityManager): Response
    {
        $repo = $entityManager->getRepository('App:Article');
        $articles = $repo->findAllPublishedOrderedByNewest();

        return $this->render('homepage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="single_article")
     * @param Article $article
     * @param Environment $twigEnv
     * @param SlackClient $slack
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Article $article, Environment $twigEnv, SlackClient $slack): Response
    {
        if ($article->getSlug() === 'hey') {
            $faker = Factory::create();
            $slack->sendMsg($faker->text, 'ArCon');
        }

        $commentsStub = [
            'I think that is a great to know about kielbasa.',
            'Drat, guys! Let it be just a wine.',
            'OK. I think we need just eat.',
        ];

        $html = $twigEnv->render('show.html.twig', [
                'article' => $article,
                'comments' => $commentsStub
            ]
        );

        return new Response($html);
    }

    /**
     * @Route(path="/news/{slug}/likes", name="article_liker_like", methods={"POST"})
     * @param Article $article
     * @param LoggerInterface $logger
     * @return Response
     * @throws \Exception
     */
    public function toggleLikes(Article $article, LoggerInterface $logger, EntityManagerInterface $em): Response
    {
        $article->incrementLikes();
        $em->flush();

        $logger->info('Article is being liked.');

        return $this->json(['likes' => $article->getLikeCount()]);
    }
}
