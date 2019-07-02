<?php

require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SMTPMailSender
{
    private static $obj = null;

    private $mail;

    private $errorMsg;

    protected $config = [
        'CharSet' => 'UTF-8',                       // The character set of the message
        'Priority' => 3,                            // Email priority 1 = High, 3 = Normal, 5 = low
        'Language' => 'zh_cn',                      // The language for error messages
        'Host' => 'smtp.163.com',                   // SMTP hosts
        'SMTPAuth' => true,                         // Whether to use SMTP authentication
        'Username' => 'xxx@163.com',                // SMTP username
        'Password' => 'xxx',                        // SMTP password
        'SMTPSecure' => 'tls',                      // What kind of encryption to use on the SMTP connection  '', ssl, tls
        'Port' => 25,                               // The default SMTP server port
        'SMTPDebug' => 0,                           // SMTP class debug output mode  `0` No output `2` Data and commands
        'From' => [                                 // The From email address for the message
            'address' => 'xxx@163.com',
            'name' => 'xxx'
        ],
        'Reply' => [                                // The Reply email address for the email
            'address' => 'xxx@163.com',
            'name' => 'xxx'
        ],
        'WordWrap' => 78,                           // Word-wrap the message body to this number of chars
        'AltBody' => 'This is the body in plain text for non-HTML mail clients',
        'UseDefaultContent' => true,
        'DefaultContent' => [
            'subject' => 'Here is the subject',
            'body' => 'This is the HTML message body <b>in bold!</b>'
        ]
    ];

    /**
     * 获取单例
     * @param array $config
     * @return SMTPMailSender|null
     */
    public static function getInstance(array $config = [])
    {
        if (is_null(self::$obj)) {
            self::$obj = new self($config);
        }
        return self::$obj;
    }

    protected function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->mail = new PHPMailer(true);
        $this->initSetting();
    }

    protected function __clone()
    {

    }

    protected function __wakeup()
    {

    }

    /**
     * 初始化配置项
     */
    protected function initSetting()
    {
        // Mailer settings
        $this->mail->CharSet = $this->config['CharSet'];
        $this->mail->Priority = $this->config['Priority'];
        $this->mail->SetLanguage($this->config['Language']);

        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host = $this->config['Host'];
        $this->mail->SMTPAuth = $this->config['SMTPAuth'];
        $this->mail->Username = $this->config['Username'];
        $this->mail->Password = $this->config['Password'];
        $this->mail->SMTPSecure = $this->config['SMTPSecure'];
        $this->mail->Port = $this->config['Port'];
        $this->mail->SMTPDebug = $this->config['SMTPDebug'];

        // Addresser settings
        $this->mail->From = $this->config['From']['address'];
        $this->mail->FromName = $this->config['From']['name'];
        $this->mail->addReplyTo($this->config['Reply']['address'], $this->config['Reply']['name']);

        // Content settings
        $this->mail->isHTML(true);
        $this->mail->AltBody = $this->config['AltBody'];
        $this->mail->WordWrap = $this->config['WordWrap'];
    }

    /**
     * 发送邮件
     * @return bool
     */
    public function sendMail()
    {
        if ($this->config['UseDefaultContent']) {
            if (empty($this->mail->Subject)) {
                $this->mail->Subject = $this->config['DefaultContent']['subject'];
            }

            if (empty($this->mail->Body)) {
                $this->mail->Body = $this->config['DefaultContent']['body'];
            }
        }

        try {
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            $this->errorMsg = $this->mail->ErrorInfo;
            return false;
        }
    }

    /**
     * 添加收件人
     * @param string|array $address
     * @param string $name
     * @return $this
     */
    public function addAddress($address, $name = '')
    {
        if (is_array($address)) {
            foreach ($address as $item) {
                $this->mail->addAddress($item);
            }
        } else {
            $this->mail->addAddress($address, $name);
        }

        return $this;
    }

    /**
     * 添加抄送人
     * @param string|array $address
     * @param string $name
     * @return $this
     */
    public function addCC($address, $name = '')
    {
        if (is_array($address)) {
            foreach ($address as $item) {
                $this->mail->addCC($item);
            }
        } else {
            $this->mail->addCC($address, $name);
        }
        return $this;
    }

    /**
     * 添加密送者，Mail Header不会显示密送者信息
     * @param string|array $address
     * @param string $name
     * @return $this
     */
    public function addBCC($address, $name = '')
    {
        if (is_array($address)) {
            foreach ($address as $item) {
                $this->mail->addBCC($item);
            }
        } else {
            $this->mail->addBCC($address, $name);
        }

        return $this;
    }

    /**
     * 添加附件
     * @param string|array $path 附件完整地址
     * @param string $name 附件重命名
     * @return $this
     * @throws Exception
     */
    public function addAttachment($path, $name = '')
    {
        if (is_array($path)) {
            foreach ($path as $item) {
                $this->mail->addAttachment($item, basename($item));
            }
        } else {
            $this->mail->addAttachment($path, $name);
        }

        return $this;
    }

    /**
     * 设置邮件内容
     * @param $subject
     * @param $body
     * @return $this
     */
    public function setContent($subject, $body)
    {
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;

        return $this;
    }

    /**
     * 从HTML模板创建内容
     * @param string $subject 消息头
     * @param string $message html模板内容
     * @param string $basedir 图片的绝对路径
     * @return $this
     */
    public function setHtmlContent($subject, $message, $basedir)
    {
        $this->mail->Subject = $subject;
        $this->mail->msgHTML($message, $basedir);

        return $this;
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->errorMsg;
    }
}


// use
$bool = SMTPMailSender::getInstance()
    ->addAddress('xxx@qq.com', '白開水')
    ->addCC('xxx@qq.com')
    ->addAttachment('./test.txt', '福利')
    ->setHtmlContent('测试html模板', file_get_contents('./mail.html'), __DIR__)
    ->sendMail();

if ($bool)
    echo 'success';
else
    echo SMTPMailSender::getInstance()->getError();