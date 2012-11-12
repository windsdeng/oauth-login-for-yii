<?php
/**
 * oauth login for yii 
 * 
 * @author windsdeng@gmail.com http://www.dlf5.com
 * @copyright Copyright &copy; 2010 dlf5.com
 */

Yii::import('ext.oauthLogin.qq.qqConnect',true);
Yii::import('ext.oauthLogin.sina.sinaWeibo',true);

class oauthLogin extends CWidget 
{
	/***** widget options  *****/
	
	/******* widget public vars *******/
	public $baseUrl			= null;
	
	public $cssFile = array(
							'/css/oauth_login_yii.css',
			   		);
	
	public $data = array();
	
    /**
     *
     * @var  small_login and medium_login big_login
     */
    public $itemView = 'small_login';

    public $sina_code_url = null;

    public $qq_code_url = null;
    
    public $back_url = null;


    /**
	* Initialize the widget
	*/
	public function init()
	{
		parent::init();        
		//Publish assets
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
		$this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
		
		//Register the widget css files
		$cs=Yii::app()->clientScript;
		foreach($this->cssFile as $css) {
			
			$oauthCssFile = $this->baseUrl . $css;
			$cs->registerCssFile($oauthCssFile);
		}
        
        $this->sinaLogin();
        $this->qqLogin();
	}
	
	
    /**
     * sinaLogin
     */
    public function sinaLogin()
    {
        $state = md5(rand(5, 10));
        Yii::app()->session->add('sina_state',$state);
        $weiboService = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
        $this->sina_code_url = $weiboService->getAuthorizeURL(WB_CALLBACK_URL,'code',$state);
		Yii::app()->session->add('back_url',$this->back_url.'?state='.$state);
    }
    
    /**
     * qqLogin
     */
    public function qqLogin()
    {
        $state = md5(rand(5, 10));
        Yii::app()->session->add('qq_state',$state);
        $qqService = new qqConnectAuthV2(QQ_APPID,QQ_APPKEY);
        $this->qq_code_url = $qqService->getAuthorizeURL(QQ_CALLBACK_URL,'code',$state);
        Yii::app()->session->add('back_url',$this->back_url.'?state='.$state);
    }


    /**
	* Run the widget
	*/
	public function run()
	{
		parent::run();
		$this->getViewFile($this->itemView);
		$this->render($this->itemView,array('data',$this->data)); 	
	}

}	