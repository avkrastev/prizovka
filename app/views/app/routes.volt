
            
<div data-role="page" id="routes-page" data-title="Призовка.бг" data-url="routes">
<div data-role="header" data-position="fixed" data-theme="b">
        <h1>Призовкар.бг</h1>
        <a href="#" data-rel="back" class="initials">
            <strong><?php echo mb_substr($user->first_name, 0, 1); ?></strong>
            <strong class="text-primary"><?php echo mb_substr($user->last_name, 0, 1); ?></strong>
        </a>
        <a href="#" data-url="logout" data-icon="action" data-iconpos="notext">Излез</a>
        <div data-role="navbar" data-position="fixed">
            <ul>
                <li><a href="#" data-url="index">Призовки</a></li>
                <li><a href="#" data-url="routes" class="ui-btn-active">Маршрут</a></li>
                <li><a href="#" data-url="assign">Зачисляване</a></li>
            </ul>
        </div><!-- /navbar -->    
    </div><!-- /header -->
    <div role="main" class="ui-content">                
        <form id="routes">
            <div class="ui-field-contain">
                <label for="start">Начална точка:</label>
                <select name="start" id="start">
                    <option value="">Моята позиция</option>
                    <option value="" lat="42.1530036" lng="24.7561777">бул. „6-ти Септември“ 219</option>
                </select><br/>
                <fieldset data-role="controlgroup" id="waypoints">
                        <legend>Междинни точки:</legend>
                        {% for key, address in addresses %}
                            <input name="{{ address.a.id }}" id="{{ address.a.id }}" type="checkbox" lat="{{ address.a.latitude }}" lng="{{ address.a.longitude }}">
                            <label for="{{ address.a.id }}">{{ address.a.address }}</label>
                        {% endfor %}
                </fieldset><br/>
                <label for="end">Крайна точка:</label>
                <select name="end" id="end">
                    <option value="" lat="42.1530036" lng="24.7561777">бул. „6-ти Септември“ 219</option>
                    {% for key, address in addresses %}
                        <option value="{{ address.a.id }}" lat="{{ address.a.latitude }}" lng="{{ address.a.longitude }}">{{ address.a.address }}</option>
                    {% endfor %}
                </select><br/>
                <input type="button" id="submit" value="Маршрут"/>
            </div>
        </form>
    </div><!-- /content -->
    <div id="mapDialog" class="ui-content" data-role="popup" data-theme="a"> 
        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Затвори</a>
        <div id="map"></div>
        <div id="directions-panel"></div>
    </div><!-- /popup -->
</div>