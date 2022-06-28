<?php

namespace App\Mail\Promotion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $role,$institution_id,$name;
    public function __construct($role,$institution_id,$name)
    {
        $this->role=$role;
        $this->institution_id=$institution_id;
        $this->name=$name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to WAM Academy')
            ->markdown('emails.promotion.new_user_welcome_mail')
            ->from('notify@wearemarcus.com','WAM Academy')
            ->with([
            'role' =>  $this->role,
            'institution_id' =>  $this->institution_id,
            'name'=>$this->name
        ]);
    }
}
