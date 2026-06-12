<?php

namespace App\Jobs;

use App\Enums\StatutReceiptEnum;
use App\Models\Receipt;
use App\Services\ExtractionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExtraireDepensesDuRecu implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $timeout = 60;
    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(public int $receiptId)
    {
        $this->onQueue('default');
    }

    public function handle(ExtractionService $extractionService): void
    {
        $receipt = Receipt::findOrFail($this->receiptId);

        if ($receipt->status !== StatutReceiptEnum::Pending) {
            Log::info("Receipt {$this->receiptId} already processed, skipping.");
            return;
        }

        Log::info("Starting AI extraction for receipt {$this->receiptId}.");

        $expensesData = $extractionService->extractFromText($receipt->source_text);

        $receipt->expenses()->createMany($expensesData);

        $receipt->update([
            'status' => StatutReceiptEnum::Processed,
            'payload_ia' => [
                'expenses' => $expensesData,
                'extracted_at' => now()->toISOString(),
            ],
        ]);

        Log::info("Extraction complete for receipt {$this->receiptId}: " . count($expensesData) . " expenses created.");
    }

    public function failed(?Throwable $exception): void
    {
        Log::error("Extraction failed for receipt {$this->receiptId}: " . ($exception?->getMessage() ?? 'Unknown error'));

        $receipt = Receipt::find($this->receiptId);
        if ($receipt) {
            $payload = $receipt->payload_ia ?? [];
            $payload['error'] = [
                'message' => $exception?->getMessage() ?? 'Unknown error',
                'failed_at' => now()->toISOString(),
            ];
            $receipt->update([
                'status' => StatutReceiptEnum::Failed,
                'payload_ia' => $payload,
            ]);
        }
    }
}
