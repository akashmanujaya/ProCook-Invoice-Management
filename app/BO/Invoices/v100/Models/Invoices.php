<?php

namespace App\BO\Invoices\v100\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * The Customers model represents customer entities in the application.
 *
 * This model is linked with the 'customers' table in the database and includes
 * attributes like first name, last name, email, phone, and address. The model
 * utilizes the SoftDeletes trait for safe deletion and the Notifiable trait
 * for Laravel's notification system.
 *
 * @package App\BO\Customers\v100\Models
 */
class Invoices extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number', 'first_name', 'last_name', 'invoice_date',
        'payment_term', 'due_date', 'description', 'total_amount', 'status'
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'status' => 'boolean'
    ];
}