<?php

return [
    // Billing / Invoice
    'billing' => 'វិក្កយប័ត្រ',
    'invoice' => 'វិក្កយប័ត្រ',
    'invoices' => 'វិក្កយប័ត្រ',
    'payment_invoice' => 'វិក្កយប័ត្រទូទាត់',
    'invoice_number' => 'លេខវិក្កយប័ត្រ',
    'invoice_date' => 'កាលបរិច្ឆេទវិក្កយប័ត្រ',
    'due_date' => 'កាលបរិច្ឆេទបង់ប្រាក់',
    'total_amount' => 'ចំនួនសរុប',
    'paid_amount' => 'ចំនួនដែលបានបង់',
    'balance_amount' => 'ចំនួននៅសល់',
    'payment_method' => 'វិធីសាស្ត្របង់ប្រាក់',
    'status' => 'ស្ថានភាព',
    'notes' => 'កំណត់ចំណាំ',
    'items' => 'ធាតុ',
    'subtotal' => 'សរុបរង',
    'tax' => 'ពន្ធ',
    'discount' => 'បញ្ចុះតម្លៃ',

    // Status
    'paid' => 'បានបង់',
    'unpaid' => 'មិនទាន់បង់',
    'partially_paid' => 'បង់បានខ្លះ',
    'overdue' => 'ហួសកំណត់',
    'cancelled' => 'បានលុប',
    'pending_cancellation' => 'រងចាំលុប',

    // Actions
    'view_invoice' => 'មើលវិក្កយប័ត្រ',
    'edit_invoice' => 'កែប្រែវិក្កយប័ត្រ',
    'delete_invoice' => 'លុបវិក្កយប័ត្រ',
    'print_invoice' => 'បោះពុម្ពវិក្កយប័ត្រ',
    'process_payment' => 'ដំណើរការបង់ប្រាក់',

    // Messages
    'invoice_updated_successfully' => 'វិក្កយប័ត្រត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។',
    'invoice_update_failed' => 'បរាជ័យក្នុងការធ្វើបច្ចុប្បន្នភាពវិក្កយប័ត្រ។',
    'invoice_deleted_successfully' => 'វិក្កយប័ត្រត្រូវបានលុបដោយជោគជ័យ។',
    'invoice_deletion_failed' => 'បរាជ័យក្នុងការលុបវិក្កយប័ត្រ។',
    'cannot_delete_paid_invoice' => 'មិនអាចលុបវិក្កយប័ត្រដែលបានបង់ប្រាក់បានទេ។',
    'void_invoice_updated_successfully' => 'វិក្កយប័ត្រលុបត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។',
    'void_invoice_update_failed' => 'បរាជ័យក្នុងការធ្វើបច្ចុប្បន្នភាពវិក្កយប័ត្រលុប។',
    'void_invoice_deleted_successfully' => 'វិក្កយប័ត្រលុបត្រូវបានលុបដោយជោគជ័យ។',
    'void_invoice_deletion_failed' => 'បរាជ័យក្នុងការលុបវិក្កយប័ត្រលុប។',
    'pending_cancel_invoice_deleted_successfully' => 'វិក្កយប័ត្ររងចាំលុបត្រូវបានលុបដោយជោគជ័យ។',
    'pending_cancel_invoice_deletion_failed' => 'បរាជ័យក្នុងការលុបវិក្កយប័ត្ររងចាំលុប។',
    'cancellation_approved' => 'ការលុបត្រូវបានអនុម័តដោយជោគជ័យ។',
    'cancellation_rejected' => 'ការលុបត្រូវបានបដិសេធដោយជោគជ័យ។',
    'pending_cancel_update_failed' => 'បរាជ័យក្នុងការធ្វើបច្ចុប្បន្នភាពវិក្កយប័ត្ររងចាំលុប។',
    'invalid_operation' => 'ប្រតិបត្តិការមិនត្រឹមត្រូវ។',

    // Payment
    'payment' => 'ការបង់ប្រាក់',
    'payments' => 'ការបង់ប្រាក់',
    'payment_history' => 'ប្រវត្តិការបង់ប្រាក់',
    'payment_amount' => 'ចំនួនបង់ប្រាក់',
    'payment_date' => 'កាលបរិច្ឆេទបង់ប្រាក់',
    'reference_number' => 'លេខយោង',
    'processed_by' => 'ដំណើរការដោយ',
    'total_payments' => 'ការបង់ប្រាក់សរុប',
    'payment_count' => 'ចំនួនការបង់ប្រាក់',

    // Filters
    'filter_by_status' => 'ត្រងតាមស្ថានភាព',
    'filter_by_date' => 'ត្រងតាមកាលបរិច្ឆេទ',
    'filter_by_payment_method' => 'ត្រងតាមវិធីសាស្ត្របង់ប្រាក់',
    'date_from' => 'កាលបរិច្ឆេទពី',
    'date_to' => 'កាលបរិច្ឆេទដល់',
    'amount_from' => 'ចំនួនពី',
    'amount_to' => 'ចំនួនដល់',
    'search' => 'ស្វែងរក',

    // Invoice History
    'invoice_history' => 'ប្រវត្តិវិក្កយប័ត្រ',
    'deleted_invoices' => 'វិក្កយប័ត្រដែលបានលុប',
    'deleted_at' => 'បានលុបនៅ',

    // Void Invoices
    'void_invoices' => 'វិក្កយប័ត្រលុប',
    'cancel_invoice' => 'លុបវិក្កយប័ត្រ',

    // Pending Cancel Invoices
    'pending_cancel_invoices' => 'វិក្កយប័ត្ររងចាំលុប',
    'pending_cancel_invoice' => 'វិក្កយប័ត្ររងចាំលុប',
    'approve_cancellation' => 'អនុម័តការលុប',
    'reject_cancellation' => 'បដិសេធការលុប',
    'cancellation_reason' => 'ហេតុផលលុប',
];
