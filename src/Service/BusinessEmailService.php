<?php

namespace BusinessCore\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime;
use BusinessCore\Entity\Repository\MailsRepository;

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

    /**
     *
     * @var MailsRepository 
     */
    private $mailsRepository;

    public function __construct(
        TransportInterface $emailTransport,
        array $emailSettings,
        MailsRepository $mailsRepository
    ) {
        $this->emailTransport = $emailTransport;
        $this->emailSettings = $emailSettings;
        $this->mailsRepository = $mailsRepository;
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
        $from = $this->emailSettings['from'];

        if (is_array($to)) {
            $to = array_map('strtolower', $to);
        } else {
            $to = strtolower($to);
        }

        $mail = (new Message())
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($mimeMessage)
            ->setEncoding("UTF-8");
        $mail->getHeaders()->addHeaderLine('X-Mailer', $this->emailSettings['X-Mailer']);

        $this->emailTransport->send($mail);
    }

    /**
     * get Mail from database
     *
     * @param integer $category
     * @param string $language
     * @return Mails $mail
     */
    public function getMail($category, $language) {
        $mail = $this->mailsRepository->findMails($category, $language);
        $find = count($mail);

        if ($find == 0) {
            $mail = $this->mailsRepository->findMails($category, "it");
        }
        return $mail[0];
    }
}
