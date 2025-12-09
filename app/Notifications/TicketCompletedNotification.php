<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Tiket;

class TicketCompletedNotification extends Notification
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
            ->subject('Tiket Selesai Dikerjakan - #' . $this->ticket->nomor)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Tiket telah diselesaikan oleh teknisi')
            ->line('Nomor Tiket: ' . $this->ticket->nomor)
            ->line('Judul: ' . $this->ticket->judul)
            ->line('Departemen: ' . ($this->ticket->departemen ? $this->ticket->departemen->nama_departemen : '-'))
            ->line('Teknisi: ' . ($this->ticket->teknisi ? $this->ticket->teknisi->nama : '-'))
            ->line('Solusi: ' . ($this->ticket->solusi ?? '-'))
            // ✅ Null check tanggal_selesai
            ->line('Tanggal Selesai: ' . ($this->ticket->tanggal_selesai ? $this->ticket->tanggal_selesai->format('d/m/Y H:i') : '-'))
            ->action('Tutup Tiket', $url)
            ->line('Silakan review dan tutup tiket jika sudah sesuai.');
    }
}
