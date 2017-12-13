
<section class="forms">
    <div class="container-fluid">
        <header> 
            <h1 class="h3 display">Създаване на QR кодове</h1>
        </header>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-sm-4">
                                {{ form('addresses/assign', 'id': 'addressesForm', 'onbeforesubmit': 'return false') }}
                                    <div class="form-group">
                                        {{ form.label('number') }}
                                        {{ form.render('number', ['class': 'form-control']) }}
                                    </div>
                                    <div class="form-group">
                                        {{ form.label('date') }}
                                        {{ form.render('date', ['class': 'form-control hasDatepicker']) }}
                                    </div>
                                    {% set addressformClass = '' %}
                                    {% set addressformControlClass = '' %}
                                    {% set addressMessage = '' %}
                                    {% if address is defined %}
                                        {% set addressformClass = 'has-danger' %}
                                        {% set addressformControlClass = 'form-control-danger' %}
                                        {% set addressMessage = address %}
                                    {% endif %}   
                                    <div class="form-group {{ addressformClass }}">
                                        {{ form.label('address') }}
                                        {{ form.render('address', ['class': 'form-control ' ~ addressformControlClass, 'id': 'pac-input']) }}
                                        <span class="help-block-none form-control-feedback">{{ addressMessage }}</span>
                                    </div>
                                    <div class="form-group">
                                            {{ form.label('assign', ['for': 'assign']) }}
                                            <div class="select">
                                                {{ form.render('assign', ['class': 'form-control']) }}
                                            </div>
                                        </div>
                                    <div class="form-group">       
                                        <input type="submit" id="createQR" value="Създай QR код" class="btn btn-primary">
                                    </div>
                                    <div id="qrcode">
                                        <a href="" id="downloadQR" download>
                                            <img src="" alt="" title="QR код" /><br>
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-8">
                                <div id="map"></div>
                                <div id="infowindow-content">
                                    <img src="" width="16" height="16" id="place-icon">
                                    <span id="place-name" class="title"></span><br>
                                    <span id="place-address"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>