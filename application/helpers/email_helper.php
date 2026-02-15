<?php 
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	if(!function_exists('sendemail')) {
  		function sendemail($email,$subject,$message,$fieldname=false,$upload_path=false,$allowed_types=false,$file_name=false) {
    		// Getting CI class instance.
    		$CI = get_instance();
			if(!$CI->load->is_loaded('email')){
				$CI->load->library('email');
			} 
			if(!function_exists('upload')){
				$CI->load->helper('upload');
			} 
            
			$from="";
			if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost'){
				ini_set('smtp','localhost');
				ini_set('smtp_port',25);
				
				$config['protocol']='smtp';
				$config['smtp_host']='';
				$config['smtp_port']='465';
				$config['smtp_timeout']='30';
				$config['smtp_user']='';
				$config['smtp_pass']='';
				$from=$config['smtp_user'];
			}
            else{
                $config['mailpath']= "/usr/bin/sendmail";
                $config['protocol']='smtp';
                $config['smtp_host']='smtp.hostinger.com';
                $config['smtp_port']='587';
                $config['smtp_timeout']='30';
                $config['smtp_user']='';
                $config['smtp_pass']='';
			}
			$config['newline']="\r\n";
			$config['wordwrap'] = TRUE;
			//$config['charset'] = 'iso-8859-1';
            $config['charset'] = 'utf-8';
			$config['mailtype'] = "html";
			if($CI->input->get('test')=='test'){
                print_pre($config);
            }
            
            //$CI->load->library('email',$config);
            //getmethods();
            //$CI->email->set_newline("\r\n");
            //$CI->email->set_wordwrap(TRUE); // Enable word wrapping
            //$CI->email->set_mailtype('html'); // Set mailtype to HTML
			//print_pre($config,true);
			$CI->email->initialize($config);
			$CI->email->from($from,PROJECT_NAME);
            $CI->email->set_newline("\r\n");
            $CI->email->set_header('Return-Path', $from);
			$CI->email->to($email);
			$CI->email->subject($subject);
			$CI->email->message($message);
            
            // Add the List-Unsubscribe header
            $CI->email->set_header('List-Unsubscribe', '<mailto:info@royalpowerclub.com?subject=Unsubscribe>, <'.base_url('/unsubscribe').'>');

			
			if($fieldname!==false && $upload_path!==false && $allowed_types!==false){
				if($file_name===false){
					$file_name=$fieldname.'-attachment';
				}
				if(is_array($_FILES[$fieldname]['name'])){
					$count=count($_FILES[$fieldname]['name']);
					for($i=0; $i<$count; $i++) {
						if(is_uploaded_file($_FILES[$fieldname]['tmp_name'][$i])){
							$_FILES['multi']['name']     = $_FILES[$fieldname]['name'][$i];
							$_FILES['multi']['type']     = $_FILES[$fieldname]['type'][$i];
							$_FILES['multi']['tmp_name'] = $_FILES[$fieldname]['tmp_name'][$i];
							$_FILES['multi']['error']     = $_FILES[$fieldname]['error'][$i];
							$_FILES['multi']['size']     = $_FILES[$fieldname]['size'][$i];
								
							$attachment=upload_file('multi',$upload_path,$allowed_types,$file_name);
							$CI->email->attach(file_url($attachment));
							$attachment='.'.$attachment;
							if(file_exists($attachment)){
								unlink($attachment);
							}
						}
					}
				}
				else{
					$attachment=upload_file($fieldname,$upload_path,$allowed_types,$file_name);
					$CI->email->attach(file_url($attachment));
					$attachment='.'.$attachment;
					if(file_exists($attachment)){
						unlink($attachment);
					}
				}
			}
			if($CI->email->send()){
                if($CI->input->get('test')=='test'){
                    print_pre($CI->email);
                }
				return true;
			}
			else{
                if($CI->input->get('test')=='test'){
                    print_pre($CI->email);
                }
				return false;
			}
		}  
	}

    if(!function_exists('addressotpmail')) {
  		function addressotpmail($email,$otp){
            $subject="Verify Your Address Update Request";
            $message='<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Address Update Request</title>
</head>
<body><table id="v1container" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td id="v1container-cell" align="center" style="border: 1px solid">
                <table id="v1header-container" cellspacing="0" cellpadding="15" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td id="v1header-logo-cell" colspan="4">
                                <a href="'.base_url().'" target="_blank" rel="noreferrer">
                                    <img src="'.base_url('assets/images/logo.png').'" alt="'.PROJECT_NAME.' Logo">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td height="8" colspan="4" style="font-size: 1px; line-height: 1px">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <table cellspacing="0" cellpadding="15" border="0">
                    <tbody>
                        <td>
                             <p>We have received a request to update the Address. To verify your identity and proceed with the update, please use the OTP provided below:</p>

                            <p><strong>OTP:</strong> '.$otp.'</p>

                            <p>This OTP is a one-time code and will expire shortly. Please do not share this code with anyone. It is intended solely for your use in updating your Address.</p>
                            
                            <p>If you did not initiate this request or suspect any unauthorized activity, please contact our support team immediately.</p>
                        </td>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>';
            
            //sendemail($email,$subject,$message);
		}  
	}

    if(!function_exists('sendnotifications')) {
  		function sendnotifications($email,$subject,$message){
            $message='<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'.$subject.'</title>
</head>
<body><table id="v1container" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td id="v1container-cell" align="center" style="border: 1px solid">
                <table id="v1header-container" cellspacing="0" cellpadding="15" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td id="v1header-logo-cell" colspan="4">
                                <a href="'.base_url().'" target="_blank" rel="noreferrer">
                                    <img src="'.base_url('assets/images/logo.png').'" alt="'.PROJECT_NAME.' Logo">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td height="8" colspan="4" style="font-size: 1px; line-height: 1px">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <table cellspacing="0" cellpadding="15" border="0">
                    <tbody>
                        <td>
                             '.$message.'
                        </td>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>';
            
            //sendemail($email,$subject,$message);
		}  
	}

    if(!function_exists('withdrawalotpmail')) {
  		function withdrawalotpmail($name,$email,$otp,$amount,$date){
            $subject="Fund Withdrawal Authorization";
            $message='<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Withdrawal Authorization</title>
</head>
<body><table id="v1container" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td id="v1container-cell" align="center" style="border: 1px solid">
                <table id="v1header-container" cellspacing="0" cellpadding="15" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td id="v1header-logo-cell" colspan="4">
                                <a href="'.base_url().'" target="_blank" rel="noreferrer">
                                    <img src="'.base_url('assets/images/logo.png').'" alt="AMMT Capital Logo">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td height="8" colspan="4" style="font-size: 1px; line-height: 1px">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <table cellspacing="0" cellpadding="15" border="0">
                    <tbody>
                        <td>
                            <h5>Dear  '.$name.',</h5>
                             <p>You have requested to withdraw $'.$amount.' from your AMMT Capital account. To authorize this transaction, please use the OTP provided below:</p>

                            <p><strong>Withdrawal Amount:</strong> $'.$amount.'</p>
                            <p><strong>Request Date:</strong> '.date('d-m-Y',strtotime($date)).'</p>
                            <p><strong>OTP:</strong> '.$otp.'</p>

                            <p>This OTP is a one-time code and will expire shortly. Please do not share this code with anyone. It is intended solely for your use in authorizing the fund withdrawal.</p>
                            
                            <p>If you did not initiate this request or suspect any unauthorized activity, please contact our support team immediately at  <a href="mailto:support@ammtcapital.com">support@ammtcapital.com</a>.</p>
                            <p>Thank you for choosing AMMT Capital! </p>
                            <p>Best Regards, </p>
                            <p><strong>AMMT Capital</strong></p>
                            <a href="'.base_url().'">'.base_url().'</a>
                        </td>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>';
            
            //sendemail($email,$subject,$message);
		}  
	}
?>
