<?php

namespace App\Mail\Facilitator\Invitation;

use App\Models\Institution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFacilitatorInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $institution_id, $email;
    public function __construct($institution_id , $email)
    {
        $this->institution_id=$institution_id;
        $this->email=$email;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $institution_code=Institution::find($this->institution_id)->institution_code;
        return $this
            ->subject('Invitation to join WAM Academy')
            ->markdown('emails.facilitator.invitation.new_facilitator_invitation_mail')
            ->with([
            'institution_code'=>$institution_code,
            'email'=>$this->email
        ]);
    }
}
