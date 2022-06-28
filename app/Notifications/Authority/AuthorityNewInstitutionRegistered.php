<?php

namespace App\Notifications\Authority;

use App\Mail\Authority\NewInstitutionRegisteredMail;
use App\Models\Institution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuthorityNewInstitutionRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $institution_id;
    public function __construct($institution_id)
    {
        $this->institution_id=$institution_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        if($notifiable->is_mailable == 1)
//            return ['mail','database'];
//        else
//            return ['database'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NewInstitutionRegisteredMail
     */
    public function toMail($notifiable)
    {
        return (new NewInstitutionRegisteredMail($this->institution_id))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $institution=Institution::find($this->institution_id);
        return [
            'notification_type'=>6,
            'institution_name'=>$institution->institution_name,
        ];
    }
}
