<div class="col-xs-12 index-carousel">
    <div class="carousel sport-carousel">
        <div class="carousel-slide">
            <div class="carousel-background" style="background-image: url(/storage/img/field.jpg);"></div>
            <div class="carousel-content" data-slide-fragment="true" data-watch-fragment="/sport/fragment/slider.soccer"></div>
        </div>
        <div class="carousel-slide">
            <div class="carousel-background" style="background-image: url(/storage/img/tennis.jpg)"></div>
            <!--<div class="carousel-content" data-slide-fragment="true" data-watch-fragment="/sport/fragment/slider.tennis"></div>-->
        </div>
        <div class="carousel-slide">
            <div class="carousel-background" style="background-image: url(/storage/img/basketball.jpg)"></div>
        </div>
        <div class="carousel-slide">
            <div class="carousel-background" style="background-image: url(/storage/img/hockey.jpg)"></div>
        </div>
        <div class="carousel-slide">
            <div class="carousel-background" style="background-image: url(/storage/img/volleyball.jpg)"></div>
        </div>
        <div class="carousel-slide">
            <div class="carousel-background" style="background-image: url(/storage/img/table_tennis.jpg)"></div>
        </div>
        <div class="carousel-slide">
            <div class="carousel-background" style="background-image: url(/storage/img/baseball.jpg)"></div>
        </div>
    </div>
</div>

<div class="sport-bet-tabs">
    <div class="sport-bet-tab sport-bet-tab-active sport-live">
        <span><i class="fal fa-futbol"></i> Футбол</span>
        <div class="sport-bet-tab-indicator"></div>
    </div>
</div>

<div class="sport-live-tab-content" id="soccer-tab" data-watch-fragment="/sport/fragment/live.soccer"></div>

<script type="text/javascript" src="{{ asset('/js/vendor/jquery.mousewheel.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor/mwheelIntent.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/vendor/jquery.jscrollpage.min.js') }}"></script>
<script type="text/javascript" src="{{ $asset('/game/sport.js', 'js') }}"></script>