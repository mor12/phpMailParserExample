<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpMimeMailParser\Parser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailParserController extends Controller
{
    public function parseEmail(Request $request)
    {
        
        // Get file path from request
        $emailFilePath = $request->input('email_path');

        Log::info('Email path: ' . $emailFilePath);

        if (!file_exists($emailFilePath)) {
            return response()->json(['error' => 'Email file not found'], 404);
        }

        try {
            // Parse email file
            $parser = new Parser();
            $parser->setPath($emailFilePath);

            // Find JSON attachments
            $attachments = $parser->getAttachments();
            foreach ($attachments as $attachment) {
                if ($attachment->getContentType() === 'application/json') {
                    return response()->json(json_decode($attachment->getContent(), true));
                }
            }

            // Find links in email body
            $text = $parser->getMessageBody('text');
            if (preg_match_all('/https?:\/\/[^\s]+/', $text, $matches)) {
                foreach ($matches[0] as $url) {
                    
                    $response = Http::get($url);

                    // Verify if response is JSON
                    if ($response->ok() && $response->header('Content-Type') === 'application/json') {
                        return response()->json($response->json());
                    }

                    // Si es una página HTML, buscar enlaces dentro de ella
                    if ($response->header('Content-Type') === 'text/html') {
                        if (preg_match('/https?:\/\/[^\s]+\.json/', $response->body(), $jsonMatches)) {
                            $jsonUrl = $jsonMatches[0];
                            $jsonResponse = Http::get($jsonUrl);

                            if ($jsonResponse->ok()) {
                                return response()->json($jsonResponse->json());
                            }
                        }
                    }
                }
            }

            return response()->json(['error' => 'JSON not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error'=> $th->getMessage()],500);
        }
    }
}
