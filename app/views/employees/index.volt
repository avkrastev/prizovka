<section class="charts">
        <div class="container-fluid">
            <header class="clearfix"> 
                <h1 class="h3 float-left">Служители</h1>
                <span class="glyphicon glyphicon-envelope"></span>
                {{ link_to("employees/create", "Добави служител", "class": "btn btn-primary float-right") }}
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
                                    <th>Права</th>
                                    <th>Операции</th>
                                </tr>
                            </thead>
                            <tbody>
                            {% endif %}
                                    <tr>
                                        {% if user.active == 1 %}
                                            <td scope="row"><span class="circle active-user">{{ (page.current - 1) * 10 + key + 1 }}</span></td>
                                        {% else %}
                                            <td scope="row"><span class="circle inactive-user">{{ (page.current - 1) * 10 + key + 1 }}</span></td>
                                        {% endif %}
                                        <td>{{ user.first_name }}</td>
                                        <td>{{ user.last_name }}</td>
                                        <td>{{ user.type }}</td>
                                        <td></td>
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
    
    
    
    

