<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (! $user) {
            return redirect('/verified-error');
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('/verified-error');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/verified-success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return redirect('/verified-success');
        }

        return redirect('/verified-error');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent']);
    }
}
