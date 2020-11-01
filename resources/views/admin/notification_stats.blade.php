<div id="__ajax_title" style="display: none">Статистика - Браузерные уведомления</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-lg">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Подписано
                                </h4>
                                <span class="kt-widget24__desc">
					            Общее количество
					        </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-primary">
                                {{\DB::table('users')->where('notify_bonus', 1)->count()}}
                            </span>
                        </div>

                        <div class="progress progress--sm">
                            <div class="progress-bar kt-bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>