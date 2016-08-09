<?php
/**
 * Created by PhpStorm.
 * User: jingdongbai
 * Date: 2016/8/9
 * Time: 10:35
 */

/**
 * This is an example which using under yii2 framework
 */

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use Bjd\PhpDom\PhpCrawl;




class TestController extends Controller{
    public function actionIndex(){
        $res_data =  $this->_getContent();
        $file_put_path = Yii::getAlias("@app/runtime/logs/ss.tmp");

        file_put_contents($file_put_path,$res_data);
        yii::$app->response->sendFile($file_put_path,"gui-config.json");
        yii::$app->end();

    }

    public function actionStr(){
        echo $this->_getContent();
    }

    private function _getContent(){
        $html = PhpCrawl::file_get_html("http://ss.yuvpn.com/page/testss.html");
        $res  = $html->find(".testvpnitem") ;


        $ss_vpn = [];
        foreach($res as $key=>$value){

            $text =  $value->innertext();
            $ss_vpn[$key]['server'] = $value->find("span")[0]->innertext();

            $matches = [];
            preg_match_all("'¶Ë¿Ú£º\s?(.*?)<br\s?/>'is",$text,$matches);
            $ss_vpn[$key]['server_port'] = $matches[1][0];
            preg_match_all("'ÃÜÂë£º\s?(.*?)<br\s?/>'is",$text,$matches);
            $ss_vpn[$key]['password'] = $matches[1][0];
            $ss_vpn[$key]['method'] = $value->find("span")[1]->innertext();
            $ss_vpn[$key]['remarks'] = $value->find("span")[0]->innertext();

        }


        $data = [
            "index" => count($ss_vpn),
            "global" => true,
            "enabled" => true,
            "shareOverLan" => false,
            "isDefault" => false,
            "localPort" => 1080
        ];
        $data['configs'] = $ss_vpn;
        return   json_encode($data);
    }

}
