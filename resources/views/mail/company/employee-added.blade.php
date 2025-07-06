<x-mail::message>
# Hello!

We have some exciting news to share with you.

**{{ $employee->first_name }} {{ $employee->last_name }}** has been successfully added to **{{ $company->name }}**.

## Contact Information
@if($employee->email)
- **Email:** {{ $employee->email }}
@endif
@if($employee->phone)
- **Phone:** {{ $employee->phone }}
@endif
@if(!$employee->email && !$employee->phone)
Contact information not provided
@endif

If you have any questions or need to make any changes, please don't hesitate to reach out.

Sincerely,<br>
{{ config('app.name') }} Team
</x-mail::message>
