<?php

namespace App\Jobs;

use App\Models\TaskFile;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadTaskFileJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    protected $taskId;

    protected $uploadedBy;

    /**
     * Create a new job instance.
     */
    public function __construct($file, $taskId, $uploadedBy)
    {
        $this->file = $file;
        $this->taskId = $taskId;
        $this->uploadedBy = $uploadedBy;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileContents = Storage::disk('local')->get($this->file);
        $filename = basename($this->file);

        $s3Path = 'tasks/'.$this->taskId.'/'.$filename;
        try {
            Storage::disk('s3')->put($s3Path, $fileContents);
        } catch (Exception $e) {
            // dd('S3 upload failed: ' . $e->getMessage());
            Log::error('S3 upload failed: '.$e->getMessage());

            return;
        }

        // Store in database
        $taskFile = TaskFile::create([
            'task_id' => $this->taskId,
            'file_name' => $filename,
            'file_path' => $s3Path,
            'uploaded_by' => $this->uploadedBy,
        ]);

        Storage::disk('local')->delete($this->file);
    }
}
