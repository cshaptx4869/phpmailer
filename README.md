# phpmailer
发送邮件

在使用phpmailer的时候使用到添加附件的功能，最后报错。
class.phpmailer.php
1469行 1475行 位置
提示的错误为如图所示“Deprecated: Function set_magic_quotes_runtime() is deprecated in xxx on line xxx”。
原因是这个函数（set_magic_quotes_runtime()）在php5.3以后就被弃用了。
由于官方也没提供替代的函数，所以找到地方，直接注释就OK了
或者是在set_magic_quotes_runtime()前面加@符号
或者是配置 error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED。

                  +----------------+                  +-----------------+        
+--------+        |                |                  |                 |
| User   | <----> |                |                  |                 | 
+--------+        |                |                  |                 |               +--------+ 
+--------+        |  Sender-SMTP   | <------------>   |  Receiver-SMTP  |   <------->   |  File  |
|  File  | <----> |                |                  |                 |               | System |
| System |        |                |                  |                 |               +--------+
+--------+        |                |                  |                 |
                  +----------------+                  +-----------------+   
                  
                                     Model for SMTP Use

常见错误

1.SMTP Error: Could not authenticate.
  smtp验证没通过，就是smtp server 的用户名和密码不正确
  
2.提示SMTP 数据错误，554
  •554 DT:SPM 发送的邮件内容包含了未被许可的信息，或被系统识别为垃圾邮件。请检查是否有用户发送病毒或者垃圾邮件； 
  •554 DT:SUM 信封发件人和信头发件人不匹配； 
  •554 IP is rejected, smtp auth error limit exceed 该IP验证失败次数过多，被临时禁止连接。请检查验证信息设置； 
  •554 HL:IHU 发信IP因发送垃圾邮件或存在异常的连接行为，被暂时挂起。请检测发信IP在历史上的发信情况和发信程序是否存在异常； 
  •554 HL:IPB 该IP不在网易允许的发送地址列表里； 
  •554 MI:STC 发件人当天内累计邮件数量超过限制，当天不再接受该发件人的投信。请降低发信频率； 
  •554 MI:SPB 此用户不在网易允许的发信用户列表里； 
  •554 IP in blacklist 该IP不在网易允许的发送地址列表里。 
  
 3.返回错误，SMTP信息错误554，（打开发送用的邮箱，发现邮件被退信了）因为内容是垃圾信息，也就是官网给出的默认内容，可能因为测试人数过多，服务商直接屏蔽了这些内容，随便修改下发送的内容就能显示发送成功
 
 4.发送成功，结果对方没有收到邮件，是因为邮件进了垃圾邮件，这里我把$mail->Altbody注释掉了，就发送成功了，因为我也没有去研究它的文档，所以我也不知道注释掉这个有什么不同。重点是能发送就可以了。如果你还是无法发送，请检查你的邮箱设置是否启用SMTP协议，正常情况下无论是POP3还是其他协议都是启用了SMTP的。其他问题我就没法解决了。
 
 5.使用phpmailer时，需要用到php的openssl扩展，在php.ini中开启
   浏览器出现如下，说明php中没有开启openssl扩展（去掉php.ini中extension=php_openssl.dll前面的分号(;)，记得重启Apache服务）Windows下有时可能会开启不了服务，可以用Windows的服务开启：
SMTP Error: Could not connect to SMTP host. Message could not be sent.Mailer Error: SMTP Error: Could not connect to SMTP host.
 
 6.thinkphp5中的extend下的扩展类库使用的是命名空间必须在class.phpmailer.php和class.smtp.php最开头加上 namespace phpmaier;  
   浏览器中出现如下，说明phpmailer类库中没有没有写命名空间（namespace phpmailer;） 指TP
 
 7.thinkphp框架找不到Exception异常类：在Exception前面加上反斜杠"\"  指TP
 
 8.thinkphp5的项目中的extend目录下新建一个phpmailer文件夹，然后把class.phpmailer.php和class.smtp.php文件复制到phpmailer目录下。
   在使用phpmailer时，实例化PHPMailer()，需要使用命名空间。 
   use phpmailer\phpmailer; 
   这里有一个问题，thinkphp5的扩展类的定义是，类文件命名为：phpmailer.php而不是class.phpmailer.php。
 
 9.SMTP -> NOTICE: EOF caught while checking if connectedThe following From address failed: xxx@xxx.xxx SMTP -> ERROR: EHLO not accepted from server:
   出现上面的错误,很可能是被服务器禁掉了
  
 10.RCPT not accepted from server :554 sender is rejected
   应该是邮件被过滤了
   
 
 
