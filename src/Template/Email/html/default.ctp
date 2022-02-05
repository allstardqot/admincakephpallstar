
<?php 
	if( 1 ) {
?>
<style>
	
@font-face {
	font-family: 'work_sansbold';
	src: url(<?php echo SITE_URL.'worksans-bold-webfont.woff2'; ?>) format('woff2'),
			url(<?php echo SITE_URL.'worksans-bold-webfont.woff'; ?>) format('woff');
	font-weight: normal;
	font-style: normal;
}


@font-face {
	font-family: 'nowaymedium';
	src: url(<?php echo SITE_URL.'noway-medium-webfont.woff2'; ?>) format('woff2'),
			url(<?php echo SITE_URL.'noway-medium-webfont.woff'; ?>) format('woff');
	font-weight: normal;
	font-style: normal;
}


@font-face {
	font-family: 'work_sansmedium';
	src: url(<?php echo SITE_URL.'worksans-medium-webfont.woff2'; ?>) format('woff2'),
			url(<?php echo SITE_URL.'worksans-medium-webfont.woff'; ?>) format('woff');
	font-weight: normal;
	font-style: normal;
}




@font-face {
	font-family: 'work_sansregular';
	src: url(<?php echo SITE_URL.'worksans-regular-webfont.woff2'; ?>) format('woff2'),
			url(<?php echo SITE_URL.'worksans-regular-webfont.woff'; ?>) format('woff');
	font-weight: normal;
	font-style: normal;
}
</style>

<title></title>
<table align="center" style="width: 100%; max-width: 706px; border-collapse: collapse; border:1px solid #dddddd;">
	<tbody>
		<tr style="background: #ce0606;">
			<td  style="padding: 15px 78px 15px 78px; text-align: center;">
				<a href="<?php echo SITE_URL; ?>" style="display: block;">
					<img src="<?php echo SITE_URL.'/dist/img/adminlogo.png'; ?>" style="outline:none; width:70px;" />
				</a>
			</td>
		</tr>
		<tr >
			<td colspan="2" style="background: #C8C8C8; padding: 30px 78px 0px 78px;">
				<table style="width: 100%;border-collapse: collapse; background: #fff; padding: 40px 75px 40px 75px; border:1px solid #E5E5E5;">
					<tbody>
						<tr>
							<td colspan="2" style="  font-family: 'work_sansregular'; font-size: 15px; color: #000000;  padding: 40px 75px 40px 75px; text-align: left;line-height: 21px;">
							<p><?php 
									$content = explode("\n", $content);
									
									foreach ($content as $line) :
										echo '<p> ' . $line . "</p>\n";
									endforeach;
								?></p>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr >
			<td colspan="2" style="background: #C8C8C8; padding: 0px 78px 70px 78px;">
				<table style="width: 100%;border-collapse: collapse; background: #E5E5E5; padding: 30px 75px 40px 75px; text-align: center;">
					<tbody>
						<!-- 			
						<tr style="">
							<td  colspan="4" style=" font-family: 'nowaymedium'; font-size:11px;  color:#242424;  padding: 40px 75px 10px 75px;">Download the <?php SITE_TITLE ?> App now</td>
						</tr>
						<tr>
							<td  colspan="4" style="text-align: center; ">
								<a href="javascript:void(0)"><img src="<?php echo SITE_URL.'assets/emailtemplate/android.png'; ?>" alt="" ></a></td>
						</tr>
						<tr style="">
							<td  colspan="4" style=" font-family: 'nowaymedium'; font-size:11px;  color:#242424;  padding: 0px 75px 10px 75px;">Follow us</td>
						</tr>
						<tr>
							<td style="text-align: center; " colspan="4">
								<a href="<?php echo FB_SOCIAL_PAGE; ?>" target="<?php echo FB_SOCIAL_PAGE; ?>" >
									<img src="<?php echo SITE_URL.'assets/emailtemplate/facebook.png'; ?>" alt="" style="width: 25px;">
								</a>
								<a href="<?php echo IG_SOCIAL_PAGE; ?>" target="<?php echo IG_SOCIAL_PAGE; ?>">
									<img src="<?php echo SITE_URL.'assets/emailtemplate/instagram.png'; ?>" alt="" style="width: 25px;">
								</a>
								<a href="<?php echo YT_SOCIAL_PAGE; ?>" target="<?php echo YT_SOCIAL_PAGE; ?>">
									<img src="<?php echo SITE_URL.'assets/emailtemplate/youtube.png'; ?>" alt="" style="width: 25px;">
								</a>
								<a href="<?php echo TW_SOCIAL_PAGE; ?>" target="<?php echo TW_SOCIAL_PAGE; ?>" >
									<img src="<?php echo SITE_URL.'assets/emailtemplate/twitter.png'; ?>" alt="" style="width: 25px;">
								</a>
							</td>
							
						</tr> -->
						<tr>
							<td colspan="4" style="padding: 33px 0px 0px 0px; color: #A1A1A1;" >
								<!-- <a href="<?php echo SITE_URL.'contact'; ?>" style="font-family: 'work_sansmedium'; text-decoration: none; color: #A1A1A1; font-size: 12px">Contact</a>&nbsp;&nbsp;|&nbsp;&nbsp; -->
								<a href="<?php echo 'https://www.lfgdraft.com/privacy/'; ?>" style="font-family: 'work_sansmedium'; text-decoration: none; color: #A1A1A1; font-size: 12px">Privacy policy</a>&nbsp;&nbsp;|&nbsp;&nbsp;
								<a href="<?php echo 'https://www.lfgdraft.com/terms-of-services/'; ?>" style="font-family: 'work_sansmedium'; text-decoration: none; color: #A1A1A1; font-size: 12px">Terms & conditions</a>
							</td>
						</tr>
						<tr>
							<td colspan="4" align="center" style="padding: 33px 0px 14px 0px;">
								<a href="#" style="color: #A1A1A1; font-weight: 600; text-decoration: none; font-size: 10px;">© All Rights Reserved.</a>
							</td>
						</tr>
						
					</tbody>
				</table>
			</td>
		</tr>

		
	</tbody>
</table>
	
<?php
	} else {
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo SITE_TITLE; ?></title>
	</head>
	<body style="padding: 0px 0px; margin: 0px 0px;">
		<table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" border="0" style="padding: 15px;">
			<tr>
				<td>
					<table align="center" width="650" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #f3f3f3;">
						<tr>
							<td style="padding: 10px 15px; text-align: center">
								<img src="<?php echo SITE_URL.'assets/images/logo-img.png'; ?>" alt="logo" style="width:20%" />
							</td>
						</tr>
						<tr>
							<td colspan="2" style="font-family: Arial; font-size: 21px; color: #4e5865; line-height: 28px; padding: 15px 20px 0px;">
								<?php 
									$content = explode("\n", $content);
									
									foreach ($content as $line) :
										echo '<p> ' . $line . "</p>\n";
									endforeach;
								?>
							</td>
						</tr>
						<!-- <tr>
							<td colspan="2" style="padding: 15px 20px;">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td align="center" bgcolor="#2988af" style="font-family: Arial; font-size: 21px; color: #fff; line-height: 25px; padding: 12px 20px;">
											<a href="<?php //echo SITE_URL.DOWNLOAD_APK_NAME;?>" style="color:#FFF;text-decoration:none">Download</a>
										</td>
									</tr>
								</table>
							</td>
						</tr> -->
						
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php		
	}

	//die;

?>


