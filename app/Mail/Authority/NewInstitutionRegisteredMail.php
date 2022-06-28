<?php

namespace App\Mail\Authority;

use App\Models\Institution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInstitutionRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $institution_id;
    public function __construct($institution_id)
    {
        $this->institution_id=$institution_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $institution=Institution::find($this->institution_id);
        return $this->markdown('emails.authority.notification.new_institution_registered_mail')->with([
            'institution_name'=>$institution->institution_name,
        ]);
    }
}
