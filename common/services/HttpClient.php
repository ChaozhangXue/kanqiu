<?php
/**
 * @link http://qidian.qq.com/
 * @copyright Copyright © 1998 - 2018 Tencent.
 * @license All Rights Reserved. 腾讯公司 版权所有
 */
namespace common\services;

use Yii;

/**
 * 调用第三方平台api工具包
 */
class HttpClient 
{
    /**
     * 请求方式
     */
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_PUT = 'PUT';
    const REQUEST_METHOD_DELETE = 'DELETE';

    /**
     * content-type
     */
	const CONTENT_TYPE_JSON = 'application/json';
	const CONTENT_TYPE_URLENCODED = 'application/x-www-form-urlencoded';

	// 操作接口地址
	protected $apiUrl;
	//curl连接句柄
	protected $curl;    
	protected $apiUrl_info; //api_Url 数据
	//传输的POST数据	
	protected $postData;
	//错误信息
	public $error;      
	//超时时间
	protected $curlTimeout;
	//content-content默认设置
	protected $contentType = 'application/json';
	//header
    protected $headers = [];

	/**
	 * 执行完成后是否关闭curl句柄,默认关闭
	 * 此开关用于同一进程是否对同一个api多次请求的情况，最后一次请求务必设为true关闭curl
	 * @var boolean
	 */
	public $closeCurl = true;
    //状态码
    private $httpCode;

    public function __construct($apiUrl)
    {
    	$this->apiUrl = $apiUrl;
    	$this->setCurlTimeout(4);
    	
    	$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL,$this->apiUrl);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); //绕过ssl验证
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false); //从证书中检查加密算法是否存在
    }

    /**
     * 获取资源句柄
     * @return false|resource
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     *  设置代理
     * @param $proxy string  host:port
     */
    public function setProxy($proxy = array())
    {
        if (!empty($proxy)) {
            $this->apiUrl_info = parse_url($this->apiUrl);
            if ($this->apiUrl_info['scheme'] == 'https') {
                $this->apiUrl = 'http'.'://' . $this->apiUrl_info['host'] . $this->apiUrl_info['path'];
                if(!empty($this->apiUrl_info['query'])){
                    $this->apiUrl = $this->apiUrl .'?'. $this->apiUrl_info['query'];
                }
                curl_setopt($this->curl, CURLOPT_URL,$this->apiUrl);
            }
            curl_setopt($this->curl, CURLOPT_PROXY, $proxy);
        }
    }

	/**
     * 设置超时时间
     * @param int $sec 超时时间，单位秒
     */
    public function setCurlTimeout(int $sec)
    {
        $this->curlTimeout = $sec;
        return $this;
    }

    /**
     * 设置Content-Type头
     * @param string $header
     */
    public function setContentType($type)
    {
    	$this->contentType = $type;
    	return $this;
    }

    /**
     * 是否关闭curl连接句柄
     * @param bool $bool 默认true关闭
     */
    public function setCloseCurl(bool $bool = true)
    {
        $this->closeCurl = $bool;
        return $this;
    }

    /**
     * 设置post提交参数
     * @param array $data 需要提交的参数数组
     */
    protected function setPostData(array $data)
    {
    	if (empty($data)) return;

    	switch ($this->contentType) {
	    	case self::CONTENT_TYPE_JSON:
		    	$this->postData = json_encode($data,JSON_UNESCAPED_UNICODE);
    			break;
    		case self::CONTENT_TYPE_URLENCODED:
		    	$this->postData = http_build_query($data);
    			break;
    		default:
    			throw new \Exception('Not Support The Content-Type!');
    			break;
    	}
    }

    /**
     * 添加请求头参数
     * @param array $headers
     * @return $this
     */
    public function addHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

	/**
	 * get方式发送数据
	 * @return array
	 */
	public function get() 
	{
		$response = $this->_httpClient();
        //Log::info("apiURL:%s,response:%s",[$this->apiUrl,print_r($response,true)]);
        return $response;
	}

	/**
	 * post方式发送数据
	 * @param array $data 需要提交的参数数组
	 * @return array
	 */
	public function post(array $data = [])
	{
		$this->setPostData($data);
		$response = $this->_httpClient(self::REQUEST_METHOD_POST,$this->postData);
        //Log::info("apiURL:%s,params:%s,response:%s",[$this->apiUrl,$this->postData,print_r($response,true)]);
        return $response;
	}

    /**
     * post方式发送数据
     * @param array $data 需要提交的参数数组
     * @return array
     */
    public function put(array $data = [])
    {
        $this->setPostData($data);
        $response = $this->_httpClient(self::REQUEST_METHOD_PUT,$this->postData);
        //Log::info("apiURL:%s,params:%s,response:%s",[$this->apiUrl,$this->postData,print_r($response,true)]);
        return $response;
    }

    /**
     * post方式发送数据
     * @param array $data 需要提交的参数数组
     * @return array
     */
    public function delete(array $data = [])
    {
        $this->setPostData($data);
        $response = $this->_httpClient(self::REQUEST_METHOD_DELETE,$this->postData);
        //Log::info("apiURL:%s,params:%s,response:%s",[$this->apiUrl,$this->postData,print_r($response,true)]);
        return $response;
    }
	
	/**
	 * Curl工具连接api
	 * @param string $data
	 * @return mixed
	 */
	private function _httpClient($method = 'GET',$data = '') 
	{
		switch ($method) {
            case self::REQUEST_METHOD_GET :
                empty($this->headers) || curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
                break;
            case self::REQUEST_METHOD_POST :
            case self::REQUEST_METHOD_PUT :
            case self::REQUEST_METHOD_DELETE :
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, array_merge(
                        [
                            'Content-Type: '. $this->contentType .'; charset=utf-8',
                            'Content-Length: ' . strlen($data),
                            'traceid:' .Yii::$app->params['seq']
                        ],
                        $this->headers
                    )
                );
                curl_setopt($this->curl,CURLOPT_CUSTOMREQUEST,$method);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

	    curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->curlTimeout); // 设置超时
	    // curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true); //递归重定向 
		
		$response = curl_exec($this->curl);
        $this->httpCode = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);//状态码
        if ($response === false) {
			$this->error = curl_error($this->curl);
		}
		if ($this->closeCurl) {
			curl_close($this->curl);
		}

        return json_decode($response,true);
	}

	/**
	 * 获取http状态码
     */
	public function getHttpCode()
    {
        return $this->httpCode;
    }
}