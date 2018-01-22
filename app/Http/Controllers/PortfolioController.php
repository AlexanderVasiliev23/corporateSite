<?php

namespace Corp\Http\Controllers;

use Corp\Menu;
use Illuminate\Http\Request;
use Corp\Repositories\Repository;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\PortfoliosRepository;

class PortfolioController extends SiteController
{
    /**
     * PortfolioController constructor.
     * @param PortfoliosRepository $p_rep
     */
    public function __construct(PortfoliosRepository $p_rep)
    {
        parent::__construct(new MenusRepository(new Menu()));

        $this->p_rep = $p_rep;

        $this->template = env('THEME') . '.portfolios';
    }

    /**
     * @return $this
     * @throws \Throwable
     */
    public function index()
    {
        $this->title = 'Портфолио';
        $this->keywords = 'Портфолио keywords';
        $this->meta_description = 'Портфолио meta_desc';

        $portfolios = $this->getPortfolios();

        $content = view(env('THEME') . '.portfolios_content')->with('portfolios', $portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    /**
     * @param $alias
     * @return $this
     * @throws \Throwable
     */
    public function show($alias)
    {
        $portfolio = $this->p_rep->one($alias);

        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->meta_description = $portfolio->meta_desc;

        $portfolios = $this->getPortfolios(config('settings.other_portfolios'), false);

        $content = view(env('THEME') . '.portfolio_content')->with([
            'portfolio'     => $portfolio,
            'portfolios'    => $portfolios
        ])->render();
        $this->vars = array_add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    public function getPortfolios($take = false, $paginate = true)
    {
        $portfolios = $this->p_rep->get('*', $take, $paginate);

        if($portfolios) {
            $portfolios->load('filter');
        }

        return $portfolios;
    }
}
