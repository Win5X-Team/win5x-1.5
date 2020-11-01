<div id="__ajax_title" style="display: none">Промокоды</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
        <div class="kt-grid__item kt-grid__item--fluid kt-app__content row">
            @foreach(\App\Promocode::where('type', 0)->get() as $promo)
                <div class="col-xl-4">
                    <div class="kt-portlet kt-portlet--height-fluid" id="promo{{$promo->id}}">
                        <div class="kt-portlet__head--noborder">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title"></h3>
                            </div>
                            <div class="kt-portlet__head-toolbar" style="float: right; margin-right: 10px;">
                                <a href="javascript:void(0)" class="btn btn-icon" data-toggle="dropdown">
                                    <i class="flaticon-more-1 kt-font-brand"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__item">
                                            <a href="javascript:void(0)"
                                               onclick="$('#edit_id').val({{$promo->id}}); $('#edit_code').val('{{$promo->code}}'); $('#edit_usages').val('{{$promo->usages}}'); $('#edit_sum').val('{{$promo->sum}}');" data-toggle="modal" data-target="#pedit" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                <span class="kt-nav__link-text">Редактировать</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="javascript:void(0)" onclick="send('#promo{{$promo->id}}', '/admin/promo/remove/{{$promo->id}}', function() { $('#promo{{$promo->id}}').fadeOut('fast') })" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-trash"></i>
                                                <span class="kt-nav__link-text">Удалить</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget kt-widget--user-profile-2">
                                <div class="kt-widget__head">
                                    <div class="kt-widget__info" style="padding-left: 0!important;">
                                        <a href="javascript:void(0)" class="kt-widget__username">
                                            {{$promo->code}}
                                        </a>
                                        <span class="kt-widget__desc" style="font-size: 1.7rem">
                                        {{$promo->sum}} руб.
                                    </span>
                                    </div>
                                </div>
                                <div class="kt-widget__body">
                                    <div class="kt-widget__item">
                                        <div class="kt-widget__contact">
                                            <span class="kt-widget__label mt-2">Использований осталось:</span>
                                            <a href="#" class="kt-widget__data">{{$promo->usages}}</a>
                                        </div>
                                    </div>
                                    <div class="kt-widget__item">
                                        <div class="kt-widget__contact">
                                            <span class="kt-widget__label">Использовали:</span>
                                            @php($users = \App\User::whereRaw('JSON_CONTAINS(`gp_used`, \''.$promo->id.'\', \'$\')')->get())
                                            <div class="kt-media-group">
                                                @if(sizeof($users) == 0) Нет @endif
                                                @php($i = 0)
                                                @foreach($users as $key => $value)
                                                    <a href="javascript:void(0)" onclick="load('admin/user?id={{$value->id}}')" class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="{{$value->username}}">
                                                        <img src="{{$value->avatar}}" alt="image">
                                                    </a>
                                                    @if($i > 5)
                                                        <a href="javascript:void(0)" class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title=""
                                                           data-original-title="Выполнений: {{sizeof($users)}}">
                                                            <span>+{{sizeof($users) - 7}}</span>
                                                        </a>
                                                        @break
                                                    @endif
                                                    @php($i += 1)
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @php
        $temp = \App\Promocode::where('type', 1)->get();
        foreach($temp as $k => $t) {
            if($t->time + 86400 < time()) unset($temp[$k]);
        }
    @endphp
    @if(sizeof($temp) > 0)
        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
            <div class="kt-grid__item kt-grid__item--fluid kt-app__content row kt-todo__list">
                <div class="col-xl-12">
                    <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Временные промокоды
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="kt_widget2_tab1_content">
                                    <div class="kt-widget2">
                                        @foreach($temp as $promo)
                                            <div class="kt-widget2__item kt-widget2__item--success" id="promo{{$promo->id}}">
                                                <div class="kt-widget2__checkbox" >
                                                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                                        <input type="checkbox" {{$promo->tick == 1 ? 'checked="checked"' : ''}}>
                                                        <span onclick="send('#promo{{$promo->id}} .kt-widget2__checkbox', '/admin/promo/group/tick/{{$promo->id}}', function() {})"></span>
                                                    </label>
                                                </div>

                                                <div class="kt-widget2__info">
                                                    <span style="user-select: all" class="kt-widget2__title">
                                                        {{$promo->code}}
                                                    </span>
                                                    <a class="kt-widget2__username" style="font-family: 'Open Sans'">
                                                        Удалится:
                                                        @php
                                                            $date = new \DateTime('now', new \DateTimeZone('Etc/GMT-3'));
                                                            $date->setTimestamp($promo->time + 86400);
                                                            echo $date->format('d.m.Y H:i');
                                                        @endphp
                                                    </a>
                                                </div>
                                                <div class="kt-widget2__actions">
                                                    <a href="javascript:void(0)" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                                        <i class="flaticon-more-1"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                                        <ul class="kt-nav">
                                                            <li class="kt-nav__item">
                                                                <a href="javascript:void(0)"
                                                                   onclick="$('#g_edit_id').val({{$promo->id}}); $('#g_edit_name').val('{{$promo->code}}');" data-toggle="modal" data-target="#gedit" class="kt-nav__link">
                                                                    <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                                    <span class="kt-nav__link-text">Редактировать</span>
                                                                </a>
                                                            </li>
                                                            <li class="kt-nav__item">
                                                                <a href="javascript:void(0)" onclick="send('#promo{{$promo->id}}', '/admin/promo/remove/{{$promo->id}}', function() { $('#promo{{$promo->id}}').fadeOut('fast') })" class="kt-nav__link">
                                                                    <i class="kt-nav__link-icon flaticon2-trash"></i>
                                                                    <span class="kt-nav__link-text">Удалить</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<div class="modal fade" id="new_group" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Промокод</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">Количество промокодов:</label>
                    <input type="text" class="form-control" id="g_num">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="g_create">Создать</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Промокод</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">Промокод:</label>
                    <input type="text" class="form-control" id="code">
                    <span class="form-text text-muted"><a href="javascript:void(0)" onclick="$('#code').val((Math.random() +1).toString(36).substr(2, 6).toUpperCase())">Сгенерировать</a></span>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Количество использований:</label>
                    <input type="text" class="form-control" id="usages">
                </div>
                <div class="form-group">
                    <label class="form-control-label">Сумма:</label>
                    <input type="text" class="form-control" id="sum">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="create">Создать</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="gedit" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Промокод</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input style="display: none" type="hidden" value="-1" id="g_edit_id">
                <div class="form-group">
                    <label class="form-control-label">Промокод:</label>
                    <input type="text" class="form-control" id="g_edit_name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="g_edit">Редактировать</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pedit" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Промокод</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input style="display: none" type="hidden" value="-1" id="edit_id">
                <div class="form-group">
                    <label class="form-control-label">Промокод:</label>
                    <input type="text" class="form-control" id="edit_code" disabled="disabled">
                </div>
                <div class="form-group">
                    <label class="form-control-label">Количество использований:</label>
                    <input type="text" class="form-control" id="edit_usages">
                </div>
                <div class="form-group">
                    <label class="form-control-label">Сумма:</label>
                    <input type="text" class="form-control" id="edit_sum">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="edit">Редактировать</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/_admin/js/promo.js?v={{$version}}"></script>