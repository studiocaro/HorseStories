<?php
namespace HorseStories\Core\Mailers;

use HorseStories\Models\Users\User;
use Illuminate\Mail\Mailer as Mail;

abstract class Mailer
{
    /**
     * @var \Illuminate\Mail\Mailer
     */
    private $mail;

    /**
     * @param \Illuminate\Mail\Mailer $mail
     */
    public function __construct(Mail $mail)
    {

        $this->mail = $mail;
    }

    /**
     * @param \HorseStories\Models\Users\User $user
     * @param string $subject
     * @param string $view
     * @param array $data
     */
    public function sendTo(User $user, $subject, $view, $data = [])
    {
        $this->mail->queue($view, $data, function($message) use ($user, $subject) {
            $message->to('test@test.com')->subject($subject);
        });
    }
}