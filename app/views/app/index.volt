{% if error is defined %}
    {% set errorMsg = error %}
{% else %}
    {% set errorMsg = '' %}
{% endif %}
<div data-role="page" id="index-page" data-title="Призовка.бг" data-url="index" data-error="{{ errorMsg }}">
    <div data-role="header" data-position="fixed" data-theme="b">
        <h1>Призовкар.бг</h1>
        <a href="#" data-rel="back" class="initials">
            <strong><?php echo mb_substr($user->first_name, 0, 1); ?></strong>
            <strong class="text-primary"><?php echo mb_substr($user->last_name, 0, 1); ?></strong>
        </a>
        <a href="#" data-url="logout" data-icon="action" data-iconpos="notext">Излез</a>
        <div data-role="navbar" data-position="fixed">
            <ul>
                <li><a href="#" data-url="index" class="ui-btn-active">Призовки</a></li>
                <li><a href="#" data-url="routes">Маршрут</a></li>
                <li><a href="#" data-url="assign">Зачисляване</a></li>
            </ul>
        </div><!-- /navbar -->
    </div><!-- /header -->
    <div role="main" class="ui-content">
        <ul id="list" class="touch" data-role="listview" data-icon="false" data-split-icon="delete" data-filter="true" data-filter-placeholder="Търсене по адрес">
            {% for key, address in addresses %}
            {% if successId is defined %}
                {% set selectedRow = successId %}
            {% else %}
                {% set selectedRow = '' %}
            {% endif %}
                <li subpoena="{{ address.a.id }}" {{ selectedRow == address.a.id ? 'class="success"' : '' }}>
                    <a href="#" class="address" lat="{{ address.a.latitude }}" lng="{{ address.a.longitude }}">
                        <h3 class="topic">{{ address.a.address }}</h3>
                        <p><strong>Номер на делото: {{ address.a.case_number }}</strong></p>
                        <p>Изходящ номер: {{ address.a.reference_number }}</p>
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
        <fieldset data-role="controlgroup">
            <input name="action" id="visited" value="2" checked="checked" type="radio">
            <label for="visited">Посетен адрес</label>
            <input name="action" id="delivered" value="3" type="radio">
            <label for="delivered">Връчена призовка</label>
            <input name="action" id="not_delivered" value="5" type="radio">
            <label for="not_delivered">Невръчена призовка</label>
        </fieldset><br>
        <input type="button" id="submit" value="Запази"/>
    </div><!-- /popup -->
    <div id="error" class="ui-content" data-role="popup" data-theme="a">
        <p>Възникна грешка, моля опитайте отново по-късно!</p>
    </div><!-- /popup -->
</div>