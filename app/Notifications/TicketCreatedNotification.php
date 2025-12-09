<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCreatedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // ✅ Arahkan ke login petugas
        $url = route('login_petugas');
        
        return (new MailMessage)
                    ->subject('Tiket Baru Dibuat - #' . $this->ticket->nomor)
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Tiket baru telah dibuat oleh ' . $this->ticket->user->name)
                    ->line('Nomor Tiket: ' . $this->ticket->nomor)
                    ->line('Judul: ' . $this->ticket->judul)
                    ->line('Departemen: ' . $this->ticket->departemen->nama_departemen)
                    ->line('Urgency: ' . $this->ticket->urgency->urgency)
                    ->action('Login untuk Lihat Tiket', $url)
                    ->line('Terima kasih!');
    }
}
