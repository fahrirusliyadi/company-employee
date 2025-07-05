<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ApplicationException extends Exception
{
    /**
     * Create a new application exception instance.
     */
    public function __construct(string $message = 'An application error occurred.', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
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
