<?php
/**
 * Created by Pavel Burylichau
 * Company: iRoyalPR
 * User: paul.burilichev@gmail.com
 * Date: 10/20/18
 * Time: 3:19 PM
 */


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function homepg(): Response
    {
        return new Response('That\'s me!');
    }

    /**
     * @Route("/news/{slug}")
     */
    public function show($slug): Response
    {
        $commentsStub = [
            'I think that is a great to know about kielbasa.',
            'Drat, guys! Let it be just a wine.',
            'OK. I think we need just eat.',
        ];

        return $this->render('show.html.twig', [
                'title' => ucwords(str_replace('-', ' ', $slug)),
                'comments' => $commentsStub,
            ]
        );
    }
}
