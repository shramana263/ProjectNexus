<?php

namespace App\Notifications;

use App\Models\EmailVerification;
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
    public $user;
    private $otp;


    /**
     * Create a new notification instance.
     */
    public function __construct(object $user)
    {
        $this->user= $user;
        $this->email = $user->email; // Assign the email to the property
        $this->name = $user->name;
        $this->message = 'Use the following OTP to verify your email address';
        $this->subject = 'Email Verification';
        $this->fromEmail = 'student@healnearn.com';
        $this->mailer='smtp';
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
        $otp= random_int(100000,999999);
        $tempUser = EmailVerification::create(array_merge(
            $this->user->toArray(),
            [
                'password' => bcrypt($this->user->password),
                'otp'=>random_int(100000,999999)
            ]
        ));
        return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('Hello!'.$this->name)
            ->line($this->message)
            ->line('code: '.$this->user->otp);
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
