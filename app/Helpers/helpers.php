<?php

namespace App\Helpers;

/**
 * Returns a list of available modules with their versions.
 *
 * @return array
 */
function getModules()
{
    return [
        [
            'module' => 'Invoices',
            'version' => 'v100'
        ]
    ];
}

/**
 * Sanitize and trim input data.
 *
 * @param array $data The input data.
 * @return array The sanitized data.
 */
function sanitizeInput(array $data)
{
    return array_map(function ($value) {
        return is_string($value) ? trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8')) : $value;
    }, $data);
}

/**
 * Calculate due date if it's missing.
 *
 * @param array $data The input data.
 * @return array The data with calculated due date if necessary.
 */
function calculateDueDate(array $data)
{
    if (empty($data['due_date'])) {
        $invoiceDate = new \DateTime($data['invoice_date']);
        $dueDate = $invoiceDate->add(new \DateInterval('P' . $data['payment_term'] . 'D'));
        $data['due_date'] = $dueDate->format('Y-m-d H:i:s');
    }
    return $data;
}
