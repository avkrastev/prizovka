<section class="charts stats">
    <div class="container-fluid">
        <header> 
            <h1 class="h3">Диаграми</h1>
        </header>
        <div class="row">
            <div class="col-lg-6">
                <div class="card pie-chart-example">
                    <div class="card-header d-flex align-items-center">
                        <h2 class="h5 display">Брой раздадени призовки от отделните служители (текущ м. {{ months[date('m')-1] }})</h2>
                    </div>
                    <div class="card-block">
                        <canvas id="subpoenasCountCurrentMonth"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card pie-chart-example">
                    <div class="card-header d-flex align-items-center">             
                        {% if (date('Y') > date('Y', strtotime("first day of previous month"))) %} 
                            {% set lastMonth = months[date('m', strtotime("first day of previous month"))-1] ~' '~ date('Y', strtotime("first day of previous month")) %}
                        {% else %}
                            {% set lastMonth = months[date('m')-2] %}
                        {% endif %}
                        <h2 class="h5 display">Брой раздадени призовки от отделните служители (предходен м. {{ lastMonth }})</h2>
                    </div>
                    <div class="card-block">
                        <canvas id="subpoenasCountPrevMonth"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card line-chart-example">
                <div class="card-header d-flex align-items-center">
                    <h2 class="h5 display">Общ брой раздадени призовки по месеци</h2>
                </div>
                <div class="card-block">
                    <canvas id="allDeliveredByMonths"></canvas>
                </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card bar-chart-example">
                <div class="card-header d-flex align-items-center">
                    <h2 class="h5 display">Брой връчени, посетени и невръчени адреси</h2>
                </div>
                <div class="card-block">
                    <canvas id="barChartExample"></canvas>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>