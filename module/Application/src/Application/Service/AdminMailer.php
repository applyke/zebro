<?php
namespace Application\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

/**
 * Example:
 *
 * $admin_mailer = new AdminMailer();
$admin_mailer->setSubject($subject)
->setBody($massage)->setMailTo("user@host.com")
->send();
 *
 * Class AdminMailer
 * @package Application\Service
 */

class AdminMailer
{
    private $mail_to;
    private $mail_from;
    private $subject;
    private $body;
    private $host_name;
    private $host;
    private $port;
    private $connection_class;
    private $login;
    private $password;
    private $ssl;

    function __construct()
    {
        $config = $this->getLocalConfig();
        $this->setMailTo($config['mail_to']);
        $this->setMailFrom($config['mail_from']);
        $this->setHostName($config['name']);
        $this->setHost($config['host']);
        $this->setPort($config['port']);
        $this->setConnectionClass($config['connection_class']);
        $this->setLogin($config['login']);
        $this->setPassword($config['password']);
        $this->setSsl($config['ssl']);
        $this->setSubject("Exception in site");
        $this->setBody('');
    }

    function send(){
        $message = new Message();
        $message->addTo($this->getMailTo())
            ->addFrom($this->getMailFrom())
            ->setSubject($this->getSubject())
            ->setBody($this->getBody());
        $transport = new SmtpTransport();
        $options   = new SmtpOptions(array(
            'name' => $this->getHostName(),
            'host' => $this->getHost(),
            'port' => $this->getPort(),
            'connection_class' => $this->getConnectionClass(),
            'connection_config' => array(
                'username' => $this->getLogin(),
                'password' => $this->getPassword(),
                'ssl' => $this->getSsl(),
            ),
        ));
        $transport->setOptions($options);
        $transport->send($message);
    }

    /**
     * @param mixed $subject
     * @return AdminMailer
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param mixed $body
     * @return AdminMailer
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param $mail_to
     * @return AdminMailer
     */
    public function setMailTo($mail_to)
    {
        $this->mail_to = $mail_to;
        return $this;
    }

    /**
     * @param $mail_from
     */
    protected function setMailFrom($mail_from)
    {
        $this->mail_from = $mail_from;
    }

    private function getLocalConfig()
    {
        $rootPath = dirname($this->findParentPath('module'));
        $config_string = "{$rootPath}/config/autoload/local.php";
        $config = new \Zend\Config\Config(include $config_string );
        return $config->mail;
    }

    protected function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }

    /**
     * @return mixed
     */
    private function getMailTo()
    {
        return $this->mail_to;
    }

    /**
     * @return mixed
     */
    private function getMailFrom()
    {
        return $this->mail_from;
    }

    /**
     * @return mixed
     */
    private function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    private function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    private function getHostName()
    {
        return $this->host_name;
    }

    /**
     * @return mixed
     */
    private function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    private function getPort()
    {
        return $this->port;
    }

    /**
     * @return mixed
     */
    private function getConnectionClass()
    {
        return $this->connection_class;
    }

    /**
     * @return mixed
     */
    private function getLogin()
    {
        return $this->login;
    }

    /**
     * @return mixed
     */
    private function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    private function getSsl()
    {
        return $this->ssl;
    }



    /**
     * @param mixed $host_name
     */
    private function setHostName($host_name)
    {
        $this->host_name = $host_name;
    }

    /**
     * @param mixed $host
     */
    private function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @param mixed $port
     */
    private function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @param mixed $connection_class
     */
    private function setConnectionClass($connection_class)
    {
        $this->connection_class = $connection_class;
    }

    /**
     * @param mixed $login
     */
    private function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @param mixed $password
     */
    private function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $ssl
     */
    private function setSsl($ssl)
    {
        $this->ssl = $ssl;
    }

}