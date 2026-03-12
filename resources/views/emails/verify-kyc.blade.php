<x-mail::message>
# Verify Your Identity

Hello {{ $customer->name }},

To comply with financial regulations and ensure the security of your account, we require you to verify your identity.

Please click the button below to start the secure KYC verification process. It should only take a few minutes.

<x-mail::button :url="$verificationLink">
Verify Your Identity
</x-mail::button>

**Why is this required?**
Identity verification helps us prevent fraud and maintain a safe environment for all our customers.

If you have any questions, please contact our support team.

Regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
