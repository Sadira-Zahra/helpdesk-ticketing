<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Tiket;

class TicketReopenedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct(Tiket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('login_petugas'); // atau route('tiket.index')

        return (new MailMessage)
            ->subject('Tiket Dibuka Kembali (Reopened) - #' . $this->ticket->nomor)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Tiket yang sebelumnya sudah closed telah dibuka kembali oleh Administrator')
            ->line('Nomor Tiket: ' . $this->ticket->nomor)
            ->line('Judul: ' . $this->ticket->judul)
            ->line('Departemen: ' . ($this->ticket->departemen ? $this->ticket->departemen->nama_departemen : '-'))
            ->line('Status Sekarang: OPEN')
            ->line('Catatan: ' . ($this->ticket->catatan ?? '-'))
            ->action('Assign Tiket', $url)
            ->line('Silakan assign tiket ini kembali ke teknisi.');
    }
}
