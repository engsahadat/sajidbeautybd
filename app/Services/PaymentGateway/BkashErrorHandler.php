<?php

namespace App\Services\PaymentGateway;

/**
 * bKash Error Code Handler
 * Maps bKash error codes to user-friendly messages
 * Reference: https://developer.bka.sh/docs/error-codes
 */
class BkashErrorHandler
{
    /**
     * Get user-friendly error message for bKash error code
     * 
     * @param string $errorCode The bKash error code
     * @param string|null $defaultMessage Optional default message
     * @return string User-friendly error message
     */
    public static function getMessage(string $errorCode, ?string $defaultMessage = null): string
    {
        $messages = [
            // Authentication & Authorization Errors
            '2001' => 'Invalid credentials. Please contact support.',
            '2020' => 'An error occurred. Please try again.',
            '2024' => 'An error occurred. Please try again.',
            '2025' => 'Invalid request. Please try again.',
            '2043' => 'Security credentials are incorrect.',
            
            // Payment Errors
            '2002' => 'Invalid payment. Please try again.',
            '2003' => 'Payment processing failed. Please try again.',
            '2006' => 'Invalid payment amount.',
            '2007' => 'Invalid currency.',
            '2008' => 'Invalid payment type.',
            '2031' => 'Invalid invoice number.',
            '2033' => 'Transaction not found.',
            '2056' => 'Invalid payment status.',
            '2060' => 'Payment cannot be completed. Prerequisites not met.',
            '2062' => 'This payment has already been completed.',
            '2068' => 'Transaction has already been completed.',
            '2069' => 'Transaction has been cancelled.',
            '2117' => 'This payment has already been completed.',
            '2119' => 'This payment has already been processed.',
            
            // Account & Balance Errors
            '2009' => 'Invalid bKash wallet.',
            '2023' => 'Insufficient balance in your bKash account. Please recharge and try again.',
            '2057' => 'This is not a valid bKash account.',
            '2058' => 'This is not a customer wallet.',
            
            // OTP & PIN Errors
            '2010' => 'Invalid OTP. Please enter the correct OTP.',
            '2011' => 'Invalid PIN.',
            '2013' => 'OTP resend limit exceeded. Please try again later.',
            '2014' => 'Wrong PIN entered.',
            '2015' => 'Maximum wrong PIN attempts exceeded. Please try again later.',
            '2016' => 'Wrong verification code.',
            '2017' => 'Verification limit exceeded. Please try again later.',
            '2018' => 'OTP verification time expired. Please request a new OTP.',
            '2019' => 'PIN verification time expired. Please try again.',
            '2059' => 'Multiple OTP requests detected. Please wait and try again.',
            
            // Duplicate Transaction Errors
            '2029' => 'Duplicate transaction detected. Please wait before trying again.',
            
            // Callback & URL Errors
            '2049' => 'Invalid callback URL configuration.',
            
            // MSISDN Errors
            '2012' => 'Invalid phone number.',
            '2045' => 'Phone number does not exist.',
            '2048' => 'Invalid payer reference.',
            
            // Account Status Errors
            '2037' => 'Your account status does not allow this transaction.',
            '2038' => 'Account restrictions prevent this transaction.',
            '2039' => 'Recipient account status does not allow this transaction.',
            '2040' => 'Recipient account restrictions prevent this transaction.',
            '2041' => 'Recipient account does not support this service.',
            '2044' => 'Account is not active or does not support this service.',
            '2046' => 'Account does not have the required service subscription.',
            
            // Agreement Errors
            '2021' => 'Invalid agreement.',
            '2022' => 'Agreement does not exist.',
            '2027' => 'Agreement already exists.',
            '2050' => 'Agreement already exists between you and merchant.',
            '2051' => 'Invalid agreement ID.',
            '2052' => 'Agreement is incomplete.',
            '2053' => 'Agreement has been cancelled.',
            '2054' => 'Agreement prerequisites not met.',
            '2055' => 'Invalid agreement status.',
            '2061' => 'Only the initiator can perform this action.',
            '2066' => 'Agreement is not shared with merchant.',
            '2116' => 'Agreement has already been completed.',
            
            // Reversal & Refund Errors
            '2026' => 'Refund amount cannot exceed original transaction amount.',
            '2028' => 'Transaction does not exist for reversal.',
            '2034' => 'Transaction has already been reversed.',
            '2035' => 'No permission to reverse this transaction.',
            '2042' => 'No permission to reverse this transaction.',
            
            // Mode & Configuration Errors
            '2030' => 'Invalid request type.',
            '2032' => 'Invalid transfer type.',
            '2063' => 'Payment mode is not valid.',
            '2064' => 'This payment mode is currently unavailable.',
            '2065' => 'Required field is missing.',
            '2067' => 'Invalid permission.',
            '2118' => 'Invalid platform value.',
            
            // Date & Time Errors
            '2004' => 'Invalid first payment date.',
            '2005' => 'Invalid frequency.',
            
            // Technical Errors
            '2047' => 'Data format error.',
            '503' => 'bKash system is under maintenance. Please try again later.',
            '9999' => 'Service unavailable. Please try again later.',
        ];

        return $messages[$errorCode] ?? ($defaultMessage ?? 'Payment failed. Please try again or contact support.');
    }

    /**
     * Check if error code represents insufficient balance
     */
    public static function isInsufficientBalance(string $errorCode): bool
    {
        return $errorCode === '2023';
    }

    /**
     * Check if error code represents a duplicate transaction
     */
    public static function isDuplicateTransaction(string $errorCode): bool
    {
        return $errorCode === '2029';
    }

    /**
     * Check if error code represents wrong OTP/PIN
     */
    public static function isWrongCredentials(string $errorCode): bool
    {
        return in_array($errorCode, ['2010', '2014', '2016']);
    }

    /**
     * Check if error code represents exceeded attempts
     */
    public static function isAttemptsExceeded(string $errorCode): bool
    {
        return in_array($errorCode, ['2013', '2015', '2017']);
    }

    /**
     * Check if error code represents timeout/expiry
     */
    public static function isTimeout(string $errorCode): bool
    {
        return in_array($errorCode, ['2018', '2019']);
    }

    /**
     * Check if error is recoverable (user can retry)
     */
    public static function isRecoverable(string $errorCode): bool
    {
        $nonRecoverableErrors = [
            '2062', '2068', '2069', '2117', '2119', // Already completed/cancelled
            '2053', // Agreement cancelled
            '2034', // Already reversed
            '2015', '2017', // Attempts exceeded
        ];

        return !in_array($errorCode, $nonRecoverableErrors);
    }

    /**
     * Get error category for logging
     */
    public static function getCategory(string $errorCode): string
    {
        $categories = [
            'auth' => ['2001', '2043'],
            'payment' => ['2002', '2003', '2006', '2007', '2008', '2031', '2056', '2060', '2062', '2117', '2119'],
            'balance' => ['2023'],
            'otp_pin' => ['2010', '2011', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2059'],
            'duplicate' => ['2029'],
            'account' => ['2009', '2037', '2038', '2039', '2040', '2041', '2044', '2046', '2057', '2058'],
            'agreement' => ['2021', '2022', '2027', '2050', '2051', '2052', '2053', '2054', '2055', '2061', '2066', '2116'],
            'system' => ['2020', '2024', '2047', '503', '9999'],
            'validation' => ['2025', '2065'],
        ];

        foreach ($categories as $category => $codes) {
            if (in_array($errorCode, $codes)) {
                return $category;
            }
        }

        return 'unknown';
    }
}
