# phpmailer
发送邮件

在使用phpmailer的时候使用到添加附件的功能，最后报错。
class.phpmailer.php
1469行 1475行 位置
提示的错误为如图所示“Deprecated: Function set_magic_quotes_runtime() is deprecated in xxx on line xxx”。
原因是这个函数（set_magic_quotes_runtime()）在php5.3以后就被弃用了。
由于官方也没提供替代的函数，所以找到地方，直接注释就OK了。

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
2.
