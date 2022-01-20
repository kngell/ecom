<?php

declare(strict_types=1);

class ErroHandling
{
    /**
     * Error Handler Convert All errors to exceptions by throwing and error exception
     * ===========================================================.
     * @param int $serverity
     * @param [type] $message
     * @param [type] $file
     * @param [type] $line
     * @return void
     */
    public static function errorHandler($serverity, $message, $file, $line)
    {
        if (!error_reporting() && $serverity) {
            return;
        }
        throw new ErrorException($message, 0, $file, $line);
    }

    /**
     * Exception Handler
     * ===========================================================.
     * @param [type] $exception
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);
        $error = true;
        if ($error) {
            echo '<div style="font-size: 18px;">';
            echo '<h1>Fatal Error</h1>';
            echo '<p>Uncaught exception: ' . get_class($exception) . '</p>';
            echo '<p style="color:red;font-weight:700;">Message: ' . $exception->getMessage() . '</p>';
            echo '<p>Stack trace: ' . $exception->getTraceAsString() . '</p>';
            echo '<p>Thrown in ' . $exception->getFile() . ' on line ' . $exception->getLine() . '</p>';
            echo '</div>';
        } else {
            $errolog = LOG_DIR . '/' . date('Y-m-d H:is') . 'txt';
            ini_set('erro_log', $errolog);
            $message = 'Uncaught Exception: ' . get_class($exception);
            $message .= 'with massage : ' . $exception->getMessage();
            $message .= '\nStack trace : ' . $exception->getTraceAsString();
            $message .= '\nThrown in : ' . $exception->getFile() . ' on line ' . $exception->getLine();
            error_log($message);
            echo Container::getInstance()->make(View::class)->render('Error', ['erro_message' => $message]);
        }
    }
}
