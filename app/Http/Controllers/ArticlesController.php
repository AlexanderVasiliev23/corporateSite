<?php

namespace Corp\Http\Controllers;

use Corp\Menu;
use Corp\Comment;
use Corp\Category;
use Illuminate\Http\Request;
use League\Flysystem\Config;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\CommentsRepository;
use Corp\Repositories\PortfoliosRepository;

class ArticlesController extends SiteController
{
    /**
     * ArticlesController constructor.
     * @param ArticlesRepository $a_rep
     * @param PortfoliosRepository $p_rep
     * @param CommentsRepository $c_rep
     */
    public function __construct(ArticlesRepository $a_rep, PortfoliosRepository $p_rep, CommentsRepository $c_rep)
    {
        parent::__construct(new MenusRepository(new Menu));

        $this->a_rep = $a_rep;
        $this->p_rep = $p_rep;
        $this->c_rep = $c_rep;

        $this->bar = 'right';
        $this->template = env('THEME') . '.articles';
    }

    /**
     * @param bool $cat_alias
     * @return $this
     * @throws \Throwable
     */
    public function index($cat_alias = false)
    {
        $category = Category::where('alias', $cat_alias)->first(['title', 'keywords', 'meta_desk']);

        $this->title = $category->title;
        $this->keywords = $category->keywords;
        $this->meta_description = $category->meta_desk;

        $articles = $this->getArticles($cat_alias);
        $content = view(env('THEME') . '.articles_content')
            ->with('articles', $articles)
            ->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $comments = $this->getComments(config('settings.resent_comments'));
        $portfolios = $this->getPortfolios(config('settings.resent_portfolios'));

        $this->contentRightBar = view(env('THEME') . '.articlesBar')
            ->with([
                'comments'      => $comments,
                'portfolios'    => $portfolios
        ]);

        return $this->renderOutput();
    }

    /**
     * @param $alias
     * @return $this
     * @throws \Throwable
     */
    public function show($alias)
    {
        $article = $this->a_rep->one($alias, ['comments' => true]);

        if($article) {
            $article->img = json_decode($article->img);
        }

        $this->title = $article->title;
        $this->keywords = $article->keywords;
        $this->meta_description = $article->meta_desk;

        $content = view(env('THEME') . './article_content')
            ->with('article', $article)
            ->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $comments = $this->getComments(config('settings.resent_comments'));
        $portfolios = $this->getPortfolios(config('settings.resent_portfolios'));

        $this->contentRightBar = view(env('THEME') . '.articlesBar')
            ->with([
                'comments'      => $comments,
                'portfolios'    => $portfolios
            ]);

        return $this->renderOutput();
    }

    /**
     * @param $take
     * @return mixed
     */
    public function getComments($take)
    {
        $comments = $this->c_rep->get([
            'text',
            'name',
            'email',
            'site',
            'article_id',
            'user_id'
        ], $take);

        if($comments) {
            $comments->load('article', 'user');
        }

        return $comments;
    }

    /**
     * @param $take
     * @return bool
     */
    public function getPortfolios($take)
    {
        $portfolios = $this->p_rep->get([
            'title',
            'text',
            'alias',
            'customer',
            'img',
            'filter_alias'
        ], $take);

        return $portfolios;
    }

    /**
     * @param bool $alias
     * @return mixed
     */
    public function getArticles($alias = false)
    {
        $where = false;

        if($alias) {
            $id = Category::select('id')->where('alias', $alias)->first()->id;
            $where = ['category_id', $id];
        }

        $articles = $this->a_rep->get(['title', 'alias', 'created_at', 'img', 'desc', 'user_id', 'category_id', 'id'], false,true, $where);

        if($articles) {
            $articles->load('user', 'category', 'comments');
        }

        return $articles;
    }

}
