<section class="charts">
    <div class="container-fluid">
        <header class="clearfix"> 
            <h1 class="h3 float-left">Детайли за призовка по Дело {{ subpoena[0].getAddress().case_number }} и Изходящ номер: {{ subpoena[0].getAddress().reference_number }}</h1>
            <a href="{{ url('subpoenas') }}" class="btn btn-primary float-right">
                <i class="icon ion-chevron-left"></i>Обратно към списъка с призовки
            </a>
        </header>
        <div id="subpoenaMap" lat="{{ subpoena[0].getAddress().latitude }}" lng="{{ subpoena[0].getAddress().longitude }} "></div>
        <h5>{{ subpoena[0].getAddress().address }}</h5>
        <div class="row">        
        <div class="col-lg-12">
            <div class="card">
                <div class="card-block">
                    {% for key, subpoena in page.items %}
                    {% if loop.first %}
                    <table class="table subpoenas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Зачислена на</th>
                                <th>Дата</th>
                                <th>Час</th>
                                <th>Действие</th>       
                            </tr>
                        </thead>
                        <tbody>
                        {% endif %}                       
                            <tr>
                                <td scope="row"><span class="circle active-user">{{ (page.current - 1) * 5 + key + 1 }}</span></td>
                                <td>{{ subpoena.getAssigned_to().first_name ~' '~ subpoena.getAssigned_to().last_name }}</td>
                                <td>{{ date('d.m.Y', strtotime(subpoena.date)) ~' /'~ daysOfWeek[date('w', strtotime(subpoena.date))] ~'/' }}</td>
                                <td>{{ date('H:i', strtotime(subpoena.date)) }}</td>
                                <td>{{ actions[subpoena.action] }}</td>
                            </tr>
                        {% if loop.last %}
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination justify-content-center">
                            {% if page.before == page.current %}
                                <li class="page-item disabled">
                                    {{ link_to("subpoenas/details/" ~ subpoena.address ~ "?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                </li>
                            {% else %}
                                <li class="page-item">
                                    {{ link_to("subpoenas/details/" ~ subpoena.address ~ "?page=" ~ page.before, 'Предишна', "class": "page-link") }}
                                </li>
                            {% endif %}
                            {% for i in 1..page.total_pages %}
                                {% if i == page.current %}
                                    <li class="page-item active">
                                        {{ link_to("subpoenas/details/" ~ subpoena.address ~ "?page=" ~ i, i, "class": "page-link") }}
                                    </li>
                                {% else %}
                                    <li class="page-item">
                                        {{ link_to("subpoenas/details/" ~ subpoena.address ~ "?page=" ~ i, i, "class": "page-link") }}
                                    </li>
                                {% endif %}
                            {% endfor %}
                            {% if page.next == page.current %}
                                <li class="page-item disabled">
                                    {{ link_to("subpoenas/details/" ~ subpoena.address ~ "?page=" ~ page.next, 'Следваща', "class": "page-link") }}
                                </li>
                            {% else %}
                                <li class="page-item">
                                    {{ link_to("subpoenas/details/" ~ subpoena.address ~ "?page=" ~ page.next, 'Следваща', "class": "page-link") }}
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
</section>
    
        
        
    
    