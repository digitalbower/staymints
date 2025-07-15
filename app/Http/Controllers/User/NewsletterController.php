<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:news_letters,email',
        ], [
            'email.unique' => "You're already subscribed with this email address.",
        ]);
        NewsLetter::create(['email' => $request->email]);
    
        return redirect()->back()->with('success', 'Thank You for Subscribing!');
    }
}
