<section class="charts subpoenas">
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
                        {{ form("subpoenas/search", "class": "form-inline") }}
                            {% for element in form %}
                                {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
                                    {{ element }}
                                {% else %}
                                <div class="form-group">
                                    {{ element.render(['class': 'mx-sm-3 form-control']) }}
                                </div>
                                {% endif %}
                            {% endfor %}
                            <div class="form-group">
                                {{ submit_button("Търсене", "class": "mx-sm-3 btn btn-primary") }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">                
                    <div class="card-block">
                        {% for key, address in page.items %}
                        {% if loop.first %}
                        <table class="table subpoenas">
                            <thead>
                                <tr>
                                    {% if order['case_number'] is defined %}
                                        {% if order['case_number'] == 'asc' %}
                                            <th>{{ link_to("subpoenas/search?order=case_number&direction=" ~ order['case_number'], 'Номер на дело <i class="icon ion-chevron-up"></i>', "class": "") }}</th>
                                        {% else %}
                                            <th>{{ link_to("subpoenas/search?order=case_number&direction=" ~ order['case_number'], 'Номер на дело <i class="icon ion-chevron-down"></i>', "class": "") }}</th>
                                        {% endif %}
                                    {% else %}
                                        <th>{{ link_to("subpoenas/search?order=case_number&direction=asc", 'Номер на дело', "class": "") }}</th>
                                    {% endif %}
                                    {% if order['reference_number'] is defined %}
                                        {% if order['reference_number'] == 'asc' %}
                                            <th>{{ link_to("subpoenas/search?order=reference_number&direction=" ~ order['reference_number'], 'Изходящ номер <i class="icon ion-chevron-up"></i>', "class": "") }}</th>
                                        {% else %}
                                            <th>{{ link_to("subpoenas/search?order=reference_number&direction=" ~ order['reference_number'], 'Изходящ номер <i class="icon ion-chevron-down"></i>', "class": "") }}</th>
                                        {% endif %}
                                    {% else %}
                                        <th>{{ link_to("subpoenas/search?order=reference_number&direction=asc", 'Изходящ номер', "class": "") }}</th>
                                    {% endif %}
                                    {% if order['address'] is defined %}
                                        {% if order['address'] == 'asc' %}
                                            <th>{{ link_to("subpoenas/search?order=address&direction=" ~ order['address'], 'Адрес <i class="icon ion-chevron-up"></i>', "class": "") }}</th>
                                        {% else %}
                                            <th>{{ link_to("subpoenas/search?order=address&direction=" ~ order['address'], 'Адрес <i class="icon ion-chevron-down"></i>', "class": "") }}</th>
                                        {% endif %}
                                    {% else %}
                                        <th>{{ link_to("subpoenas/search?order=address&direction=asc", 'Адрес', "class": "") }}</th>
                                    {% endif %}
                                    <th>Зачислена</th>
                                    <th>Операции</th>
                                </tr>
                            </thead>
                            <tbody>
                            {% endif %}                       
                                <tr addressId="{{ address.a.id }}" {{ request.getQuery('addressid') == address.a.id ? 'class="activeRow"' : '' }}>
                                    <td># {{ address.a.case_number }}</td>
                                    <td>{{ address.a.reference_number }}</td>
                                    <td class="address">
                                        <a href="#" class="viewAddress" data-toggle="modal" data-target="#viewAddressModal">{{ address.a.address }}</a>
                                    </td>
                                    {% if (address.s.assigned_to != '') %}
                                        <td>Да</td>
                                    {% else %}
                                        <td>Не</td>
                                    {% endif %}
                                    <td class="operations">
                                        {{ link_to("subpoenas/edit/" ~ address.a.id, '<i class="icon ion-edit"></i>', "title": "Редакция") }}
                                        {{ link_to("subpoenas/details/" ~ address.a.id, '<i class="icon ion-clipboard"></i>', "title": "Преглед") }}
                                    </td>
                                </tr>
                            {% if loop.last %}
                            </tbody>
                        </table>
                        <nav id="pagination">
                            <ul class="pagination justify-content-center" page-current="{{ page.current }}" page-total="{{ page.total_pages }}"></ul>
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
    
        
        
    
    