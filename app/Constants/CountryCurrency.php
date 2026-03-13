<?php

namespace App\Constants;

class CountryCurrency
{
    /**
     * List of supported countries with their flag codes and currencies.
     * Used in recipient country dropdown and currency selectors.
     */
    public const COUNTRIES = [
        ['name' => 'India',            'code' => 'IN', 'flag' => 'in', 'currency' => 'INR'],
        ['name' => 'Pakistan',         'code' => 'PK', 'flag' => 'pk', 'currency' => 'PKR'],
        ['name' => 'Philippines',      'code' => 'PH', 'flag' => 'ph', 'currency' => 'PHP'],
        ['name' => 'Bangladesh',       'code' => 'BD', 'flag' => 'bd', 'currency' => 'BDT'],
        ['name' => 'Nepal',            'code' => 'NP', 'flag' => 'np', 'currency' => 'NPR'],
        ['name' => 'Sri Lanka',        'code' => 'LK', 'flag' => 'lk', 'currency' => 'LKR'],
        ['name' => 'Nigeria',          'code' => 'NG', 'flag' => 'ng', 'currency' => 'NGN'],
        ['name' => 'Ghana',            'code' => 'GH', 'flag' => 'gh', 'currency' => 'GHS'],
        ['name' => 'Kenya',            'code' => 'KE', 'flag' => 'ke', 'currency' => 'KES'],
        ['name' => 'Mexico',           'code' => 'MX', 'flag' => 'mx', 'currency' => 'MXN'],
        ['name' => 'United States',    'code' => 'US', 'flag' => 'us', 'currency' => 'USD'],
        ['name' => 'United Kingdom',   'code' => 'GB', 'flag' => 'gb', 'currency' => 'GBP'],
        ['name' => 'European Union',   'code' => 'EU', 'flag' => 'eu', 'currency' => 'EUR'],
        ['name' => 'Canada',           'code' => 'CA', 'flag' => 'ca', 'currency' => 'CAD'],
        ['name' => 'Australia',        'code' => 'AU', 'flag' => 'au', 'currency' => 'AUD'],
        ['name' => 'UAE',              'code' => 'AE', 'flag' => 'ae', 'currency' => 'AED'],
    ];

    /**
     * Supported currencies with labels and flag codes.
     */
    public const CURRENCIES = [
        ['code' => 'CHF', 'name' => 'Swiss Franc',        'flag' => 'ch'],
        ['code' => 'INR', 'name' => 'Indian Rupee',        'flag' => 'in'],
        ['code' => 'PKR', 'name' => 'Pakistani Rupee',     'flag' => 'pk'],
        ['code' => 'PHP', 'name' => 'Philippine Peso',     'flag' => 'ph'],
        ['code' => 'BDT', 'name' => 'Bangladeshi Taka',    'flag' => 'bd'],
        ['code' => 'NPR', 'name' => 'Nepalese Rupee',      'flag' => 'np'],
        ['code' => 'NGN', 'name' => 'Nigerian Naira',      'flag' => 'ng'],
        ['code' => 'GHS', 'name' => 'Ghanaian Cedi',       'flag' => 'gh'],
        ['code' => 'KES', 'name' => 'Kenyan Shilling',     'flag' => 'ke'],
        ['code' => 'MXN', 'name' => 'Mexican Peso',        'flag' => 'mx'],
        ['code' => 'USD', 'name' => 'US Dollar',           'flag' => 'us'],
        ['code' => 'GBP', 'name' => 'British Pound',       'flag' => 'gb'],
        ['code' => 'EUR', 'name' => 'Euro',                'flag' => 'eu'],
        ['code' => 'CAD', 'name' => 'Canadian Dollar',     'flag' => 'ca'],
        ['code' => 'AUD', 'name' => 'Australian Dollar',   'flag' => 'au'],
        ['code' => 'AED', 'name' => 'UAE Dirham',          'flag' => 'ae'],
    ];

    /**
     * Get just country names for a simple dropdown.
     */
    public static function countryNames(): array
    {
        return array_column(self::COUNTRIES, 'name');
    }

    /**
     * Get currency codes for a dropdown.
     */
    public static function currencyCodes(): array
    {
        return array_column(self::CURRENCIES, 'code');
    }
}
