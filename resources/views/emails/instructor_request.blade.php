<h2>Instructor Request</h2>

<p>A student has requested you as their instructor.</p>

<p>
   <a href="{{ URL::temporarySignedRoute('instructor.request.accept', now()->addMinutes(60), ['id' => $request->id]) }}">
    Accept Request
</a>
</p>

<p>
    <a href="{{ URL::temporarySignedRoute('instructor.request.reject', now()->addMinutes(60), ['id' => $request->id]) }}">
        Reject Request
    </a>
</p>