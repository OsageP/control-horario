<? php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php
public function destroy(Request $request)
{
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}