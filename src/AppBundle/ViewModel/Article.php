<?php

namespace AppBundle\ViewModel;

/**
 * Class Article
 *
 * @package AppBundle\ViewModel
 */
class Article
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $image;
    /**
     * @var string
     */
    protected $howOldIs;
    /**
     * @var \DateTime
     */
    private $howOldIsDateTime;
    /**
     * @var int
     */
    private $votesUp;
    /**
     * @var int
     */
    private $votesDown;
    /**
     * @var int
     */
    private $viewsCount;
    /**
     * @var int
     */
    private $commentsCount;
    /**
     * @var bool
     */
    private $isPrimapagina;
    /**
     * @var float
     */
    private $editorRating;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        return (bool)$this->image;
    }

    /**
     * @return string
     */
    public function howOldIs()
    {
        return $this->howOldIs;
    }

    /**
     * @return \DateTime
     */
    public function howOldIsDateTime()
    {
        return $this->howOldIsDateTime;
    }

    /**
     * @return int
     */
    public function getVotesUp()
    {
        return $this->votesUp;
    }

    /**
     * @return int
     */
    public function getVotesDown()
    {
        return $this->votesDown;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * @return bool
     */
    public function isPrimapagina()
    {
        return $this->isPrimapagina;
    }

    /**
     * @return float
     */
    public function getEditorRating()
    {
        return $this->editorRating;
    }

    /**
     * @param array $data
     *
     * @return Article
     */
    public static function create(array $data)
    {
        $article = new self();

        $article->id               = $data['id'];
        $article->image            = $data['image'];
        $article->howOldIs         = $data['howOldIs'];
        $article->howOldIsDateTime = new \DateTime($data['howOldIs']);
        $article->votesUp          = $data['votesUp'];
        $article->votesDown        = $data['votesDown'];
        $article->viewsCount       = $data['viewsCount'];
        $article->commentsCount    = $data['commentsCount'];
        $article->isPrimapagina    = $data['isPrimapagina'];
        $article->editorRating     = $data['editorRating'];

        return $article;
    }


    /**
     * @todo TO REMOVE
     */
    public $votesScore;

    public $viewsScore;

    public $commentsScore;

    public $editorScore;

    public $dateTimeScore;

    public $isPrimapaginaScore;

    public $imageScore;

    public $score;

    /**
     * @param mixed $getVotesScore
     */
    public function setVotesScore($getVotesScore)
    {
        $this->votesScore = $getVotesScore;
    }

    /**
     * @param mixed $getViewsScore
     */
    public function setViewsScore($getViewsScore)
    {
        $this->viewsScore = $getViewsScore;
    }

    /**
     * @param mixed $getCommentsScore
     */
    public function setCommentsScore($getCommentsScore)
    {
        $this->commentsScore = $getCommentsScore;
    }

    /**
     * @param mixed $getEditorScore
     */
    public function setEditorRatingScore($getEditorScore)
    {
        $this->editorScore = $getEditorScore;
    }

    /**
     * @param mixed $geDateTimeScore
     */
    public function setDateTimeScore($geDateTimeScore)
    {
        $this->dateTimeScore = $geDateTimeScore;
    }

    /**
     * @param mixed $isPrimapaginaScore
     */
    public function setIsPrimapaginaScore($isPrimapaginaScore)
    {
        $this->isPrimapaginaScore = $isPrimapaginaScore;
    }

    /**
     * @param mixed $getImageScore
     */
    public function setImageScore($getImageScore)
    {
        $this->imageScore = $getImageScore;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }
}