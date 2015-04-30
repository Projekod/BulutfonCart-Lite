<fieldset>
    <legend>Api Uygulama Bilgileri [ <a href="https://bulutfon.com/oauth/applications" target="_blank">Bulutfon Uygulamalara Git</a> ]</legend>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="config_bulutfon_key">Uygulama Anahtarı : </label>
        <div class="col-sm-10">
            <input type="text" name="config_bulutfon_key" value="<?php echo $config_bulutfon_key; ?>" placeholder="Uygulama Anahtarı" id="config_bulutfon_key" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="config_bulutfon_secret">Gizli Anahtar : </label>
        <div class="col-sm-10">
            <input type="text" name="config_bulutfon_secret" value="<?php echo $config_bulutfon_secret; ?>" placeholder="Uygulama Anahtarı" id="config_bulutfon_secret" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Yönlendirme Adresi : </label>
        <div class="col-sm-10">
            <div class="well">
                <?php echo $config_bulutfon_url;?>
            </div>
        </div>
        <div class="col-sm-2"></div>
        <div class="col-md-8">
            * Bulutfon Api kullanabilmeniz için SSL sertifikanızın yüklü ve aktif olması gerekmektedir.
        </div>
    </div>
</fieldset>
