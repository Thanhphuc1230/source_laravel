<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $orderDetails;
    public $template;

    /**
     * Create a new message instance.
     */
    public function __construct($orderDetails, $template)
    {
        $this->orderDetails = $orderDetails;
        $this->template = $template;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $order = $this->orderDetails['order'];
        $subject = str_replace('{order_id}', $order->id_order_status, $this->template->subject ?? 'New Order Placed');
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = $this->template->content ?? 'New order placed.';

        $order = $this->orderDetails['order'];
        $shipping = $this->orderDetails['shipping'];
        $products = $this->orderDetails['products'];

        // Build products list HTML
        $productsHtml = '';
        foreach ($products as $item) {
            $productName = $item->product->name_vn ?? 'Unknown Product';
            $quantity = $item->quantity;
            $price = number_format($item->price, 0, ',', '.') . ' đ';
            $subtotal = number_format($item->price * $item->quantity, 0, ',', '.') . ' đ';
            
            $productsHtml .= "<tr>
                <td>{$productName}</td>
                <td>{$quantity}</td>
                <td>{$price}</td>
                <td>{$subtotal}</td>
            </tr>";
        }

        // Replace placeholders
        $replacements = [
            '{order_id}' => $order->id_order_status,
            '{customer_name}' => $shipping->f_name_order . ' ' . $shipping->l_name_order,
            '{customer_email}' => $shipping->email,
            '{customer_phone}' => $shipping->phone,
            '{customer_address}' => $shipping->address,
            '{order_note}' => $shipping->note ?? '',
            '{payment_method}' => $order->payment_method,
            '{order_total}' => number_format($order->total, 0, ',', '.') . ' đ',
            '{order_date}' => $order->created_at->format('d/m/Y H:i'),
            '{products_list}' => $productsHtml,
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        return new Content(
            htmlString: $content,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
