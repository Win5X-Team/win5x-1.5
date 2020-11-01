<div id="__ajax_title" style="display: none">Кейсы</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid row">
    @foreach(\App\Box::get() as $box)
        <div class="col-xl-4">
            <div class="kt-portlet kt-portlet--height-fluid" id="box{{$box->id}}">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-widget kt-widget--project-1">
                        <div class="kt-widget__head">
                            <div class="kt-widget__label">
                                <div class="kt-widget__media">
                                    <span class="kt-media kt-media--lg kt-media--circle task-ico">
                                        <i class="fad fa-box-open"></i>
                                    </span>
                                </div>
                                <div class="kt-widget__info kt-margin-t-5">
                                    <a href="#" class="kt-widget__title">
                                        {{$box->name}}
                                    </a>
                                </div>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                    <i class="flaticon-more-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__item">
                                            <a href="javascript:void(0)" onclick="cid = {{$box->id}};" data-toggle="modal" data-target="#add" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-add"></i>
                                                <span class="kt-nav__link-text">Добавить предмет</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="javascript:void(0)" onclick="send('#box{{$box->id}}', '/admin/case/remove/{{$box->id}}', function() {window.location.reload()})" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-trash"></i>
                                                <span class="kt-nav__link-text">Удалить</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__body">
                            <div class="kt-widget__content">
                                <div class="kt-widget__details">

                                    <span class="kt-widget__subtitle">Цена</span>
                                    <span class="kt-widget__value">{{$box->price}} <span>руб.</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Кейс</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">Название:</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="form-group">
                    <label class="form-control-label">Цена:</label>
                    <input type="text" class="form-control" id="price">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="create">Создать</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Новый предмет</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">type id:</label>
                    <input type="text" class="form-control" id="type">
                </div>
                <div class="form-group">
                    <label class="form-control-label">value:</label>
                    <input type="text" class="form-control" id="value">
                </div>
                <div class="form-group">
                    <label class="form-control-label">chance:</label>
                    <input type="text" class="form-control" id="chance">
                </div>
                <div class="form-group">
                    <label class="form-control-label">rarity:</label>
                    <input type="text" class="form-control" id="rarity">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="i_create">Создать</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/_admin/js/cases.js?v={{$version}}"></script>