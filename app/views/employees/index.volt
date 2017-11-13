<section class="charts">
    <div class="container-fluid">
        <header class="clearfix"> 
            <h1 class="h3 float-left">Служители</h1>
            <a href="{{ url('employees/create') }}" class="btn btn-primary float-right">
                <i class="icon ion-android-person-add"></i>Добави служител
            </a>
        </header>
        <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-block">
                    {% for key, user in page.items %}
                    {% if loop.first %}
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Име</th>
                                <th>Фамилия</th>
                                <th>Длъжност</th>
                                <th>Операции</th>
                            </tr>
                        </thead>
                        <tbody>
                        {% endif %}
                        {% if userId is defined %}
                            {% set selectedRow = userId %}
                        {% else %}
                            {% set selectedRow = '' %}
                        {% endif %}
                            <tr {{ selectedRow == key + 1 ? 'class="table-success"' : '' }}>
                                {% if user.active == 1 %}
                                    <td scope="row"><span class="circle active-user">{{ (page.current - 1) * 10 + key + 1 }}</span></td>
                                {% else %}
                                    <td scope="row"><span class="circle inactive-user">{{ (page.current - 1) * 10 + key + 1 }}</span></td>
                                {% endif %}
                                <td>{{ user.first_name }}</td>
                                <td>{{ user.last_name }}</td>
                                <td>{{ userTypes[user.type] }}</td>
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
                                                <h4 class="name"></h4>
                                                <p class="name"></p>
                                                <p class="name"></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
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
                                            <li class="page-item">
                                                {{ link_to("employees/index?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                            </li>
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
                                            <li class="page-item">
                                                {{ link_to("employees/index?page=" ~ page.next, 'Следваща', "class": "page-link") }}
                                            </li>
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
    
    
    
