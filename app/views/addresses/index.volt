
<section class="charts">
    <div class="container-fluid">
        <header class="clearfix"> 
            <h1 class="h3 float-left">Създаване на QR кодове</h1>
        </header>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-sm-4">
                                {{ form('addresses/assign', 'id': 'addressesForm') }}
                                    {{ form.render('latitude') }}
                                    {{ form.render('longitude') }}
                                    <div class="form-group">
                                        {{ form.label('case_number') }}
                                        {{ form.render('case_number', ['class': 'form-control']) }}
                                        <span class="help-block-none form-control-feedback">Номерът на делото е задължително поле</span>
                                    </div>
                                    <div class="form-group">
                                        {{ form.label('reference_number') }}
                                        {{ form.render('reference_number', ['class': 'form-control']) }}
                                        <span class="help-block-none form-control-feedback">Изходящият номер е задължително поле</span>
                                    </div>  
                                    <div class="form-group">
                                        {{ form.label('address') }}
                                        {{ form.render('address', ['class': 'form-control', 'id': 'pac-input']) }}
                                        <span class="help-block-none form-control-feedback">Адресът е задължително поле</span>
                                    </div>
                                    <div class="form-group">
                                        {{ form.label('assigned_to', ['for': 'assigned_to']) }}
                                        <div class="select">
                                            {{ form.render('assigned_to', ['class': 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">       
                                        <input type="submit" id="assignAddress" value="Зачисли призовка" class="btn btn-primary">
                                    </div>
                                    <div id="qrcode">
                                        <a href="#" id="downloadQR" download="">
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