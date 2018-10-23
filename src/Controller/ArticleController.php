<?php
/**
 * Created by Pavel Burylichau
 * Company: iRoyalPR
 * User: paul.burilichev@gmail.com
 * Date: 10/20/18
 * Time: 3:19 PM
 */


namespace App\Controller;


use App\Service\MDHelper;
use Faker\Factory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ArticleController extends AbstractController
{
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
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show($slug, Environment $twigEnv, MDHelper $mdHelper): Response
    {
        $commentsStub = [
            'I think that is a great to know about kielbasa.',
            'Drat, guys! Let it be just a wine.',
            'OK. I think we need just eat.',
        ];
        $faker = Factory::create();
        $contentsStub = $faker->paragraphs;

        $creds = "and [my website](https://rpr.by/)";

        $contentsStub []= $mdHelper->parse($creds);

        $titleStub = $faker->words(5, true);

        $html = $twigEnv->render('show.html.twig', [
                'title' => ucwords(str_replace('-', ' ', $titleStub)),
                'contents' => $contentsStub,
                'comments' => $commentsStub,
                'slug' => $slug,
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
        //TODO: Like/Dislike an article.
        $logger->info('Article is being liked.');

        return $this->json(['likes' => random_int(2, 99)]);
    }
}
