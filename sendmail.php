<?php
    header("content-type:text/html;charset=utf-8");
    ini_set("magic_quotes_runtime",0);
    require 'class.phpmailer.php';
    try {
    $mail = new PHPMailer(true);
    $mail->IsSMTP(); //使用SMTP方式发送
    $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
    $mail->SMTPAuth = true; //开启SMTP认证
    $mail->Port = 25; //邮箱服务器端口号
    $mail->Host = "smtp.163.com";
    $mail->Username = "你的163邮箱";
    $mail->Password = "不是密码哦,是授权码";   //163的授权码
    //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
    $mail->AddReplyTo("你的163邮箱","发件人名");//回复地址
    $mail->From = "你的163邮箱"; //邮件发送者email地址
    $mail->FromName = "发件人名"; //发件人名称
    $to = "收件人邮箱";
    $mail->AddAddress($to); //收件人地址
    $mail->Subject = "phpmailer测试标题"; //邮件标题
    $mail->Body = "<h1>phpmail演示</h1>这是php点点通（<font color=red>发件人</font>）对phpmailer的测试内容"; //邮件内容
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
    $mail->WordWrap = 80; // 设置每行字符串的长度
    $mail->AddAttachment("test.txt"); //可以添加附件
    $mail->IsHTML(true); //是否使用HTML格式
    $mail->Send(); 
    echo '邮件已发送';
    } catch (phpmailerException $e) {
    echo "邮件发送失败：".$e->errorMessage();
    }
