<section class="charts">
        <div class="container-fluid">
            <header class="clearfix"> 
                <h1 class="h3 float-left">История на раздадените призовки</h1>
                <a href="{{ url('history') }}" class="btn btn-primary float-right">
                    <i class="icon ion-search"></i>Ново търсене
                </a>
            </header>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">                
                        <div class="card-block">
                            {% for key, history in page.items %}
                            {% if loop.first %}
                            <table class="table history">
                                <thead>
                                    <tr>
                                        <th>Номер на дело</th>
                                        <th>Изходящ номер</th>
                                        <th>Адрес</th>
                                        <th>Служител</th>
                                        <th>Дата</th>
                                        <th>Операции</th>
                                    </tr>
                                </thead>
                                <tbody>
                                {% endif %}                       
                                    <tr addressId="{{ history.a.id }}" {{ request.getQuery('addressid') == history.a.id ? 'class="activeRow"' : '' }}>
                                        <td># {{ history.a.case_number }}</td>
                                        <td>{{ history.a.reference_number }}</td>
                                        <td class="address">
                                            <a href="#" class="viewAddress" data-toggle="modal" data-target="#viewAddressModal">{{ history.a.address }}</a>
                                        </td>
                                        <td>{{ history.s.getAssigned_to().first_name ~' '~ history.s.getAssigned_to().last_name}}</td>
                                        <td>{{ date('d.m.Y', strtotime(history.s.date)) }} г.</td>
                                        <td class="operations">
                                            {{ link_to("subpoenas/details/" ~ history.a.id ~'/history', '<i class="icon ion-clipboard"></i>', "title": "Преглед") }}
                                        </td>
                                    </tr>
                                {% if loop.last %}
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination justify-content-center">
                                    {% if page.before == page.current %}
                                        <li class="page-item disabled">
                                            {{ link_to("history/index?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                        </li>
                                    {% else %}
                                        <li class="page-item">
                                            {{ link_to("history/index?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                        </li>
                                    {% endif %}
                                    {% for i in 1..page.total_pages %}
                                        {% if i == page.current %}
                                            <li class="page-item active">
                                                {{ link_to("history/index?page=" ~ i, i, "class": "page-link") }}
                                            </li>
                                        {% else %}
                                            <li class="page-item">
                                                {{ link_to("history/index?page=" ~ i, i, "class": "page-link") }}
                                            </li>
                                        {% endif %}
                                    {% endfor %}
                                    {% if page.next == page.current %}
                                        <li class="page-item disabled">
                                            {{ link_to("history/index?page=" ~ page.next, 'Следваща', "class": "page-link") }}
                                        </li>
                                    {% else %}
                                        <li class="page-item">
                                            {{ link_to("history/index?page=" ~ page.next, 'Следваща', "class": "page-link") }}
                                        </li>
                                    {% endif %}
                                </ul>
                            </nav>
                            {% endif %}
                            {% else %}
                                Няма намерени призовки
                            {% endfor  %}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal-->
        <div id="viewAddressModal" tabindex="-1" role="dialog" aria-labelledby="viewAddressLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="viewAddressLabel" class="modal-title">Детайли за призовката</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div id="map2"></div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label">Адрес:</label>
                        <div class="col-sm-8">
                            <p class="form-control-static address"></p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label">Зачислена на:</label>
                        <div class="col-sm-8">
                            <p class="form-control-static assigned_to"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="serviceFields">
                        <div class="row">
                            <label class="col-sm-4 col-form-label">Обновена от:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static updated_by"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label">Обновена на:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static updated_at"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label">Създадена от:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static created_by"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label">Създадена на:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static created_at"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Затвори</button>
                </div>
            </div>
            </div>
        </div>
    </section>
        
            
            
        
        