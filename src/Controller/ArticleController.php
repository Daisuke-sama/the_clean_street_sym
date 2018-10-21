<?php
/**
 * Created by Pavel Burylichau
 * Company: iRoyalPR
 * User: paul.burilichev@gmail.com
 * Date: 10/20/18
 * Time: 3:19 PM
 */


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
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
        return new Response(sprintf("The demonstration of a slug generation from routes annotations directly: %s",
            $slug));
    }
}
