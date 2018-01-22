<div id="content-page" class="content group">
    <div class="clear"></div>
    <div class="posts">
        @if($portfolio)
        <div class="group portfolio-post internal-post">
            <div id="portfolio" class="portfolio-full-description">

                <div class="fulldescription_title gallery-filters">
                    <h1>{{ $portfolio->title }}</h1>
                </div>

                <div class="portfolios hentry work group">
                    <div class="work-thumbnail">
                        <a class="thumb"><img src="{{ asset(env('THEME')) }}/images/projects/{{ $portfolio->img->max }}" alt="0081" title="0081" /></a>
                    </div>
                    <div class="work-description">
                        <p>{{ $portfolio->text }}</p>
                        <div class="clear"></div>
                        <div class="work-skillsdate">
                            <p class="skills"><span class="label">{{ Lang::get('ru.skills') }}:</span> {{ $portfolio->filter->title }}</p>
                            <p class="workdate"><span class="label">{{ Lang::get('ru.customer') }}:</span> {{ $portfolio->customer }}</p>
                            <p class="workdate"><span class="label">{{ Lang::get('ru.year') }}:</span> {{ ($portfolio->created_at) ? $portfolio->created_at->format('Y') : '' }}</p>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                @endif

                <div class="clear"></div>

                <h3>{{ Lang::get('ru.other_projects') }}</h3>

                <div class="portfolio-full-description-related-projects">
                    @if($portfolios)
                        @foreach($portfolios as $portfolio)
                            <div class="related_project">
                                <a class="related_proj related_img" href="{{ route('portfolios.show', ['alias' => $portfolio->alias]) }}" title="{{ $portfolio->title }}"><img src="{{ asset(env('THEME')) }}/images/projects/{{ $portfolio->img->mini }}" alt="0061" title="0061" /></a>
                                <h4><a href="{{ route('portfolios.show', ['alias' => $portfolio->alias]) }} ">{{ $portfolio->title }}</a></h4>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
