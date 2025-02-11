<?php

namespace App\Notifications;

use App\Models\EmailVerification;
use Carbon\Carbon;
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
        // dd($this->user);
        $now = Carbon::now();
        $fetchedUser= EmailVerification::where('email',$this->user->email)->first();
        if($fetchedUser){
            $fetchedUser->otp= random_int(100000, 999999);
            $fetchedUser->expires_at = $now->addDay();
            $fetchedUser->save();
            return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('Hello!'.$this->name)
            ->line($this->message)
            ->line('code: '.$fetchedUser->otp);
        }
        $tempUser = EmailVerification::create(array_merge(
            $this->user->toArray(),
            [
                // 'college_id'=>$this->user->college_id,
                'password' => bcrypt($this->user->password),
                'otp'=>random_int(100000,999999),
                'expires_at' => Carbon::now()->addDay()
            ]
        ));
        return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('Hello!'.$this->name)
            ->line($this->message)
            ->line('code: '.$tempUser->otp);
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
