<?php

namespace Corp\Http\Controllers;

use Config;
use Corp\Menu;
use Corp\Repositories\ArticlesRepository;
use Illuminate\Http\Request;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\SlidersRepository;
use Corp\Repositories\PortfoliosRepository;

class IndexController extends SiteController
{
    /**
     * IndexController constructor.
     * @param SlidersRepository $s_rep
     * @param PortfoliosRepository $p_rep
     * @param ArticlesRepository $a_rep
     */
    public function __construct(SlidersRepository $s_rep, PortfoliosRepository $p_rep, ArticlesRepository $a_rep)
    {
        parent::__construct(new MenusRepository(new Menu));

        $this->p_rep = $p_rep;
        $this->s_rep = $s_rep;
        $this->a_rep = $a_rep;

        $this->bar = 'right';
        $this->template = env('THEME') . '.index';
    }

    /**
     * @return $this
     * @throws \Throwable
     */
    public function index()
    {
        $this->keywords = 'Home Page keywords';
        $this->meta_description = 'Home Page description';
        $this->title = 'Home Page title';

        $portfolios = $this->getPortfolio();
        $content = view(env('THEME') . '.content')->with('portfolios', $portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $sliderItems = $this->getSliders();
        $sliders = view(env('THEME') . '.slider')->with('sliders', $sliderItems)->render();
        $this->vars = array_add($this->vars, 'sliders', $sliders);

        $articles = $this->getArticles();
        $this->contentRightBar = view(env('THEME') . '.indexBar')->with('articles', $articles)->render();

        return $this->renderOutput();
    }

    /**
     * @return mixed
     */
    protected function getPortfolio()
    {
        $portfolio = $this->p_rep->get('*', Config::get('settings.home_port_count'));

        return $portfolio;
    }

    /**
     * @return mixed
     */
    protected function getArticles()
    {
        $articles = $this->a_rep->get([
            'title',
            'created_at',
            'img',
            'alias'
        ], Config::get('settings.home_articles_count'));

        return $articles;
    }

    /**
     * @return bool|mixed
     */
    public function getSliders()
    {
        $sliders = $this->s_rep->get();

        if($sliders->isEmpty()) {
            return false;
        }

        $sliders->transform(function ($item, $key) {
            $item->img = Config::get('settings.slider_path') . '/' . $item->img;
            return $item;
        });

        return $sliders;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
