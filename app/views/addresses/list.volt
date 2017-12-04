<section class="charts">
        <div class="container-fluid">
            <header class="clearfix"> 
                <h1 class="h3 float-left">Адреси</h1>
                <a href="{{ url('employees/create') }}" class="btn btn-primary float-right">
                    <i class="icon ion-android-person-add"></i>Добави служител
                </a>
            </header>
            <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        {% for key, address in page.items %}
                        {% if loop.first %}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Номер на дело</th>
                                    <th>Дата</th>
                                    <th>Зачислена на</th>
                                    <th>Адрес</th>
                                    <th>Операции</th>
                                </tr>
                            </thead>
                            <tbody>
                            {% endif %}
                                <tr>
                                    <td>{{ address.case_number }}</td>
                                    <td>{{ address.date }}</td>
                                    <td>{{ address.assigned_to }}</td>
                                    <td>{{ address.address }}</td>
                                    <td class="operations">
                                        {{ link_to("employees/edit/" ~ user.id, '<i class="icon ion-edit"></i>', "title": "Редакция") }}
                                        <a href="#" class="viewUser" user-id="{{ user.id }}" data-toggle="modal" data-target="#viewUserModal" title="Преглед"><i class="icon ion-eye"></i></a>
                                        <!-- Modal-->
                                        <div id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserLabel" aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 id="viewUserLabel" class="modal-title">Преглед на потребител</h5>
                                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4 class="name"></h4><br>
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label">Длъжност:</label>
                                                        <div class="col-sm-8">
                                                            <p class="form-control-static type"></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label">Електронна поща:</label>
                                                        <div class="col-sm-8">
                                                            <p class="form-control-static email"></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label">Статус на профила:</label>
                                                        <div class="col-sm-8">
                                                            <p class="form-control-static active"></p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="serviceFields">
                                                        <div class="row">
                                                            <label class="col-sm-4 col-form-label">Обновен от:</label>
                                                            <div class="col-sm-8">
                                                                <p class="form-control-static updated_by"></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-sm-4 col-form-label">Обновен на:</label>
                                                            <div class="col-sm-8">
                                                                <p class="form-control-static updated_at"></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-sm-4 col-form-label">Създаден от:</label>
                                                            <div class="col-sm-8">
                                                                <p class="form-control-static created_by"></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-sm-4 col-form-label">Създаден на:</label>
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
                                        {{ link_to("employees/delete/" ~ user.id, '<i class="icon ion-android-delete"></i>', "title": "Изтриване") }}
                                    </td>
                                </tr>
                            {% if loop.last %}
                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="7" align="right">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-center">
                                                {% if page.before == page.current %}
                                                    <li class="page-item disabled">
                                                        {{ link_to("employees/index?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                                    </li>
                                                {% else %}
                                                    <li class="page-item">
                                                        {{ link_to("employees/index?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                                    </li>
                                                {% endif %}
                                                {% for i in 1..page.total_pages %}
                                                    {% if i == page.current %}
                                                        <li class="page-item active">
                                                            {{ link_to("employees/index?page=" ~ i, i, "class": "page-link") }}
                                                        </li>
                                                    {% else %}
                                                        <li class="page-item">
                                                            {{ link_to("employees/index?page=" ~ i, i, "class": "page-link") }}
                                                        </li>
                                                    {% endif %}
                                                {% endfor %}
                                                {% if page.next == page.current %}
                                                    <li class="page-item disabled">
                                                        {{ link_to("employees/index?page=" ~ page.next, 'Следваща', "class": "page-link") }}
                                                    </li>
                                                {% else %}
                                                    <li class="page-item">
                                                        {{ link_to("employees/index?page=" ~ page.next, 'Следваща', "class": "page-link") }}
                                                    </li>
                                                {% endif %}
                                            </ul>
                                        </nav>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {% endif %}
                        {% else %}
                            Няма намерени служители
                        {% endfor  %}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
        
        
        
    
    