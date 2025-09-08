@component('mail::message')
    # إعادة تعيين كلمة المرور

    مرحباً {{ $user->name }},

    لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بك.

    @component('mail::button', ['url' => $resetUrl])
        إعادة تعيين كلمة المرور
    @endcomponent

    إذا لم تطلب ذلك، يمكنك تجاهل هذه الرسالة.

    شكراً،
    {{ config('app.name') }}
@endcomponent
