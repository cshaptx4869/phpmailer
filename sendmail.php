<?php
    header("content-type:text/html;charset=utf-8");
    ini_set("magic_quotes_runtime",0);
    require 'class.phpmailer.php';
    //require 'PHPMailer/PHPMailerAutoload.php';  
    try {
    $mail = new PHPMailer(true);
    $mail->IsSMTP(); //使用SMTP方式发送
    $mail->SMTPDebug = 1; //启用SMTP调试功能	1 = errors and messages	 2 = messages only
    $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
    $mail->Priority = 3; // 设置邮件优先级 1：高, 3：正常（默认）, 5：低  
    $mail->SetLanguage('zh_cn'); // 设置错误中文提示
    $mail->SMTPSecure = 'tls';  // 设置启用加密，注意：必须打开 php_openssl 模块
    //$mail->SMTPSecure = 'ssl';  //设置使用ssl加密方式登录鉴权 163邮箱的ssl协议方式端口号是465/994
    $mail->SMTPAuth = true; //开启SMTP认证
    $mail->Port = 25; //邮箱服务器端口号
    $mail->Host = "smtp.163.com";
    $mail->Username = "你的163邮箱"; // 设置那 需要开启SMTP服务
    $mail->Password = "不是密码哦,是授权码";   //163的授权码
    //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
    $mail->From = "你的163邮箱"; //邮件发送者email地址
    $mail->FromName = "发件人名"; //发件人名称
    //$mail->setFrom("xxx@163.com","Mailer");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
    $to = "收件人邮箱";
    $mail->AddAddress($to); //收件人地址
    //$mail->AddAddress('ellen@example.com','wang'); // 添加多个接受者
    //$mail->AddCC('mail2@sina.com'); // 添加抄送人 
    //$mail->AddCC('mail3@qq.com');   // 添加多个抄送人 
    //$mail->AddBCC('mail4@qq.com');  // 添加密送者，Mail Header不会显示密送者信息
    $mail->AddReplyTo("你的163邮箱","发件人名");//添加回复地址 指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
    $mail->ConfirmReadingTo = '回执邮箱@163.com';  // 添加发送回执邮件地址，即当收件人打开邮件后，会询问是否发生回执 
    $mail->Subject = "phpmailer测试标题"; //邮件标题
    $mail->Body = "<h1>phpmail演示</h1>这是php点点通（<font color=red>发件人</font>）对phpmailer的测试内容"; //邮件内容
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
    $mail->WordWrap = 80; // 设置每行字符串的长度,自动换行
    $mail->AddAttachment("test.txt"); //可以添加附件
    //$mail->AddAttachment('/tmp/image.jpg', 'one pic');  // 添加多个附件
    $mail->IsHTML(true); //是否使用HTML格式
    $mail->Send(); //发送邮件测试结果进了垃圾箱是因为$mail->AltBody这个问题，把 $mail->AltBody 一行代码注释掉就好了
    $mail->ErrorInfo; 
    echo '邮件已发送';
    } catch (phpmailerException $e) {
    echo "邮件发送失败：".$e->errorMessage();
    }
