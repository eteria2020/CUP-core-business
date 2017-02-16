<?php

namespace BusinessCore\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime;

class BusinessEmailService
{
    /**
     * @var \Zend\Mail\Transport\TransportInterface
     */
    private $emailTransport;

    /**
     * @var array
     */
    private $emailSettings;

    public function __construct(
        TransportInterface $emailTransport,
        array $emailSettings
    ) {
        $this->emailTransport = $emailTransport;
        $this->emailSettings = $emailSettings;
    }

    /**
     * sends an email with defaults parameters
     *
     * @param string $to recipient email address
     * @param string $subject email subject
     * @param string $content email body
     */
    public function sendEmail($to, $subject, $content)
    {
        $text = new Mime\Part($content);
        $text->type = Mime\Mime::TYPE_HTML;
        $text->charset = 'utf-8';

        $parts = [$text];

        $mimeMessage = new Mime\Message();
        $mimeMessage->setParts($parts);

        if (is_array($to)) {
            $to = array_map('strtolower', $to);
        } else {
            $to = strtolower($to);
        }

        $mail = (new Message())
            ->setFrom($this->emailSettings['from'])
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($mimeMessage)
            ->setEncoding("UTF-8");
        $mail->getHeaders()->addHeaderLine('X-Mailer', $this->emailSettings['X-Mailer']);

        $this->emailTransport->send($mail);
    }
}
