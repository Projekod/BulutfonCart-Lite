<?php
class ControllerBulutfonLogin extends Controller {
    public function index(){
        require __DIR__.'/vendor/autoload.php';
        $key = $this->config->get('config_bulutfon_key');
        $secret = $this->config->get('config_bulutfon_secret');
        $refreshToken = $this->config->get('config_bulutfon_tokenRefresh');

        $code = $this->request->get["code"];

        $redirectUrl = "https://".$this->config->get('config_ftp_hostname')."/bulutfonauth.php";

        $provider = new \Bulutfon\OAuth2\Client\Provider\Bulutfon([
            'clientId'          => $key,
            'clientSecret'      => $secret,
            'redirectUri'       => $redirectUrl
        ]);


        if($code){
            $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
            $this->refreshData($token);
            echo "<div style='display:block;padding:10px;margin:10px;text-align: center;'>Yetkilendirme tamamlandı<br/>
            <a href='http://".$this->config->get('config_ftp_hostname')."'>Ana sayfa</a></div>";
        }elseif($refreshToken){
            $token = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $refreshToken
            ]);
            $this->refreshData($token);
        }

        return "";
    }

    public function refreshData($token){
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting Where store_id=0 and `key`='config_bulutfon_token' ");
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting Where store_id=0 and `key`='config_bulutfon_tokenRefresh' ");
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting Where store_id=0 and `key`='config_bulutfon_expires' ");

        $sorgu = "insert into ".DB_PREFIX."setting(store_id,code,`key`,`value`,serialized) values(0,'config','config_bulutfon_token','".$token->accessToken."',0)";
        $this->db->query($sorgu);
        $sorgu = "insert into ".DB_PREFIX."setting(store_id,code,`key`,`value`,serialized) values(0,'config','config_bulutfon_tokenRefresh','".$token->refreshToken."',0)";
        $this->db->query($sorgu);
        $sorgu = "insert into ".DB_PREFIX."setting(store_id,code,`key`,`value`,serialized) values(0,'config','config_bulutfon_expires','".$token->expires."',0)";
        $this->db->query($sorgu);
    }

    public function adminmodel($model) {

        $admin_dir = DIR_SYSTEM;
        $admin_dir = str_replace('system/','admin/',$admin_dir);
        $file = $admin_dir . 'model/' . $model . '.php';
        //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
        $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
        if (file_exists($file)) {
            include_once($file);
            $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
        } else {
            trigger_error('Error: Could not load model ' . $model . '!');
            exit();
        }
    }
}