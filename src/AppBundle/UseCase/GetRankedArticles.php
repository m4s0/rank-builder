<?php

namespace AppBundle\UseCase;

use AppBundle\Services\ArticlesRankingBuilder;
use AppBundle\ViewModel\Article as ArticleViewModel;

/**
 * Class GetRankedArticles
 *
 * @package AppBundle\UseCase
 */
class GetRankedArticles
{
    /**
     * @var array
     */
    private $articles;
    /**
     * @var ArticlesRankingBuilder
     */
    private $articlesRankingBuilder;

    /**
     * GetRankedArticles constructor.
     *
     * @param ArticlesRankingBuilder $articlesRankingBuilder
     * @param array                  $articles
     */
    public function __construct(
        ArticlesRankingBuilder $articlesRankingBuilder,
        array $articles
    ) {
        $this->articles               = $articles;
        $this->articlesRankingBuilder = $articlesRankingBuilder;
    }

    /**
     * @return ArticleViewModel[]
     */
    public function execute()
    {
        $articlesViewModel = [];
        foreach ($this->articles as $article) {
            $articlesViewModel[] = ArticleViewModel::create($article);
        }

        return $this->articlesRankingBuilder->execute($articlesViewModel);
    }
}