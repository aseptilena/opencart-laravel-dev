<?php
// Text
$_['text_title']          = 'Klarna發票';
$_['text_terms_fee']	= '<span id="klarna_invoice_toc"></span> (+%s)<script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\', charge: %s});</script>';
$_['text_terms_no_fee']	= '<span id="klarna_invoice_toc"></span><script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\'});</script>';
$_['text_additional']      = 'Klarna帳戶需要一些額外的信息，才可以處理您的訂單。';
$_['text_wait']            = '請耐心等待！';
$_['text_male']            = '男';
$_['text_female']          = '女';
$_['text_year']            = '年';
$_['text_month']           = '月';
$_['text_day']             = '日';
$_['text_comment']        = "Klarna的發票編號： %s\n%s/%s: %.4f";

// Entry
$_['entry_gender']         = '性別:';
$_['entry_pno']            = '個人號碼：<br /><span class="help">請在此處輸入您的社會安全號碼。</span>';
$_['entry_dob']            = '出生日期：';
$_['entry_phone_no']       = '電話號碼：<br /><span class="help">請輸入您的電話號碼。</span>';
$_['entry_street']         = '街道：<br /><span class="help">請注意使用Klarna時，送貨只可以使用巳註冊的地址。</span>';
$_['entry_house_no']       = '門牌號:<br /><span class="help">請輸入您的門牌號碼。</span>';
$_['entry_house_ext']      = '房間號:<br /><span class="help">請在這裡提交您的房間號。 E.g. A, B, C, Red, Blue ect.</span>';
$_['entry_company']        = '公司註冊號：<br /><span class="help">請輸入您的公司的註冊號碼</span>';

// Help
$_['help_pno']					= '請在此輸入您的社會安全號碼。';
$_['help_phone_no']				= '請輸入您的手機號碼。';
$_['help_street']				= '請注意，配送地址只能是Klarna已註冊的地址。';
$_['help_house_no']				= '請輸入您的門牌號碼。';
$_['help_house_ext']			= 請輸入您的房間號碼。E.g. A, B, C, Red, Blue ect.';
$_['help_company']				= '請輸入您的公司的註冊號碼';

// Error
$_['error_deu_terms']      = '您必須同意Klarna的隱私政策 (Datenschutz)';
$_['error_address_match']  = '如果您想使用Klarna發票，支付和發貨地址必須相符';
$_['error_network']        = '在連接到Klarna時發生錯誤。請稍後再試。';

