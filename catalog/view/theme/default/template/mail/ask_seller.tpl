<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $inquiry_title; ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width: 680px;"><a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" style="margin-bottom: 20px; border: none;" /></a>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr><td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: rgb(176,0,0); font-weight: bold; text-align: left; padding: 7px; color: #FFF;"><?php echo $inquiry_title; ?></td></tr>
    </thead>	
  </table>
  <?php echo $vendor_name; ?><br/>
  <?php echo $pi_customer_question; ?><br/><br/>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;width: 30%;"><?php echo $text_product_image; ?></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;width: 70%;"><?php echo $text_product_detail; ?></td>
      </tr>
    </thead>	
    <tbody>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: center; padding: 7px;"><img src="<?php echo $pi_thumb; ?>" alt="<?php echo $pi_pname; ?>" style="margin-bottom: 20px; border: none;" /></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; vertical-align:text-top; padding: 7px;">
		<?php echo $pi_name; ?><br />
		<?php echo $pi_model; ?><br />
		<?php echo $pi_manufacturer; ?><br />
		<?php echo $pi_price; ?><br />
		<?php echo $pi_availability; ?><br />
		<?php echo $pi_website; ?><a href="<?php echo $pi_url; ?>" title="<?php echo $pi_pname; ?>" style="text-decoration:none; font-weight:normal;"><?php echo $pi_click_me; ?></a><br />
		</td>
	 </tr>
    </tbody>	
  </table>  
  <br />
  <?php echo $text_signature; ?>
  <br /><br /><br />
  <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_auto_msg; ?></p>
</div>
</body>
</html>
