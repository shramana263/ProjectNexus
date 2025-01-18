<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Session;

class EmailVerificationNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    public $email;
    public $name;
    private $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $email, string $name)
    {
        $this->email = $email; // Assign the email to the property
        $this->name = $name;
        $this->message = 'Use the following OTP to verify your email address';
        $this->subject = 'Email Verification';
        $this->fromEmail = 'student@healnearn.com';
        $this->mailer='smtp';
        $this->otp= new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp= $this->otp->generate($this->email, 'numeric', 6, 60);
        Session::put('otp',$otp);
        return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('Hello!'.$this->name)
            ->line($this->message)
            ->line('code: '.$otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
