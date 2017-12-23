<div data-role="page" id="demo-page" data-title="Призовка.бг" data-url="demo-page">
    <div data-role="header" data-position="fixed" data-theme="b">
        <h1>Призовкар.бг</h1>
        <a href="#" data-rel="back" class="initials">
            <strong><?php echo mb_substr($user->first_name, 0, 1); ?></strong>
            <strong class="text-primary"><?php echo mb_substr($user->last_name, 0, 1); ?></strong>
        </a>
        <a href="{{ url('app/logout') }}" data-icon="back" data-iconpos="notext">Излез</a>
    </div><!-- /header -->
    <div data-role="navbar">
            <ul>
                <li><a href="{{ url('app/index') }}" class="ui-btn-active">Призовки</a></li>
                <li><a href="{{ url('app/routes') }}">Маршрут</a></li>
                <li><a href="{{ url('app/scan') }}">Сканиране</a></li>
            </ul>
        </div><!-- /navbar -->
    <div role="main" class="ui-content">
        <ul id="list" class="touch" data-role="listview" data-icon="false" data-split-icon="delete" data-filter="true" data-filter-placeholder="Търсене по адрес">
            {% for key, address in addresses %}
                <li subpoena="{{ address.a.id }}">
                    <a href="#" class="address" lat="{{ address.a.latitude }}" lng="{{ address.a.longitude }}">
                        <h3 class="topic">{{ address.a.address }}</h3>
                        <p><strong>Номер на делото: {{ address.a.case_number }}</strong></p>
                        <p>Изходящ номер: {{ address.a.reference_number }}</p>
                        <p class="ui-li-aside"><strong>{{ date('d.m.y', strtotime(address.s.date))}}</strong>г.</p>
                    </a>
                    <a href="#" class="delete">Delete</a>
                </li>
            {% endfor %}
        </ul>
    </div><!-- /content -->
    <div id="confirm" class="ui-content" data-role="popup" data-theme="a">
        <p id="question">Сигурни ли сте, че искате да отбележите адреса като връчен?</p>
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <a id="yes" class="ui-btn ui-corner-all ui-mini ui-btn-a" data-rel="back">Да</a>
            </div>
            <div class="ui-block-b">
                <a id="cancel" class="ui-btn ui-corner-all ui-mini ui-btn-a" data-rel="back">Не</a>
            </div>
        </div>
    </div><!-- /popup -->
    <div id="mapDialog" class="ui-content" data-role="popup" data-theme="a"> 
        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Затвори</a>
        <p id="address"></p>
        <div id="map"></div>
    </div><!-- /popup -->
</div>