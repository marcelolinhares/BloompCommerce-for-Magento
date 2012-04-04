<?PHP 
require_once 'Mage/Checkout/controllers/CartController.php'; 
class Bloompa_Bloompa_Checkout_CartController extends Mage_Checkout_CartController { 
    public function indexAction() 
    {	
		if($_GET['cupom']=='bloompa'):

			/*
			$dados = array('coupon_code' => '39c4af018d85fe6600bc6cbe363515ce');
			$cURL = curl_init('http://localhost/rossi/checkout/cart/couponPost/');
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_POST, true);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $dados);
			$resultado = curl_exec($cURL);
			curl_close($cURL);
			*/
		endif;
		parent::indexAction();
    } 
	
	function postData ($url, $data, $optional_headers = null)
	{
		$params = array('http' => array
		(
			'method' => 'POST',
			'content' => http_build_query($data, "", "&")
		));
		if ($optional_headers !== null):
			$params['http']['header'] = $optional_headers;
		endif;
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		if (!$fp):
			throw new Exception("Problem with $url, $php_errormsg");
		endif;
		$response = @stream_get_contents($fp);
		if ($response === false):
			throw new Exception("Problem reading data from $url, $php_errormsg");
		endif;
		return $response;
	}
	
	function gen_redirect_and_form($addr, $page, $msg, $host="")
	{
		$sret = "";
		$sret .= "POST ".$page." HTTP/1.1\r\n";
		$sret .= "Host: ".$host."\r\n";
		$sret .= "Content-Type: application/x-www-form-urlencoded\r\n";
		//$sret .= "Content-Type: text/html; charset=utf-8\r\n";
		$sret .= "Content-Length: ".strlen($msg)."\r\n\r\n";
		$sret .= $msg."\r\n";
		//$sret .= "Connection: Close\r\n\r\n";
		return $sret;
	}
	
    public function bloompcommerceAction() 
    {	
		$url = explode('bloompcommerce', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		echo "<html><head></head><body>
			<form name='foo' action='http://".$url[0]."checkout/cart/couponPost/' method=POST>
			 <input type=hidden name='coupon_code' value='39c4af018d85fe6600bc6cbe363515ce' />
			 <input type=hidden name='remove' value='0' />
			</form>
			<SCRIPT FOR=window EVENT=onload LANGUAGE='JavaScript'>
			 document.foo.submit();
			</SCRIPT>
			</body></html>";
	}
	
	public function bloompaupdateAction()
	{
		$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		$strsql = "SELECT * FROM `salesrule` WHERE `name`='Bloompa'";
		$bloompa = $readConnection->fetchAll($strsql);

		if(count($bloompa)==0):
			$strsql = "INSERT INTO `salesrule` (`name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `customer_group_ids`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `website_ids`, `coupon_type`) VALUES ('Bloompa', 'Cupom de desconto fornecido pelo compartilhamento do site nas redes sociais.', <?=date('Y-m-d');?>, NULL, 0, '0,1,2,3,4', 1, 'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}', 'a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}', 0, 1, '', 0, 'by_percent', '".$_REQUEST['desconto']."', NULL, 0, 0, 0, 0, 1, '1', 2)";
			$writeConnection->query($strsql);
			$strsql = "INSERT INTO `salesrule_coupon` (`rule_id`, `code`, `usage_limit`, `usage_per_customer`, `times_used`, `expiration_date`, `is_primary`) VALUES (".mysql_insert_id().", '39c4af018d85fe6600bc6cbe363515ce', NULL, NULL, 0, NULL, 1)";
			$writeConnection->query($strsql);
		else:
			$strsql = "UPDATE `salesrule` SET discount_amount = '".$_GET['desconto']."' WHERE name = 'Bloompa'";
			$writeConnection->query($strsql);
		endif;
		echo "data = {msg:'desconto atualizado', status:true}";
	}
} 
