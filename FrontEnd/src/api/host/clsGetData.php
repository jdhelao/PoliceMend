<?php
date_default_timezone_set('America/Guayaquil');
//header("Content-Type: text/html;charset=utf-8");
//header("Content-Type: text/html;charset=ISO-8859-1");
//error_reporting(0);            //parano no postar ccuanquier error
//error_reporting(E_ALL ^ E_STRICT);		//parano no mostrar sólo el error de  -->Strict Standards: mysqli::next_result(): There is no next result set. Please, call mysqli_more_results()/mysqli::more_results() to check whether to call this function/method 

class clsGetData
{
    /*	public static $dsDatos='';

        public function __construct()
        {
            self::$dsDatos = '';
        }*/

    public static function get_RealIP()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

	/**
	$associative values
	1 : array numeric index
	2 : array name index

	*/
    public static function get_DataSet($sp_codigo, $parametros, $associative = 0/*columns referenced with the column name */, $fromCache = false /*get data from cache*/)
    {
		print_r('zzz');
		print_r($sp_codigo);
		print_r('xxxx');
		print_r($parametros);
		print_r('yyyy');
		print_r($associative);
		print_r('uuuu');
		print_r($fromCache);
		print_r('tttt');
		
        $dsData = null;
        $ready = null;

        $cache_dir = (dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'cache';
        $cache_key = (string)$sp_codigo . md5($parametros) . $associative . md5(date('Ymd', time()));
        $cache_nam = $cache_dir . DIRECTORY_SEPARATOR . $cache_key . date('Ymd_H', time());
        $cache_dat = null;
        
        if (!file_exists($cache_dir)) { mkdir($cache_dir, 0777, true); }
        if (file_exists($cache_nam) and is_file($cache_nam)) {
            $cache_dat = unserialize(file_get_contents($cache_nam));
        } else {
            ///delete old cache data
            $files2remove = scandir($cache_dir);
            if (isset($files2remove)) {
                for ($v = 0, $w = count($files2remove); $v < $w; $v++) {
                    if (strpos($cache_dir . DIRECTORY_SEPARATOR . $files2remove[$v], (string)md5(date('Ymd', time()))) === false and strpos($cache_dir . DIRECTORY_SEPARATOR . $files2remove[$v], (string)$sp_codigo) !== false and file_exists($cache_dir . DIRECTORY_SEPARATOR . $files2remove[$v]) and is_file($cache_dir . DIRECTORY_SEPARATOR . $files2remove[$v])) {
                        unlink($cache_dir . DIRECTORY_SEPARATOR . $files2remove[$v]);
                    }
                }
            }
            unset($files2remove);
            //$cache_dat = clsGetData::get_DataSet(2559, '', 1);
            //file_put_contents($cache_nam, serialize($cache_dat));
        }
		print_r('aaaa');

        if ($fromCache and isset($cache_dat) and is_array($cache_dat) and $cache_dat != null) {
            $dsData = $cache_dat;
        } else {
            try {
				print_r('bbbb');

                /*
                        $ws = new Soapclient('http://190.12.54.147/wssafdoccomercial/Servicio.asmx?WSDL',array('cache_wsdl' => WSDL_CACHE_NONE,'trace' => TRUE));
                        $ws->__setLocation('http://190.12.54.147/wssafdoccomercial/Servicio.asmx');
                */
                $ws = new Soapclient('http://190.12.54.147/wsSafiedDHweb/Servicio.asmx?WSDL', array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));
                $ws->__setLocation('http://190.12.54.147/wsSafiedDHweb/Servicio.asmx');
print_r('ccc');
                $param = array('id' => $sp_codigo, 'cadena' => $parametros);
                $ready = $ws->wmObtenerDatos_xml($param)->wmObtenerDatos_xmlResult;
print_r('dddd');

            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
                $xm = null;
                $ready = null;
				print_r('ERRRRORRRRR');
            }

            //$ready = str_replace('<table _id="0" _count="0"></table>', '<table _id="0" _count="0"><tr><td></td></tr></table>', $ready);
            $xm = simplexml_load_string($ready);
print_r($xm);
print_r('EEEE');

            //print_r($xm); echo  PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . 'dddd' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL ;
            try {
                if (count($xm->children()) > 0) {
                    if (count($xm->table->children()) > 0) {
                        /////////////////////
                        //loop for dataset's TABLES
                        foreach ($xm->children() as $tb) {///print_r($tb);echo '< tb >' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
                            unset($attrib_tb);
                            $attrib_tb = (array)$tb->attributes();
                            $attrib_tb = $attrib_tb['@attributes'];
                            /// print_r($attrib_tb); echo '$attrib_tb' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
                            $dsData[(int)$attrib_tb['_id']] = null;  ///set nullable value into "table" array

                            if ($attrib_tb['_count'] > 0)   //if num/count of rows is great 0
                            {
                                /////////////////////
                                //loop for tables' ROWS
                                foreach ($tb->children() as $tr) {
                                    unset($attrib_tr);

                                    $attrib_tr = (array)$tr->attributes();
                                    $attrib_tr = $attrib_tr['@attributes'];

                                    $data_tr = (array)$tr;
                                    $data_tr = $data_tr['td'];

                                    //print_r($attrib_tr); 
                                    $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']] = null;  ///set any value into "row" array

                                    if ($attrib_tr['_count'] > 0)   //if num/count of columns great than 0
                                    {
                                        /////////////////////
                                        //loop for cell's Atribute
                                        $i = 0;
                                        foreach ($tr->children() as $td) {
                                            unset($attrib_td);
                                            $attrib_td = (array)$td;
                                            $attrib_td = $attrib_td['@attributes'];

                                            if (isset($attrib_td['_name'])) {
                                                $attrib_td['_name'] = utf8_encode(mb_convert_encoding($attrib_td['_name'], 'Windows-1252', 'UTF-8'));
                                            }
                                            ///print_r($attrib_td); echo PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
                                            ///print_r($tr); echo PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
                                            //$dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = $data_tr[$i];


                                            if (filter_var((string)$attrib_td['_isnull'], FILTER_VALIDATE_BOOLEAN)) {
                                                if ((int)$associative == 1) {
                                                    $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = null;
                                                } else {
                                                    $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_id']] = null;
                                                }
                                            } else {
                                                if ((int)$associative == 1) {
                                                    ///echo $attrib_td['_type'] . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
                                                    if ($attrib_td['_type'] == 'string') {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = utf8_encode(mb_convert_encoding(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), 'Windows-1252', 'UTF-8'));
                                                    } else if ($attrib_td['_type'] == 'boolean') {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = filter_var(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), FILTER_VALIDATE_BOOLEAN);
                                                    } else if ($attrib_td['_type'] == 'dateTime') {
                                                        $_fecha = DateTime::createFromFormat('Y-m-d\TG:i:s', substr(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), 0, 19));
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = ($_fecha);
                                                    } else if ($attrib_td['_type'] == 'date') {
                                                        $_fecha = DateTime::createFromFormat('Y-m-d', substr(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), 0, 10));
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = ($_fecha);
                                                    } else if ($attrib_td['_type'] == 'double') {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = (float)((is_array($data_tr)) ? $data_tr[$i] : $data_tr);
                                                    } else {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_name']] = ((is_array($data_tr)) ? $data_tr[$i] : $data_tr);
                                                    }
                                                } else {
                                                    if ($attrib_td['_type'] == 'string') {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_id']] = utf8_encode(mb_convert_encoding(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), 'Windows-1252', 'UTF-8'));
                                                    } else if ($attrib_td['_type'] == 'boolean') {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_id']] = filter_var(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), FILTER_VALIDATE_BOOLEAN);
                                                    } else if ($attrib_td['_type'] == 'dateTime') {
                                                        $_fecha = DateTime::createFromFormat('Y-m-d\TG:i:s', substr(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), 0, 19));
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_id']] = ($_fecha);
                                                    } else if ($attrib_td['_type'] == 'date') {
                                                        $_fecha = DateTime::createFromFormat('Y-m-d', substr(((is_array($data_tr)) ? $data_tr[$i] : $data_tr), 0, 10));
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_id']] = ($_fecha);
                                                    } else if ($attrib_td['_type'] == 'double') {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_id']] = (float)((is_array($data_tr)) ? $data_tr[$i] : (float)$data_tr);
                                                    } else {
                                                        $dsData[(int)$attrib_tb['_id']][(int)$attrib_tr['_id']][$attrib_td['_id']] = ((is_array($data_tr)) ? $data_tr[$i] : $data_tr);
                                                    }
                                                }
                                            }


                                            /////////////////////////////////////////////////////////////////////                           
                                            $i += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $dsData = null;
            }

            if ($fromCache) {
                file_put_contents($cache_nam, serialize($dsData));
            }
        }

        return $dsData;
    }


    public function __destruct(){} /*8654789465456*/

}
?>