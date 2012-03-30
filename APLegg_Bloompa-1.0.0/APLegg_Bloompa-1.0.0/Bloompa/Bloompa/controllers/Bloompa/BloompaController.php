<?PHP 
require_once 'Mage/Checkout/controllers/CartController.php'; 
class Bloompa_Bloompa_Bloompa_BloompaController extends Mage_Checkout_CartController { 
    public function indexAction() 
    {	
		$url = explode('bloompcommerce', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		
		echo "<html><head></head><body>
			<form name='foo' action='http://".$url[0]."checkout/cart/couponPost/' method=POST>
			 <input type=hidden name='coupon_code' value='".md5("Bloompa-".$_REQUEST['set_type'])."' />
			 <input type=hidden name='remove' value='0' />
			</form>
			<SCRIPT FOR=window EVENT=onload LANGUAGE='JavaScript'>
			 document.foo.submit();
			</SCRIPT>
			</body></html>";
    } 
	
	public function setupAction()
	{
		Mage::getSingleton('core/session', array('name'=>'adminhtml') );
		$adminsession = Mage::getSingleton('admin/session', array('name'=>'adminhtml'));
		
		if($adminsession->isLoggedIn()) :
			echo "data = {msg:'desconto atualizado', status:true}";
		else:
			echo "data = {msg:'você não esta logado', status:false}";
		endif;
			$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
			$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
			
			if($_REQUEST['set_token']):
				$strsql = "CREATE TABLE IF NOT EXISTS `bloompa` (
							  `id_token` int(11) NOT NULL DEFAULT '1',
							  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
							  PRIMARY KEY (`id_token`)
							) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Tabela com as configurações do bloompa';
							REPLACE INTO `bloompa` (`id_token`, `token`) VALUES (1, '".$_REQUEST['set_token']."');
							";
				$writeConnection->query($strsql);
			endif;
			
			if($_REQUEST['set_discount']):
			
				$strsql = "SELECT * FROM `salesrule` WHERE `name`='Bloompa-".$_REQUEST['set_type']."'";
				$bloompa = $readConnection->fetchAll($strsql);
		
				if(count($bloompa)==0):
					$strsql = "INSERT INTO `salesrule` (`name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `customer_group_ids`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `website_ids`, `coupon_type`) VALUES ('Bloompa-".$_REQUEST['set_type']."', 'Cupom de desconto fornecido pelo compartilhamento do site nas redes sociais.', <?=date('Y-m-d');?>, NULL, 0, '0,1,2,3,4', 1, 'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}', 'a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}', 0, 1, '', 0, 'by_percent', '".$_REQUEST['set_discount']."', NULL, 0, 0, 0, 0, 1, '1', 2)";
					$writeConnection->query($strsql);
					$strsql = "INSERT INTO `salesrule_coupon` (`rule_id`, `code`, `usage_limit`, `usage_per_customer`, `times_used`, `expiration_date`, `is_primary`) VALUES (".mysql_insert_id().", '".md5("Bloompa-".$_REQUEST['set_type'])."'', NULL, NULL, 0, NULL, 1)";
					$writeConnection->query($strsql);
				else:
					$strsql = "UPDATE `salesrule` SET discount_amount = '".$_REQUEST['set_discount']."' WHERE name = 'Bloompa-".$_REQUEST['set_type']."'";
					$writeConnection->query($strsql);
				endif;
				
			endif;
		parent::indexAction();
	}
} 
