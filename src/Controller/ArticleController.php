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
use App\Service\MDHelper;
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
    public function homepg(): Response
    {
        return $this->render('homepage.html.twig');
    }

    /**
     * @Route("/news/{slug}", name="single_article")
     * @param $slug
     * @param Environment $twigEnv
     * @param MDHelper $mdHelper
     * @param SlackClient $slack
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show($slug, Environment $twigEnv, MDHelper $mdHelper, SlackClient $slack, EntityManagerInterface $em): Response
    {
        $faker = Factory::create();

        if ($slug === 'hey') {
            $slack->sendMsg($faker->text, 'ArCon');
        }

        $commentsStub = [
            'I think that is a great to know about kielbasa.',
            'Drat, guys! Let it be just a wine.',
            'OK. I think we need just eat.',
        ];

        $repo = $em->getRepository(Article::class);
        $article = $repo->findOneBy(['slug' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException(sprintf('There is no article with the slug "%s" in the database.', $slug));
        }


        $html = $twigEnv->render('show.html.twig', [
                'article' => $article,
                'comments' => $commentsStub
            ]
        );

        return new Response($html);
    }

    /**
     * @Route(path="/news/{slug}/likes", name="article_liker_like", methods={"POST"})
     * @param $slug
     * @param LoggerInterface $logger
     * @return Response
     * @throws \Exception
     */
    public function toggleLikes($slug, LoggerInterface $logger): Response
    {
        $logger->info('Article is being liked.');

        return $this->json(['likes' => random_int(2, 99)]);
    }
}
