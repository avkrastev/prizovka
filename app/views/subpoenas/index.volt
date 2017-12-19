<section class="charts">
    <div class="container-fluid">
        <header class="clearfix"> 
            <h1 class="h3 float-left">Призовки</h1>
            <a href="{{ url('addresses') }}" class="btn btn-primary float-right">
                <i class="icon ion-ios-location"></i>Добави адрес
            </a>
        </header>
        <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-block">
                    {% for key, address in page.items %}
                    {% if loop.first %}
                    <table class="table table-hover subpoenas">
                        <thead>
                            <tr>
                                <th>Номер на дело</th>
                                <th>Изходящ номер</th>
                                <th>Адрес</th>
                                <th>Операции</th>
                            </tr>
                        </thead>
                        <tbody>
                        {% endif %}                       
                            <tr addressId="{{ address.id }}" {{ request.getQuery('addressid') == address.id ? 'class="activeRow"' : '' }} title="Редактирай призовката">
                                <td># {{ address.case_number }}</td>
                                <td>{{ address.reference_number }}</td>
                                <td class="address">
                                    <a href="#" class="viewAddress" data-toggle="modal" data-target="#viewAddressModal">{{ address.address }}</a>
                                </td>
                                <td class="operations">
                                    {{ link_to("subpoenas/edit/" ~ address.id, '<i class="icon ion-edit"></i>', "title": "Редакция") }}
                                    {{ link_to("subpoenas/details/" ~ address.id, '<i class="icon ion-eye"></i>', "title": "Преглед") }}
                                </td>
                            </tr>
                        {% if loop.last %}
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination justify-content-center">
                            {% if page.before == page.current %}
                                <li class="page-item disabled">
                                    {{ link_to("subpoenas/index?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                </li>
                            {% else %}
                                <li class="page-item">
                                    {{ link_to("subpoenas/index?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                </li>
                            {% endif %}
                            {% for i in 1..page.total_pages %}
                                {% if i == page.current %}
                                    <li class="page-item active">
                                        {{ link_to("subpoenas/index?page=" ~ i, i, "class": "page-link") }}
                                    </li>
                                {% else %}
                                    <li class="page-item">
                                        {{ link_to("subpoenas/index?page=" ~ i, i, "class": "page-link") }}
                                    </li>
                                {% endif %}
                            {% endfor %}
                            {% if page.next == page.current %}
                                <li class="page-item disabled">
                                    {{ link_to("subpoenas/index?page=" ~ page.next, 'Следваща', "class": "page-link") }}
                                </li>
                            {% else %}
                                <li class="page-item">
                                    {{ link_to("subpoenas/index?page=" ~ page.next, 'Следваща', "class": "page-link") }}
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
    
        
        
    
    