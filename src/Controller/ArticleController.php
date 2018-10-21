<?php
/**
 * Created by Pavel Burylichau
 * Company: iRoyalPR
 * User: paul.burilichev@gmail.com
 * Date: 10/20/18
 * Time: 3:19 PM
 */


namespace App\Controller;


use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @return Response
     */
    public function show($slug): Response
    {
        $commentsStub = [
            'I think that is a great to know about kielbasa.',
            'Drat, guys! Let it be just a wine.',
            'OK. I think we need just eat.',
        ];

        $faker = Factory::create();
        $contentsStub = $faker->paragraphs;
        $titleStub = $faker->words(5, true);

        return $this->render('show.html.twig', [
                'title' => ucwords(str_replace('-', ' ', $titleStub)),
                'contents' => $contentsStub,
                'comments' => $commentsStub,
                'slug' => $slug,
            ]
        );
    }

    /**
     * @Route(path="/news/{slug}/likes", name="article_liker_like", methods={"POST"})
     * @param $slug
     * @return Response
     * @throws \Exception
     */
    public function toggleLikes($slug): Response
    {
        //TODO: Like/Dislike an article.

        return $this->json(['likes' => random_int(2, 99)]);
    }
}
