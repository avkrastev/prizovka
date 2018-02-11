<div data-role="page" id="assign-page" data-title="Призовка.бг" data-url="assign">
    <div data-role="header" data-position="fixed" data-theme="b">
        <h1>Призовкар.бг</h1>
        <a href="#" data-rel="back" class="initials">
            <strong><?php echo mb_substr($user->first_name, 0, 1); ?></strong>
            <strong class="text-primary"><?php echo mb_substr($user->last_name, 0, 1); ?></strong>
        </a>
        <a href="#" data-url="logout" data-icon="action" data-iconpos="notext">Излез</a>
        <div data-role="navbar">
            <ul>
                <li><a href="#" data-url="index">Призовки</a></li>
                <li><a href="#" data-url="routes">Маршрут</a></li>
                <li><a href="#" data-url="assign" class="ui-btn-active">Зачисляване</a></li>
            </ul>
        </div><!-- /navbar -->
    </div><!-- /header -->
  
    <div role="main" class="ui-content">
        {{ form('app/assign', 'id': 'assignSubpoenas', 'method': 'post', 'data-ajax': 'false') }}              	
            <label for="case_number">Номер на дело:</label>
            <input name="case_number" id="case_number" value="{{ postData['case_number'] }}" type="text">
            <label for="reference_number">Изходящ номер:</label>
            <input name="reference_number" id="reference_number" value="{{ postData['reference_number'] }}" type="text"><br>

            <input type="submit" id="submit" value="Търси"/>
        </form><br><br>
        <ul id="list" class="touch" data-role="listview" data-icon="false" data-split-icon="delete" data-filter-placeholder="Търсене по адрес">
            {% for key, address in addresses %}
                <li subpoena="{{ address.id }}">
                    <a href="#" class="address" lat="{{ address.latitude }}" lng="{{ address.longitude }}">
                        <h3 class="topic">{{ address.address }}</h3>
                        <p><strong>Номер на делото: {{ address.case_number }}</strong></p>
                        <p>Изходящ номер: {{ address.reference_number }}</p>
                    </a>
                </li>
            {% endfor %}
        </ul>
    </div><!-- /content -->
    <div id="confirm" class="ui-content" data-role="popup" data-theme="a">
        <p id="question">Сигурни ли сте, че искате да зачислите тази призовка?</p>
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <a id="yes" class="ui-btn ui-corner-all ui-mini ui-btn-a" data-rel="back">Да</a>
            </div>
            <div class="ui-block-b">
                <a id="cancel" class="ui-btn ui-corner-all ui-mini ui-btn-a" data-rel="back">Не</a>
            </div>
        </div>
    </div><!-- /popup -->
    <div id="error" class="ui-content" data-role="popup" data-theme="a">
        <p>Възникна грешка, моля опитайте отново по-късно!</p>
    </div><!-- /popup -->
</div>