<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $articles = $this->get('app.use_case.get_ranked_articles')->execute();

        return $this->render(
            'AppBundle:Default:_ranked-articles.html.twig',
            [
                'articles' => $articles
            ]
        );
    }
}
