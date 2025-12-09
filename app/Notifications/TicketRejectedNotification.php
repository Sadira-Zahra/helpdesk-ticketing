<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketRejectedNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $reason;

    public function __construct($ticket, $reason = null)
    {
        $this->ticket = $ticket;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // ✅ Arahkan ke login petugas
        $url = route('login_petugas');
        
        $message = (new MailMessage)
                    ->subject('Tiket Ditolak Teknisi - #' . $this->ticket->nomor)
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Tiket telah ditolak oleh teknisi')
                    ->line('Nomor Tiket: ' . $this->ticket->nomor)
                    ->line('Judul: ' . $this->ticket->judul)
                    ->line('Departemen: ' . $this->ticket->departemen->nama_departemen);
        
        if ($this->reason) {
            $message->line('Alasan Penolakan: ' . $this->reason);
        }
        
        return $message->action('Login untuk Assign Ulang', $url)
                      ->line('Terima kasih!');
    }
}
