<?php

namespace common\services;


class BaseService
{
    /**
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $header
     * @return mixed
     * @throws \Exception
     */
    public function sendRequest($url, $data = [], $method = 'POST', $header = [], $pemList = [])
    {
        $urlArr = parse_url($url);
        if ($method != 'POST') {
            $queryList = [];
            if (isset($urlArr['query'])) {
                $queryList[] = $urlArr['query'];
            }
            if (!empty($data)) {
                $queryList[] = http_build_query($data);
            }
            $url = $urlArr['scheme'] . '://' . $urlArr['host'];
            if (isset($urlArr['path'])) {
                $url .= $urlArr['path'];
            }
            if (count($queryList)) {
                $url .= urldecode('?' . implode('&', $queryList));
            }
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0); //将头文件的信息作为数据流输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回获取的输出文本流
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if ($urlArr['scheme'] == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        if (!empty($pemList)) {
            //使用证书：cert 与 key 分别属于两个.pem文件
            //默认格式为PEM，可以注释
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $pemList['cert']);
            //默认格式为PEM，可以注释
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $pemList['key']);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $ret = curl_exec($ch);
        if (28 == curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception($error);
        }
        curl_close($ch);
        return $ret;
    }

    public function xml2array($contents, $get_attributes = 1, $priority = 'tag')
    {
        if (!$contents) return array();

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values) return;//Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach ($xml_values as $data) {
            unset($attributes, $value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if ($type == "open") {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data) $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];

                } else { //There was another element with the same tag name

                    if (isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag], $result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data) $current[$tag . '_attr'] = $attributes_data;

                } else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;

                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }

            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return ($xml_array);
    }

    public function uuid($len, $radix)
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $uuid = [];

        for ($i = 0; $i < $len; $i++) {
            $uuid[$i] = $chars[rand(0, $radix)];
        }
        return implode('', $uuid);

    }

    /**
     * @param $where
     * @param bool $as_array
     * @return mixed
     */
    public function getWhere($where, $as_array = true)
    {
        $model = $this->model;
        if ($as_array == true) {
            return $model::find()->where($where)->asArray()->all();
        } else {
            return $model::find()->where($where)->all();

        }
    }

    /**
     * @param $where
     * @param bool $as_array
     * @return mixed
     */
    public function getWhereOne($where, $as_array = true)
    {
        $model = $this->model;
        if ($as_array == true) {
            return $model::find()->where($where)->asArray()->one();
        } else {
            return $model::find()->where($where)->one();

        }
    }

    public function updateAll($where, $update_data)
    {
        $this->model->updateAll($update_data, $where);
    }

    /**
     * @param $data
     * @return
     * @throws \Exception
     */
    public function add($data)
    {
        if (!$this->model->load($data, '')) {
            throw new \Exception(array_values($this->model->firstErrors)[0]);
        }
        if (!$this->model->save()) {
            throw new \Exception(array_values($this->model->firstErrors)[0]);
        }
        return $this->model;
    }

    public function update($id, $data)
    {
        $model = $this->model;
        $model = $model::findOne([
            'id' => $id
        ]);

        foreach ($data as $key => $val) {
            $model->$key = $val;
        }

        if (!$model->save()) {
            throw new \Exception(array_values($model->firstErrors)[0]);
        }
        return $model;
    }

    /**
     * 根据起点坐标和终点坐标测距离
     * @param  [array]   $from    [起点坐标(经纬度),例如:array(118.012951,36.810024)]
     * @param  [array]   $to    [终点坐标(经纬度)]
     * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
     * @param  [int]     $decimal   精度 保留小数位数
     * @return [string]  距离数值
     */
    public function getDistance($from, $to, $km = true, $decimal = 2)
    {
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * 1000;

        if ($km) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
}