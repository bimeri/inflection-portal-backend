<?php

namespace App\Console\Commands;

use App\Models\SuccessfulEmail;
use App\Utils\HtmlContentExtractor;
use Illuminate\Console\Command;

class ParseEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse raw email content and extract plain text body';
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $emails = SuccessfulEmail::where('raw_text', '')->orWhereNull('raw_text')->get();
        info("Parsing emails...");
        foreach ($emails as $email) {
            try {
                $plainText = HtmlContentExtractor::extractPlainText($email->email);
                $email->raw_text = $plainText;
                $email->save();
                info("Processed email ID: {$email->id}");
            } catch (\Exception $e) {
                $this->error("Failed processing email ID: {$email->id} - " . $e->getMessage());
            }
        }
    }

}
