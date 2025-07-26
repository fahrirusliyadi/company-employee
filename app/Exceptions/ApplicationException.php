<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicationException extends Exception
{
    /**
     * Create a new application exception instance.
     */
    public function __construct(string $message = 'An application error occurred.', ?Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): RedirectResponse
    {
        return redirect()->back()
            ->with('error', $this->getMessage())
            ->withInput();
    }
}
