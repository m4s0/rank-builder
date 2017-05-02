<?php

namespace AppBundle\Services;

use AppBundle\ViewModel\Article as ArticleViewModel;

/**
 * Class ArticleRankBuilder
 *
 * @package AppBundle\Services
 */
class ArticlesRankingBuilder
{
    const PRETEND_UP   = 4;
    const PRETEND_DOWN = 10;

    /**
     * @var int
     */
    private $votesWeight;
    /**
     * @var int
     */
    private $viewsWeight;
    /**
     * @var int
     */
    private $commentsWeight;
    /**
     * @var int
     */
    private $editorRatingWeight;
    /**
     * @var int
     */
    private $creationDateWeight;
    /**
     * @var int
     */
    private $primaPaginaWeight;
    /**
     * @var int
     */
    private $articleImageWeight;

    /**
     * ArticlesRankingBuilder constructor.
     *
     * @param $votesWeight
     * @param $viewsWeight
     * @param $commentsWeight
     * @param $editorRatingWeight
     * @param $creationDateWeight
     * @param $primaPaginaWeight
     * @param $articleImageWeight
     */
    public function __construct(
        $votesWeight = 1,
        $viewsWeight = 1,
        $commentsWeight = 1,
        $editorRatingWeight = 1,
        $creationDateWeight = 1,
        $primaPaginaWeight = 1,
        $articleImageWeight = 1
    ) {
        $this->votesWeight        = $votesWeight;
        $this->viewsWeight        = $viewsWeight;
        $this->commentsWeight     = $commentsWeight;
        $this->editorRatingWeight = $editorRatingWeight;
        $this->creationDateWeight = $creationDateWeight;
        $this->primaPaginaWeight  = $primaPaginaWeight;
        $this->articleImageWeight = $articleImageWeight;
    }

    /**
     * @param ArticleViewModel[] $articles
     *
     * @return ArticleViewModel[]
     */
    public function execute(array $articles): array
    {
        $tmp = [];
        /** @var ArticleViewModel $article */
        foreach ($articles as $index => $article) {
            $tmp[$index] = $this->getScore($article);
        }

        arsort($tmp);

        $articlesRanked = [];
        foreach ($tmp as $index => $item) {
            $articlesRanked[] = $articles[$index];
        }

        return $articlesRanked;
    }

    /**
     * Weighted geometric mean
     *
     * @see  https://en.wikipedia.org/wiki/Weighted_geometric_mean
     *
     * ex_:
     * S = (A^7 ✕ B^5)^[1/(7+5)]
     *
     *
     *
     * @todo valutare questo score https://www.elastic.co/blog/found-function-scoring
     *
     *
     * @param ArticleViewModel $article
     *
     * @return float
     */
    private function getScore(ArticleViewModel $article): float
    {
        $votesScore        = $this->getVotesScore($article);
        $viewsScore        = $this->getViewsScore($article, 20, 6);
        $commentsScore     = $this->getCommentsScore($article, 20, 8);
        $editorRatingScore = $this->getEditorRatingScore($article);
        $dateTimeScore     = $this->getDateTimeScore($article);

        $score =
            (
                $votesScore ** $this->votesWeight *
                $viewsScore ** $this->viewsWeight *
                $commentsScore ** $this->commentsWeight *
                $editorRatingScore ** $this->editorRatingWeight
            ) ** (
                1 /
                (
                    $this->votesWeight +
                    $this->viewsWeight +
                    $this->commentsWeight +
                    $this->editorRatingWeight
                )
            );

        /**
         * @todo TO REMOVE
         */
        $article->setVotesScore($votesScore);
        $article->setViewsScore($viewsScore);
        $article->setCommentsScore($commentsScore);
        $article->setEditorRatingScore($editorRatingScore);
        $article->setDateTimeScore($dateTimeScore);
        $article->setScore($score);

        return $score / $dateTimeScore;
    }

    /**
     * Lower bound of Wilson score confidence interval for a Bernoulli parameter
     *
     * @see http://www.evanmiller.org/how-not-to-sort-by-average-rating.html
     *
     * $positiveRatings is the number of positive ratings, $totalRatings is the total number of ratings, and
     * $confidence refers to the statistical confidence level: pick 0.95 to have a 95% chance that your lower bound is
     * correct, 0.975 to have a 97.5% chance, etc.
     * The z-score in this function never changes, so if you don't have a statistics package handy or if performance is
     * an issue you can always hard-code a value here for z. (Use 1.96 for a confidence level of 0.95.)
     *
     * @param $positiveRatings
     * @param $totalRatings
     * @param $confidence
     *
     * @return float
     */
    private static function scoreConfidenceInterval($positiveRatings, $totalRatings, $confidence): float
    {
        if ($totalRatings === 0) {
            return (float)0;
        }

        $z     = 1.96;
        $p_hat = 1.0 * $positiveRatings / $totalRatings;

        return ($p_hat + $z * $z / (2 * $totalRatings) - $z * sqrt(($p_hat * (1 - $p_hat) + $z * $z / (4 * $totalRatings)) / $totalRatings)) / (1 + $z * $z / $totalRatings);
    }

    /**
     * @see http://www.evanmiller.org/bayesian-average-ratings.html
     * @see https://en.wikipedia.org/wiki/Bayesian_average
     *
     * @param int $upVotes
     * @param int $downVotes
     *
     * @return float
     */
    private static function bayesianAverage(int $upVotes, int $downVotes)
    {
        return ($upVotes + self::PRETEND_UP) / ($upVotes + self::PRETEND_UP + $downVotes + self::PRETEND_DOWN);
    }

    /**
     *
     * @param ArticleViewModel $article
     *
     * @return float
     */
    private function getVotesScore(ArticleViewModel $article): float
    {
        return 20 * self::bayesianAverage($article->getVotesUp(), $article->getVotesDown());
    }

    /**
     * 1 + k * ( 1 - e^-(x/j) )
     *
     * @param ArticleViewModel $article
     * @param float            $k
     * @param int              $j
     *
     * @return float
     */
    private function getViewsScore(ArticleViewModel $article, float $k, int $j): float
    {
        return 1 + $k * (1 - M_E ** -($article->getViewsCount() / $j));
    }

    /**
     * 1 + k * ( 1 - e^-(x/j) )
     *
     * @param ArticleViewModel $article
     * @param float            $k
     * @param int              $j
     *
     * @return float
     */
    private function getCommentsScore(ArticleViewModel $article, float $k, int $j): float
    {
        return 1 + $k * (1 - M_E ** -($article->getCommentsCount() / $j));
    }

    /**
     * @param ArticleViewModel $article
     *
     * @return float
     */
    private function getEditorRatingScore(ArticleViewModel $article): float
    {
        return (float)$article->getEditorRating();
    }

    /**
     * @param ArticleViewModel $article
     *
     * @return float
     */
    private function getIsPrimapaginaScore(ArticleViewModel $article): float
    {
        return (float)$article->isPrimapagina();
    }

    /**
     * @param ArticleViewModel $article
     *
     * @return float
     */
    private function getImageScore(ArticleViewModel $article): float
    {
        return (float)$article->hasImage();
    }

    /**
     * e^ -(x/lambda)
     *
     * lambda = exponential decay constant
     *
     * @param ArticleViewModel $article
     * @param int              $lambda
     *
     * @return float
     */
    private function getDateTimeScore(ArticleViewModel $article, int $lambda = 86400): float
    {
        $interval = (new \DateTime())->getTimestamp() - $article->howOldIsDateTime()->getTimestamp();

//        return exp(-$interval / $lambda);

        return 1 + (($interval / $lambda) ** 2);

        return 1 / (1 - M_E ** -($interval / $lambda));

        return 1 - M_E ** ($interval / $lambda);

        return -($interval / $lambda) ** 2;
    }
}
